<?php

namespace App\Services\Inventory;

use App\Models\BloodPack;
use App\Models\BloodStock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class BloodStockService
{
  // ---------- Fungsi untuk menampilkan data ke tabel :begin ----------
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

    return $query->paginate($request->get('per_page', 10));
  }
  // ---------- Fungsi untuk menampilkan data ke tabel :end ----------

  // ---------- Fungsi untuk mendapatkan status label :begin ----------
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
  // ---------- Fungsi untuk mendapatkan status label :end ----------

  // ---------- Fungsi untuk menampilkan data ke tabel stock blood detail :begin ----------
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
        'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component'
      ])->where('blood_pack_id', $bloodPackId);

    $this->applyDateFilter($query, $request);

    if ($request->filled('sort_by')) {
      $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
    }

    return $query->paginate($request->filled('per_page', 10));
  }
  // ---------- Fungsi untuk menampilkan data ke tabel stock blood detail :end ----------

  // ---------- Fungsi untuk mengambil data stock blood by id :begin ----------
  public function getDataDetailStockBlood(string $id)
  {
    $stockBloodData = BloodStock::withTrashed()->with(['incomingBloodDetails', 'bloodPacks'])->where('public_id', $id)->first();

    return $stockBloodData;
  }
  // ---------- Fungsi untuk mengambil data stock blood by id :end ----------

  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data :begin ----------
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
  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data :end ----------
}
