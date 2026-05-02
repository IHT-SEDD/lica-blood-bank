<?php

namespace App\Services\Inventory;

use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodPack;
use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\OrderLogActivity;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class HistoryOrderService
{
  // ---------- Fungsi untuk menampilkan data history order ke tabel :begin ----------
  public function historyOrderTable(Request $request)
  {
    // ---------- Ambil data ----------
    $query = OrderBlood::withTrashed()
      ->with([
        'vendors:id,public_id,name',
        'orderBloods:id,public_id,order_blood_id,blood_pack_id',
        'orderBloods.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
      ]);

    // ---------- Terapkan filter untuk tanggal pada data ----------
    $this->applyDateFilter($query, $request);

    // ---------- Filter berdasarkan vendor ----------
    if ($request->filled('vendor_id')) {
      $query->whereHas('vendors', function ($q) use ($request) {
        $q->where('public_id', $request->vendor_id);
      });
    }

    // ---------- Filter berdasarkan status ----------
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    // ---------- Filter berdasarkan blood_group dari detail ----------
    if ($request->filled('blood_group')) {
      $query->whereHas('orderBloods.bloodPacks', function ($q) use ($request) {
        $q->where('blood_group', $request->blood_group);
      });
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

  // ---------- Fungsi untuk menambahkan data order baru :begin ----------
  public function insertNewOrder(Request $request, ?string $draft)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Hitung total quantity dari semua blood data ----------
      $totalQuantity = collect($request->blood_data)
        ->sum(fn($item) => (int) $item['quantity']);

      // ---------- Validasi PO Number ----------
      $poNumberExists = OrderBlood::where('po_number', $request->po_number)->exists();
      $poNumber = (!empty($request->po_number) && !$poNumberExists) ? $request->po_number : $this->generatePoNumber();

      // ---------- Ambil data vendor ----------
      $vendor = Vendor::where('public_id', $request->vendor_id)->first();

      $vendorId = $vendor?->id;
      $vendorName = $vendor?->name;

      // ---------- Ambil data user ----------
      $user = Auth::user();

      // ---------- Ambil status order ----------
      $status = $draft === 'draft' ? OrderBloodStatus::DRAFT : OrderBloodStatus::ORDER_CREATED;

      // ---------- Insert ke tabel order_bloods ----------
      $newOrderData = OrderBlood::create([
        'vendor_id' => $vendorId,
        'po_number' => $poNumber,
        'total_quantity' => $totalQuantity,
        'description' => $request->description ?? NULL,
        'status' => $status,
        'ordered_by_user_id' => $user->id,
      ]);

      // ---------- Insert detail per blood data ----------
      $orderBloodDetails = [];

      foreach ($request->blood_data as $item) {
        $bloodPack = BloodPack::where('public_id', $item['blood_pack_id'])->firstOrFail();

        $detail = OrderBloodDetail::create([
          'order_blood_id' => $newOrderData->id,
          'blood_pack_id' => $bloodPack->id,
          'note' => $item['note'],
          'quantity' => $item['quantity'],
        ]);

        $orderBloodDetails[] = $detail;
      }

      // ---------- Ambil status order log ----------
      $statusLog = $draft === 'draft'
        ? OrderLogActivityStatus::DRAFT_CREATED
        : OrderLogActivityStatus::ORDER_CREATED;

      // ---------- Insert Order Log Activity ----------
      OrderLogActivity::create([
        'po_number' => $poNumber,
        'vendor_name' => $vendorName,
        'order_data' => $newOrderData->toArray(),
        'order_blood_data' => collect($orderBloodDetails)
          ->map(fn($d) => $d->toArray())
          ->toArray(),
        'order_by_user_name' => $user->name,
        'status' => $statusLog,
        'description' => generateOrderLogDescription(
          $statusLog,
          $poNumber,
          $user->id
        ),
        'ordered_at' => $newOrderData->created_at,
      ]);

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger('info', 'New order data inserted succesfully!', [
        'id' => $newOrderData->id,
        'payload' => $newOrderData,
      ], 200, 'neworderadd');

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => 'New order data inserted succesfully!',
        'data' => $newOrderData
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger('error', 'New order data failed to insert!', [
        'payload' => $request->all(),
        'error' => $e->getMessage(),
      ], 500, 'neworderadd');

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => 'New order data failed to insert!',
      ], 500);
    }
  }
  // ---------- Fungsi untuk menambahkan data order baru :end ----------

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

  // ---------- Fungsi untuk membuat po number :begin ----------
  public function generatePoNumber(): string
  {
    $year = now()->format('Y');
    $last = OrderBlood::where('po_number', 'like', "P{$year}OB%")
      ->lockForUpdate()
      ->orderByDesc('po_number')
      ->first();
    $nextNumber = $last
      ? ((int) substr($last->po_number, -6) + 1)
      : 1;
    $poNumber = 'P' . $year . 'OB' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

    // ---------- Lempar data ke frontend ----------
    return $poNumber;
  }
  // ---------- Fungsi untuk membuat po number :end ----------

  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :begin ----------
  public function getDataOrderAndLogById(string $id)
  {
    $order = OrderBlood::where('public_id', $id)
      ->with(['orderBloods', 'vendors', 'users.roles'])
      ->firstOrFail();
    $orderLog = OrderLogActivity::where('po_number', $order->po_number)->firstOrFail();

    return [
      'order' => $order,
      'log' => $orderLog,
    ];
  }
  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :end ----------

  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :begin ----------
  public function getDataOrderByPO(string $poNumber)
  {
    $order = OrderBlood::where('po_number', $poNumber)
      ->with(['orderBloods', 'vendors', 'users.roles'])
      ->firstOrFail();

    return $order;
  }
  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :end ----------
}
