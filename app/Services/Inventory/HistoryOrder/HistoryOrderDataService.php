<?php

namespace App\Services\Inventory\HistoryOrder;

use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodPack;
use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\OrderLogActivity;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class HistoryOrderDataService
{
  const CACHE_ORDER_BY_ID_KEY = "order_and_log_data_by_id";
  const CACHE_ORDER_BY_PO_KEY = "order_and_log_data_by_PO";

  // ---------- Fungsi untuk menampilkan data history order ke tabel :begin ----------
  public function historyOrderTable(Request $request)
  {
    // ---------- Ambil data ----------
    $query = OrderBlood::withTrashed()
      ->with([
        'vendors:id,public_id,name',
        'orderBloodDetails:id,public_id,order_blood_id,blood_pack_id',
        'orderBloodDetails.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
      ]);

    // ---------- Terapkan filter untuk tanggal pada data ----------
    $this->applyDateFilter($query, $request);

    // ---------- Filter berdasarkan vendor ----------
    if ($request->filled('vendor')) {
      $query->whereHas('vendors', function ($q) use ($request) {
        $q->where('public_id', $request->vendor);
      });
    }

    // ---------- Filter berdasarkan status ----------
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    // ---------- Handle search pada kolom data ----------
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('po_number', 'like', "%{$search}%")
          ->orWhereHas('vendors', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
          })
          ->orWhereHas('orderBloods.bloodPacks', function ($q) use ($search) {
            $q->where('blood_group', 'like', "%{$search}%");
          });
      });
    }

    // ---------- Urutkan data ----------
    if ($request->filled('sort_by')) {
      $query->orderBy(
        $request->sort_by,
        $request->sort_dir ?? 'asc'
      );
    } else {
      $query->latest();
    }

    // ---------- Tampilkan data ke tabel frontend ----------
    return $query->paginate($request->get('per_page', 10));
  }
  // ---------- Fungsi untuk menampilkan data history order ke tabel :end ----------

  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data :begin ----------
  protected function applyDateFilter(Builder $query, Request $request)
  {
    // ---------- Terima data start_date & end_date dari frontend ----------
    $start = $request->start_date;
    $end = $request->end_date;

    if ($start && $end) {
      try {
        // ---------- Format data tanggal menjadi d-m-Y H:i ----------
        $startDate = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();

        // ---------- Cek apakah kolom ada atau tidak ----------
        $table = $query->getModel()->getTable();

        // ---------- Jalankan filter data ----------
        if (Schema::hasColumn($table, 'created_at')) {
          $query->whereBetween('created_at', [$startDate, $endDate]);
        }
      } catch (\Exception $e) {
        logger()->error('Date filter error: ' . $e->getMessage());
      }
    }
  }
  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data :end ----------

  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :begin ----------
  public function getDataOrderAndLogById(string $id)
  {
    $cacheKey = self::CACHE_ORDER_BY_ID_KEY . ":{$id}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {

      $order = OrderBlood::where('public_id', $id)
        ->with(['orderBloodDetails', 'orderBloodDetails.bloodPacks', 'vendors', 'users.roles'])
        ->firstOrFail();

      $log = OrderLogActivity::where('po_number', $order->po_number)
        ->get();

      return [
        'order' => $order,
        'log' => $log,
      ];
    });
  }
  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :end ----------

  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :begin ----------
  public function getDataOrderByPO(string $poNumber)
  {
    $cacheKey = self::CACHE_ORDER_BY_PO_KEY . ":{$poNumber}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($poNumber) {
      $order = OrderBlood::where('po_number', $poNumber)
        ->with(['orderBloodDetails', 'vendors', 'users.roles'])
        ->firstOrFail();

      return $order;
    });
  }
  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :end ----------

  // ---------- Fungsi untuk mengambil data order berdasarkan id :begin ----------
  public function getDataOrderById(string $id)
  {
    $order = OrderBlood::where('public_id', $id)
      ->with(['orderBloodDetails', 'vendors', 'users.roles'])
      ->firstOrFail();

    return $order;
  }
  // ---------- Fungsi untuk mengambil data order berdasarkan id :end ----------

  // ---------- Fungsi untuk preview PO File (tanpa simpan ke DB & storage) :begin ----------
  public function previewPoFile(string $poNumber)
  {
    // ---------- Ambil data order ----------
    $order = OrderBlood::where('po_number', $poNumber)
      ->select([
        'id',
        'public_id',
        'vendor_id',
        'po_number',
        'total_quantity',
        'description',
        'ordered_by_user_id',
        'created_at',
      ])
      ->with([
        'vendors:id,public_id,name,address',
        'users:id,public_id,name',
        'users.roles',
        'orderBloodDetails:id,public_id,order_blood_id,blood_pack_id,quantity,note',
        'orderBloodDetails.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
      ])
      ->firstOrFail();

    // ---------- Generate PDF dari blade (tanpa simpan) ----------
    $pdf = Pdf::loadView('pdf.history_order.po_file', [
      'order' => $order,
      'details' => $order->orderBloodDetails,
      'vendor' => $order->vendors,
    ])->setPaper('a4', 'portrait');

    // ---------- Stream langsung ke browser (inline, bukan download) ----------
    return response($pdf->output(), 200, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="PREVIEW-' . $poNumber . '.pdf"',
    ]);
  }
  // ---------- Fungsi untuk preview PO File (tanpa simpan ke DB & storage) :end ----------
}
