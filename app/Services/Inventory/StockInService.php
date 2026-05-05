<?php

namespace App\Services\Inventory;

use App\Enums\IncomingBloodStatus;
use App\Models\BloodPack;
use App\Models\IncomingBlood;
use App\Models\IncomingBloodLogActivity;
use App\Enums\IncomingBloodLogActivityStatus;
use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodStock;
use App\Models\IncomingBloodDetail;
use App\Models\OrderBlood;
use App\Models\OrderLogActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StockInService
{
  // ---------- Fungsi untuk menampilkan data ke tabel :begin ----------
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
          $q->withTrashed()->select('public_id', 'incoming_blood_id');
        }
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
  // ---------- Fungsi untuk menampilkan data ke tabel :end ----------

  // ---------- Fungsi untuk mengambil data berdasarkan id :begin ----------
  public function getData(string $id)
  {
    $dataStockIn = IncomingBlood::withTrashed()->where('public_id', $id)->first();

    if (!$dataStockIn) {
      return response()->json(['message' => 'Data not found'], 404);
    }

    return $dataStockIn;
  }
  // ---------- Fungsi untuk mengambil data berdasarkan id :end ----------

  // ---------- Fungsi untuk delete data :begin ----------
  public function deleteDataStockIn(string $id)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      $user = Auth::user();
      $incomingBlood = IncomingBlood::where('public_id', $id)->first();

      if (!$incomingBlood) {
        return response()->json(['message' => 'Data incoming blood not found'], 404);
      }

      $bloodStocks = BloodStock::where('incoming_blood_id', $incomingBlood->id)->get();

      if ($bloodStocks->isEmpty()) {
        return response()->json(['message' => 'Data blood pack not found'], 404);
      }

      $incomingData = $incomingBlood->toArray();
      $bloodStockData = $bloodStocks->toArray();

      $bloodStocks->each->delete();
      $incomingBlood->delete();

      // ---------- Insert Incoming Blood Log Activity ----------
      IncomingBloodLogActivity::create([
        'incoming_blood_public_id' => $incomingBlood->public_id,
        'po_number' => $incomingBlood->po_number,
        'batch_number' => $incomingBlood->batch_number,
        'incoming_data' => $incomingData,
        'blood_stock_data' => $bloodStockData,
        'status' => IncomingBloodLogActivityStatus::INCOMING_DELETED,
        'created_by_user_name' => $user->name,
        'description' => generateIncomingLogDescription(
          IncomingBloodLogActivityStatus::INCOMING_DELETED,
          $incomingBlood->po_number,
          $user->id
        ),
        'deleted_at' => now(),
      ]);

      DB::commit();

      globalLogger('info', "Data stock in deleted successfully!", [
        'id' => $incomingBlood->id,
        'deleted_by' => Auth::user()->id,
      ], 200, 'newincomingblooddelete');

      return response()->json([
        'message' => "Data stock in deleted successfully!",
        'data' => $incomingBlood
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      globalLogger('error', "Data stock in failed to delete!", [
        'data' => $id,
        'deleted_by' => Auth::user()->id,
        'error' => $e->getMessage(),
      ], 500, 'newincomingblooddelete');

      return response()->json(['message' => "Data stock in failed to delete!"], 500);
    }
  }
  // ---------- Fungsi untuk delete data :end ----------

  // ---------- Fungsi untuk restore data :begin ----------
  public function restoreDataStockIn(string $id)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      $user = Auth::user();
      $incomingBlood = IncomingBlood::onlyTrashed()->where('public_id', $id)->first();

      if (!$incomingBlood) {
        return response()->json(['message' => 'Data incoming blood not found in the trash'], 404);
      }

      $bloodStocks = BloodPack::onlyTrashed()->where('incoming_blood_id', $incomingBlood->id)->get();

      if ($bloodStocks->isEmpty()) {
        return response()->json(['message' => 'Data blood pack not found'], 404);
      }

      $incomingData = $incomingBlood->toArray();
      $bloodStockData = $bloodStocks->toArray();

      $bloodStocks->each->restore();
      $incomingBlood->restore();

      // ---------- Insert Incoming Blood Log Activity ----------
      IncomingBloodLogActivity::create([
        'incoming_blood_public_id' => $incomingBlood->public_id,
        'po_number' => $incomingBlood->po_number,
        'batch_number' => $incomingBlood->batch_number,
        'incoming_data' => $incomingData,
        'blood_stock_data' => $bloodStockData,
        'status' => IncomingBloodLogActivityStatus::INCOMING_RESTORED,
        'created_by_user_name' => $user->name,
        'description' => generateIncomingLogDescription(
          IncomingBloodLogActivityStatus::INCOMING_RESTORED,
          $incomingBlood->po_number,
          $user->id
        ),
      ]);

      DB::commit();

      globalLogger('info', "Data stock in restored successfully!", [
        'id' => $incomingBlood->id,
        'restored_by' => Auth::user()->id,
      ], 200, 'newincomingbloodrestore');

      return response()->json([
        'message' => "Data stock in restored successfully!",
        'data' => $incomingBlood
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      globalLogger('error', "Data stock in failed to restore!", [
        'data' => $id,
        'error' => $e->getMessage(),
        'restored_by' => Auth::user()->id,
      ], 500, 'newincomingbloodrestore');

      return response()->json(['message' => "Data stock in failed to restore!"], 500);
    }
  }
  // ---------- Fungsi untuk delete data :begin ----------

  // ---------- Fungsi untuk membuat po number :begin ----------
  public function generateBagNumber(string $bloodGroup, string $bloodComponent): string
  {
    return retry(5, function () use ($bloodGroup, $bloodComponent) {
      return DB::transaction(function () use ($bloodGroup, $bloodComponent) {
        $year = now()->format('Y');
        $random = rand(100, 999);
        $prefix = "BP{$bloodGroup}{$random}{$bloodComponent}{$year}";

        $last = BloodStock::where('bag_number_lica', 'like', "{$prefix}%")
          ->lockForUpdate()
          ->orderByDesc('bag_number_lica')
          ->first();

        $nextNumber = $last
          ? ((int) substr($last->bag_number_lica, -4) + 1)
          : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
      });
    }, 100);
  }
  // ---------- Fungsi untuk membuat po number :end ----------

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
