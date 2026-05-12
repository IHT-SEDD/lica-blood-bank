<?php

namespace App\Services\Inventory\BloodStock;

use App\Enums\BloodStockLogActivityStatus;
use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodStockLogActivity;
use App\Models\OrderBlood;
use App\Models\StorageRack;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BloodStockWriteService
{
 // ---------- Edit data blood stock ----------
 public function editBloodStockData(Request $request, string $id)
 {
  DB::beginTransaction();
  try {
   $user = Auth::user();

   $stock = BloodStock::where('public_id', $id)->first();
   if (!$stock) {
    DB::rollBack();
    return response()->json(['message' => 'Data not found!'], 404);
   }

   $storageRackId = StorageRack::where('public_id', $request->storage_rack_id)->value('id');
   if (!$storageRackId) {
    DB::rollBack();
    return response()->json(['message' => 'Data storage rack not found!'], 404);
   }

   // ---------- Update ----------
   $stock->update([
    'blood_volume' => $request->volume,
    'storage_rack_id' => $storageRackId,
    'is_expired' => $request->boolean('is_expired'),
   ]);
   $stock->refresh();

   BloodStockLogActivity::create([
    'blood_stock_public_id' => $stock->public_id,
    'payload' => json_encode($stock->toArray()),
    'status' => BloodStockLogActivityStatus::BLOOD_STOCK_UPDATED,
    'description' => generateBloodStockLogDescription(
     BloodStockLogActivityStatus::BLOOD_STOCK_UPDATED,
     $stock->bag_number,
     $user->id
    ),
    'created_by_user_name' => $user->name,
    'timestamp' => now(),
   ]);

   DB::commit();

   globalLogger('info', 'Blood stock updated successfully!', [
    'data' => $stock,
    'updated_by' => $user->id,
   ], 200, 'editbloodstock');
   return response()->json([
    'message' => 'Blood stock updated successfully!',
    'data' => $stock,
   ]);
  } catch (\Throwable $e) {
   DB::rollBack();

   globalLogger('error', 'Blood stock failed to update!', [
    'payload' => $request->all(),
    'error' => $e->getMessage(),
    'updated_by' => Auth::id(),
   ], 500, 'editbloodstock');
   return response()->json([
    'message' => 'Blood stock failed to update!',
    'error' => $e->getMessage(),
   ], 500);
  }
 }
}
