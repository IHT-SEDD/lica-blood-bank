<?php

namespace App\Services\Inventory;

use App\Enums\BloodPackStatus;
use App\Enums\IncomingBloodStatus;
use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodPack;
use App\Models\IncomingBlood;
use App\Models\IncomingBloodLogActivity;
use App\Enums\IncomingBloodLogActivityStatus;
use App\Models\BloodStock;
use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\OrderLogActivity;
use App\Models\Vendor;
use Faker\Core\Blood;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToArray;

class BloodStockService
{
  // ---------- Fungsi untuk menampilkan data ke tabel :begin ----------
  public function bloodStockTable(Request $request)
  {
    $query = BloodStock::withTrashed()
      ->join('blood_packs', 'blood_stocks.blood_pack_id', '=', 'blood_packs.id')
      ->selectRaw('
            blood_packs.blood_group,
            blood_packs.blood_rhesus,
            blood_packs.blood_component,
            blood_packs.warning_quantity,
            blood_packs.danger_quantity,
            MAX(blood_stocks.updated_at) as updated_at,
            COUNT(blood_stocks.id) as total_blood_data
        ')
      ->groupBy(
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
