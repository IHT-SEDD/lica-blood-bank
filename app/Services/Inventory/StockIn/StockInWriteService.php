<?php

namespace App\Services\Inventory\StockIn;

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

class StockInWriteService
{
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

      $incomingBloodDetails = IncomingBloodDetail::where('incoming_blood_id', $incomingBlood->id)->get();
      if ($incomingBloodDetails->isEmpty()) {
        return response()->json(['message' => 'Data incoming blood detail not found'], 404);
      }

      $orderBlood = OrderBlood::withoutTrashed()->where('id', $incomingBlood->order_blood_id)->first();
      if (!$orderBlood) {
        return response()->json(['message' => 'Data order blood not found'], 404);
      }

      $incomingData = $incomingBlood->toArray();
      $incomingBloodDetailData = $incomingBloodDetails->toArray();

      $incomingBloodDetails->each->delete();
      $incomingBlood->delete();

      // ---------- Insert Incoming Blood Log Activity ----------
      IncomingBloodLogActivity::create([
        'incoming_blood_public_id' => $incomingBlood->public_id,
        'po_number' => $incomingBlood->po_number,
        'batch_number' => $incomingBlood->batch_number,
        'incoming_data' => $incomingData,
        'blood_data' => $incomingBloodDetailData,
        'status' => IncomingBloodLogActivityStatus::INCOMING_DELETED,
        'created_by_user_name' => $user->name,
        'description' => generateIncomingLogDescription(
          IncomingBloodLogActivityStatus::INCOMING_DELETED,
          $incomingBlood->po_number,
          $user->id
        ),
        'deleted_at' => now(),
      ]);

      // ---------- Update status order blood setelah delete ----------
      if ($orderBlood->status === OrderBloodStatus::ALL_ORDER_STOCK_REGISTERED) {
        $orderBlood->update([
          'status' => OrderBloodStatus::SOME_ORDER_STOCK_REGISTERED,
        ]);

        OrderLogActivity::create([
          'po_number' => $orderBlood->po_number,
          'vendor_name' => $orderBlood->vendors?->name,
          'payload' => $orderBlood->toArray(),
          'created_by_user_name' => $user->name,
          'status' => OrderLogActivityStatus::SOME_ORDER_STOCK_REGISTERED,
          'description' => generateOrderLogDescription(
            OrderLogActivityStatus::SOME_ORDER_STOCK_REGISTERED,
            $orderBlood->po_number,
            $user->id
          ),
          'timestamp' => now(),
        ]);
      }

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

      $incomingBloodDetails = IncomingBloodDetail::onlyTrashed()->where('incoming_blood_id', $incomingBlood->id)->get();
      if ($incomingBloodDetails->isEmpty()) {
        return response()->json(['message' => 'Data blood pack not found'], 404);
      }

      $orderBlood = OrderBlood::withoutTrashed()->where('id', $incomingBlood->order_blood_id)->first();
      if (!$orderBlood) {
        return response()->json(['message' => 'Data order blood not found'], 404);
      }

      $incomingData = $incomingBlood->toArray();
      $bloodStockData = $incomingBloodDetails->toArray();

      // ---------- Total detail aktif sebelum restore ----------
      $totalIncomingBloodDetailsBeforeRestore =
        IncomingBloodDetail::query()
        ->whereHas('incomingBloods', function ($q) use ($orderBlood, $incomingBlood) {
          $q->where('order_blood_id', $orderBlood->id)
            ->whereNull('deleted_at')
            ->whereNot('id', $incomingBlood->id);
        })
        ->count();

      // ---------- Total data yang akan direstore ----------
      $totalRestoreData = $incomingBloodDetails->count();

      // ---------- Total detail setelah restore ----------
      $totalIncomingBloodDetailsAfterRestore =
        $totalIncomingBloodDetailsBeforeRestore + $totalRestoreData;

      // ---------- Total quantity order ----------
      $totalQuantityOrder = (int) $orderBlood->total_quantity;

      // ---------- Validasi jika melebihi quantity order ----------
      if ($totalIncomingBloodDetailsAfterRestore > $totalQuantityOrder) {

        DB::rollBack();

        globalLogger('info', "Restore failed because total blood data exceeds order quantity", [
          'id' => $incomingBlood->id,
          'restored_by' => Auth::user()->id,
          'details' => [
            'total_before_restore' => $totalIncomingBloodDetailsBeforeRestore,
            'total_restore_data' => $totalRestoreData,
            'total_after_restore' => $totalIncomingBloodDetailsAfterRestore,
            'total_quantity_order' => $totalQuantityOrder,
          ],
        ], 200, 'newincomingbloodrestore');
        return response()->json([
          'message' => 'Restore failed because total blood data exceeds order quantity',
          'details' => [
            'total_before_restore' => $totalIncomingBloodDetailsBeforeRestore,
            'total_restore_data' => $totalRestoreData,
            'total_after_restore' => $totalIncomingBloodDetailsAfterRestore,
            'total_quantity_order' => $totalQuantityOrder,
          ]
        ], 422);
      }

      $incomingBloodDetails->each->restore();
      $incomingBlood->restore();

      // ---------- Update status order blood setelah restore ----------
      if ($totalIncomingBloodDetailsAfterRestore === $totalQuantityOrder) {
        if ($orderBlood->status === OrderBloodStatus::SOME_ORDER_STOCK_REGISTERED) {
          // ---------- Update status ----------
          $orderBlood->update([
            'status' => OrderBloodStatus::ALL_ORDER_STOCK_REGISTERED,
          ]);

          // ---------- Insert order log ----------
          OrderLogActivity::create([
            'po_number' => $orderBlood->po_number,
            'vendor_name' => $orderBlood->vendors?->name,
            'payload' => $orderBlood->fresh()->toArray(),
            'created_by_user_name' => $user->name,
            'status' => OrderLogActivityStatus::ALL_ORDER_STOCK_REGISTERED,
            'description' => generateOrderLogDescription(
              OrderLogActivityStatus::ALL_ORDER_STOCK_REGISTERED,
              $orderBlood->po_number,
              $user->id
            ),
            'timestamp' => now(),
          ]);
        }
      }

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
}
