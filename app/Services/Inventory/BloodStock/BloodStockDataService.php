<?php

namespace App\Services\Inventory\BloodStock;

use App\Exports\Inventory\BloodStock\BloodStockExport;
use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodStockLogActivity;
use App\Models\OrderBlood;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class BloodStockDataService
{
  const CACHE_BLOOD_STOCK_LOG_KEY = "blood_stock_log_data";

  // ---------- Fungsi select po pada form add ----------
  public function selectPO(Request $request): array
  {
    $search = $request->filled('q', '');

    // ---------- Ambil data dari model ----------
    $query = OrderBlood::withoutTrashed()
      ->select([
        'id',
        'public_id',
        'po_number',
      ])
      ->whereIn('status', ['all_order_stock_registered', 'some_order_stock_registered']);

    // ---------- Handle search field ----------
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('po_number', 'like', "%{$search}%");
      });
    }

    $data = $query
      ->limit(100)
      ->get();

    return [
      'results' => $data->map(function ($item) {
        return [
          'id' => $item->public_id ?? $item->id,
          'text' => $item->po_number,
        ];
      })->values(),
    ];
  }

  // ---------- Fungsi untuk menampilkan data ke tabel ----------
  public function bloodStockTable(Request $request)
  {
    $query = BloodStock::withTrashed()
      ->join('blood_packs', 'blood_stocks.blood_pack_id', '=', 'blood_packs.id')
      ->selectRaw('
            blood_packs.public_id,
            blood_packs.blood_group,
            blood_packs.blood_rhesus,
            blood_packs.blood_component,
            blood_packs.warning_quantity,
            blood_packs.danger_quantity,
            MAX(blood_stocks.updated_at) as updated_at,
            COUNT(blood_stocks.id) as total_blood_data
        ')
      ->groupBy(
        'blood_packs.public_id',
        'blood_packs.blood_group',
        'blood_packs.blood_rhesus',
        'blood_packs.blood_component',
        'blood_packs.warning_quantity',
        'blood_packs.danger_quantity'
      );

    $this->applyDateFilter($query, $request);

    if ($request->filled('search')) {
      $search = $request->search;

      $query->where(function ($q) use ($search) {
        $q->where('blood_packs.blood_group', 'like', "%{$search}%")
          ->orWhere('blood_packs.blood_component', 'like', "%{$search}%")
          ->orWhere('blood_packs.blood_rhesus', 'like', "%{$search}%");
      });
    }

    if ($request->filled('sort_by')) {
      $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
    } else {
      $query->orderByDesc('total_blood_data');
    }

    return $query->paginate($request->filled('per_page', 10));
  }

  // ---------- Fungsi untuk menampilkan data ke tabel stock blood detail ----------
  public function detailStockBloodDataTable(Request $request, string $id)
  {
    $bloodPackId = BloodPack::where('public_id', $id)->value('id');
    $query = BloodStock::withTrashed()
      ->select([
        'id',
        'public_id',
        'bag_number',
        'bag_number_lica',
        'blood_pack_id',
        'storage_rack_id',
        'blood_volume',
        'aftap_date',
        'process_date',
        'expiry_date',
        'blood_status',
        'used_at',
        'created_at',
        'deleted_at'
      ])
      ->with([
        'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
        'storageRacks:id,public_id,blood_group,rack_type,name',
      ])->where('blood_pack_id', $bloodPackId);

    $this->applyDateFilter($query, $request);

    // ---------- Filter berdasarkan status ----------
    if ($request->filled('status')) {
      $query->where('blood_status', $request->status);
    }

    if ($request->filled('sort_by')) {
      $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
    }

    return $query->paginate($request->filled('per_page', 10));
  }

  // ---------- Fungsi untuk mengambil data stock blood by id ----------
  public function getDataDetailStockBlood(string $id)
  {
    $stockBloodData = BloodStock::withTrashed()->where('public_id', $id)
      ->with([
        'incomingBloodDetails',
        'bloodPacks',
        'storageRacks:id,public_id,blood_group,rack_type,name'
      ])
      ->first();
    return $stockBloodData;
  }

  // ---------- Fungsi untuk mendapatkan status label ----------
  public function bloodStockStatusLabel()
  {
    $data = \App\Models\BloodPack::withCount([
      'bloodStocks as stock_count'
    ])->get();

    $result = $data->map(function ($item) {
      $stockCount = $item->stock_count;
      $isDanger = $stockCount <= $item->danger_quantity;
      $isWarning = !$isDanger && $stockCount <= $item->warning_quantity;

      return [
        'blood_pack_id' => $item->id,
        'label' => $item->label,
        'stock_count' => $stockCount,
        'is_danger' => $isDanger,
        'is_warning' => $isWarning,
      ];
    });

    return [
      'stock_count' => $result->sum('stock_count'),
      'stock_danger_count' => $result->where('is_danger', true)->count(),
      'stock_warning_count' => $result->where('is_warning', true)->count(),
      'is_danger' => $result->contains('is_danger', true),
      'is_warning' => !$result->contains('is_danger', true)
        && $result->contains('is_warning', true),
      'data' => $result->values(),
    ];
  }

  // ---------- Fungsi untuk mengambil data log berdasarkan id ----------
  public function getDataLogById(string $id)
  {
    $cacheKey = self::CACHE_BLOOD_STOCK_LOG_KEY . ":{$id}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {
      $bloodPackId = BloodPack::where('public_id', $id)->value('id');
      $bloodStockPublicIds = BloodStock::withTrashed()->where('blood_pack_id', $bloodPackId)->pluck('public_id');
      $bloodStockLog = BloodStockLogActivity::whereIn('blood_stock_public_id', $bloodStockPublicIds)
        ->orderBy('timestamp', 'desc')
        ->limit(50)
        ->get();

      return $bloodStockLog;
    });
  }

  // ---------- Fungsi untuk export data ke Excel :begin ----------
  public function exportToExcel(Request $request)
  {
    $fileName = 'Blood Stock - ' . now()->format('Ymd') . '.xlsx';
    $storagePath = 'blood_stock/excel_file/' . $fileName;

    $query = BloodStock::withTrashed()
      ->select([
        'id',
        'public_id',
        'bag_number',
        'blood_pack_id',
        'blood_volume',
        'aftap_date',
        'process_date',
        'expiry_date',
        'blood_status',
        'is_hiv',
        'is_hcv',
        'is_hbsag',
        'is_syphilis',
        'used_at',
        'created_at',
      ])
      ->with(['bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component']);

    $bloodStocks = $query->orderBy('created_at')->get();

    // ---------- Simpan ke storage (timpa jika sudah ada) ----------
    Excel::store(new BloodStockExport($bloodStocks), $storagePath, 'public');

    // ---------- Download langsung ----------
    return Excel::download(new BloodStockExport($bloodStocks), $fileName);
  }
  // ---------- Fungsi untuk export data ke Excel :end ----------

  // ---------- Clear Cache ----------
  public function clearBloodStockCache(string $id)
  {
    Cache::forget(self::CACHE_BLOOD_STOCK_LOG_KEY . ":{$id}");
  }

  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data ----------
  protected function applyDateFilter(Builder $query, Request $request)
  {
    $start = $request->start_date;
    $end = $request->end_date;

    if ($start && $end) {
      try {
        $startDate = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();
        $table = $query->getModel()->getTable();

        if (Schema::hasColumn($table, 'created_at')) {
          $query->whereBetween('created_at', [$startDate, $endDate]);
        }
      } catch (\Exception $e) {
        logger()->error('Date filter error: ' . $e->getMessage());
      }
    }
  }
}
