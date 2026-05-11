<?php

namespace App\Services\Inventory\StockIn;

use App\Models\BloodPack;
use App\Models\IncomingBlood;
use App\Models\OrderBlood;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class StockInDataService
{
  // ---------- Fungsi untuk menampilkan data ke tabel ----------
  public function stockInTable(Request $request)
  {
    $query = IncomingBlood::withTrashed()
      ->select([
        'id',
        'public_id',
        'order_blood_id',
        'po_number',
        'batch_number',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
      ])
      ->with([
        'users',
        'orderBloods:id,public_id,vendor_id,po_number',
        'orderBloods.vendors:id,public_id,name',
        'incomingBloodDetails' => function ($q) {
          $q->withTrashed()->select('public_id', 'incoming_blood_id', 'blood_pack_id');
        },
        'incomingBloodDetails.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component'
      ])
      ->withCount([
        'incomingBloodDetails as total_blood_data' => function ($q) {
          $q->withTrashed();
        }
      ]);

    $this->applyDateFilter($query, $request);

    if ($request->filled('vendor')) {
      $query->whereHas('orderBloods.vendors', function ($q) use ($request) {
        $q->where('public_id', $request->vendor);
      });
    }

    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('po_number', 'like', "%{$search}%")
          ->orWhereHas('orderBloods.vendors', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
          });
      });
    }

    if ($request->filled('sort_by')) {
      $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
    } else {
      $query->latest();
    }

    return $query->paginate($request->get('per_page', 10));
  }

  // ---------- Fungsi untuk mengambil data berdasarkan id :begin ----------
  public function getData(string $id)
  {
    $dataStockIn = IncomingBlood::withTrashed()->where('public_id', $id)->first();

    if (!$dataStockIn) {
      return response()->json(['message' => 'Data not found'], 404);
    }

    return $dataStockIn;
  }

  // ---------- Fungsi select blood pack pada form add ----------
  public function selectBloodPack(Request $request, string $poNumber): array
  {
    $search = $request->filled('q', '');

    // ---------- Ambil data dari model ----------
    $order = OrderBlood::withoutTrashed()
      ->select([
        'id',
        'public_id',
        'po_number',
      ])
      ->with([
        'orderBloodDetails:id,order_blood_id,blood_pack_id',
      ])
      ->where('po_number', $poNumber)
      ->firstOrFail();

    $bloodPackIds = $order->orderBloodDetails
      ->pluck('blood_pack_id')
      ->filter()
      ->unique()
      ->values();

    $query = BloodPack::query()
      ->select([
        'id',
        'public_id',
        'blood_group',
        'blood_rhesus',
        'blood_component',
      ])
      ->whereIn('id', $bloodPackIds);

    // ---------- Handle search field ----------
    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('blood_group', 'like', "%{$search}%")
          ->orWhere('blood_rhesus', 'like', "%{$search}%")
          ->orWhere('blood_component', 'like', "%{$search}%");
      });
    }

    $data = $query
      ->limit(100)
      ->get();


    return [
      'results' => $data->map(function ($item) {
        return [
          'id' => $item->public_id ?? $item->id,
          'text' => collect([
            $item->blood_group->value,
            $item->blood_rhesus,
            $item->blood_component->value,
          ])->filter()->join(' '),
        ];
      })->values(),
    ];
  }

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
      ->whereIn('status', ['order_created', 'some_order_stock_registered']);

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
}
