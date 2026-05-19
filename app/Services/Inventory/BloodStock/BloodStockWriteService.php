<?php

namespace App\Services\Inventory\BloodStock;

use App\Enums\BloodStockLogActivityStatus;
use App\Enums\BloodStockStatus;
use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodStockLogActivity;
use App\Models\OrderBlood;
use App\Models\StorageRack;
use App\Models\StorageRackBlood;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

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

   StorageRackBlood::create([
    'storage_rack_id' => $storageRackId,
    'blood_stock_id' => $stock->id,
   ]);

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

 // ---------- Delete data blood stock ----------
 public function deleteBloodStockData(string $id)
 {
  DB::beginTransaction();
  try {
   $user = Auth::user();

   $deleteableStatus = ['available', 'expired', 'destroyed'];

   $stock = BloodStock::where('public_id', $id)->whereIn('blood_status', $deleteableStatus)->first();
   if (!$stock) {
    DB::rollBack();
    return response()->json(['message' => 'Data blood stock not found!'], 404);
   }

   $storageRackBlood = StorageRackBlood::where('blood_stock_id', $stock->id)->first();
   if ($storageRackBlood) {
    $storageRackBlood->delete();
   }

   // ---------- Delete ----------
   $stock->update([
    'blood_status' => BloodStockStatus::DELETED
   ]);
   $stock->delete();

   BloodStockLogActivity::create([
    'blood_stock_public_id' => $stock->public_id,
    'payload' => json_encode($stock->toArray()),
    'status' => BloodStockLogActivityStatus::BLOOD_STOCK_DELETED,
    'description' => generateBloodStockLogDescription(
     BloodStockLogActivityStatus::BLOOD_STOCK_DELETED,
     $stock->bag_number,
     $user->id
    ),
    'created_by_user_name' => $user->name,
    'timestamp' => now(),
   ]);

   DB::commit();

   globalLogger('info', 'Blood stock deleted successfully!', [
    'data' => $stock,
    'deleted_by' => $user->id,
   ], 200, 'deletebloodstock');
   return response()->json([
    'message' => 'Blood stock deleted successfully!',
    'data' => $stock,
   ]);
  } catch (\Throwable $e) {
   DB::rollBack();

   globalLogger('error', 'Blood stock failed to delete!', [
    'blood_stock_id' => $id,
    'error' => $e->getMessage(),
    'deleted_by' => Auth::id(),
   ], 500, 'deletebloodstock');
   return response()->json([
    'message' => 'Blood stock failed to delete!',
    'error' => $e->getMessage(),
   ], 500);
  }
 }

 // ---------- Permanent delete data blood stock ----------
 public function permanentDeleteBloodStockData(string $id)
 {
  DB::beginTransaction();
  try {
   $user = Auth::user();

   $stock = BloodStock::onlyTrashed()->where('public_id', $id)->where('blood_status', 'deleted')->whereNotNull('deleted_at')->first();
   if (!$stock) {
    DB::rollBack();
    return response()->json(['message' => 'Data blood stock not found!'], 404);
   }

   $storageRackBlood = StorageRackBlood::onlyTrashed()->where('blood_stock_id', $stock->id)->first();
   if (!$storageRackBlood) {
    DB::rollBack();
    return response()->json(['message' => 'Data storage rack blood not found!'], 404);
   }

   // ---------- Delete ----------
   $stock->forceDelete();
   $storageRackBlood->forceDelete();

   BloodStockLogActivity::create([
    'blood_stock_public_id' => $stock->public_id,
    'payload' => json_encode($stock->toArray()),
    'status' => BloodStockLogActivityStatus::BLOOD_STOCK_PERMANENT_DELETED,
    'description' => generateBloodStockLogDescription(
     BloodStockLogActivityStatus::BLOOD_STOCK_PERMANENT_DELETED,
     $stock->bag_number,
     $user->id
    ),
    'created_by_user_name' => $user->name,
    'timestamp' => now(),
   ]);

   DB::commit();

   globalLogger('info', 'Blood stock permanent deleted successfully!', [
    'data' => $stock,
    'deleted_by' => $user->id,
   ], 200, 'deletebloodstock');
   return response()->json([
    'message' => 'Blood stock permanent deleted successfully!',
    'data' => $stock,
   ]);
  } catch (\Throwable $e) {
   DB::rollBack();

   globalLogger('error', 'Blood stock failed to permanent delete!', [
    'blood_stock_id' => $id,
    'error' => $e->getMessage(),
    'deleted_by' => Auth::id(),
   ], 500, 'deletebloodstock');
   return response()->json([
    'message' => 'Blood stock failed to permanent delete!',
    'error' => $e->getMessage(),
   ], 500);
  }
 }

 // ---------- Restore data blood stock ----------
 public function restoreBloodStockData(string $id)
 {
  DB::beginTransaction();
  try {
   $user = Auth::user();

   $stock = BloodStock::onlyTrashed()->where('public_id', $id)->where('blood_status', BloodStockStatus::DELETED)->whereNotNull('deleted_at')->first();
   if (!$stock) {
    DB::rollBack();
    return response()->json(['message' => 'Data blood stock not found!'], 404);
   }

   $storageRackBlood = StorageRackBlood::where('blood_stock_id', $stock->id)->first();
   if ($storageRackBlood) {
    $storageRackBlood->restore();
   }

   // ---------- Restore ----------
   $stock->update([
    'blood_status' => BloodStockStatus::AVAILABLE
   ]);
   $stock->restore();

   BloodStockLogActivity::create([
    'blood_stock_public_id' => $stock->public_id,
    'payload' => json_encode($stock->toArray()),
    'status' => BloodStockLogActivityStatus::BLOOD_STOCK_RESTORED,
    'description' => generateBloodStockLogDescription(
     BloodStockLogActivityStatus::BLOOD_STOCK_RESTORED,
     $stock->bag_number,
     $user->id
    ),
    'created_by_user_name' => $user->name,
    'timestamp' => now(),
   ]);

   DB::commit();

   globalLogger('info', 'Blood stock restored successfully!', [
    'data' => $stock,
    'restored_by' => $user->id,
   ], 200, 'restorebloodstock');
   return response()->json([
    'message' => 'Blood stock restored successfully!',
    'data' => $stock,
   ]);
  } catch (\Throwable $e) {
   DB::rollBack();

   globalLogger('error', 'Blood stock failed to restore!', [
    'blood_stock_id' => $id,
    'error' => $e->getMessage(),
    'restored_by' => Auth::id(),
   ], 500, 'restorebloodstock');
   return response()->json([
    'message' => 'Blood stock failed to restore!',
    'error' => $e->getMessage(),
   ], 500);
  }
 }

 // ---------- Fungsi untuk print barcode lica ----------
 public function printBarcodeLicaBloodStock(string $id)
 {
  $data = BloodStock::withTrashed()
   ->where('public_id', $id)
   ->with([
    'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
    'storageRacks:id,public_id,name',
   ])
   ->firstOrFail();

  $this->resolveBarcodeLica($data);
  $data->refresh();

  $relativePath = $data->barcode_bag_lica_path . '/' . $data->barcode_bag_lica_filename;
  $barcodeUrl = asset('storage/' . $relativePath);

  return response()->json([
   'message' => 'Barcode ready',
   'data' => [
    'barcode_url' => $barcodeUrl,
    'bag_number_lica' => $data->bag_number_lica,
    'bag_number' => $data->bag_number,
    'blood_group' => $data->bloodPacks?->blood_group,
    'blood_rhesus' => $data->bloodPacks?->blood_rhesus,
    'blood_component' => $data->bloodPacks?->blood_component,
   ],
  ]);
 }

 // ---------- Fungsi untuk download barcode lica ----------
 public function downloadBarcodeLicaBloodStock(string $id)
 {
  $data = BloodStock::withTrashed()
   ->where('public_id', $id)
   ->with([
    'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
    'storageRacks:id,public_id,name',
   ])
   ->firstOrFail();

  $absolutePath = $this->resolveBarcodeLica($data);

  $data->refresh();

  $filename = $data->barcode_bag_lica_filename;

  return response()->download(
   $absolutePath,
   $filename,
   ['Content-Type' => 'image/png']
  );
 }

 // ---------- Fungsi untuk generate & simpan barcode, return path ----------
 private function resolveBarcodeLica(BloodStock $data): string
 {
  if (
   $data->barcode_bag_lica_path &&
   $data->barcode_bag_lica_filename &&
   Storage::disk('public')->exists($data->barcode_bag_lica_path . '/' . $data->barcode_bag_lica_filename)
  ) {
   return Storage::disk('public')->path($data->barcode_bag_lica_path . '/' . $data->barcode_bag_lica_filename);
  }

  $generator = new BarcodeGeneratorPNG();
  $barcodeData = $generator->getBarcode(
   $data->bag_number_lica,
   $generator::TYPE_CODE_128,
   widthFactor: 2,
   height: 76,
  );

  $folder = 'barcode_blood_bag/lica';
  $filename = $data->bag_number_lica . '_barcode.png';

  Storage::disk('public')->put($folder . '/' . $filename, $barcodeData);

  BloodStock::withTrashed()
   ->where('id', $data->id)
   ->update([
    'barcode_bag_lica_path' => $folder,
    'barcode_bag_lica_filename' => $filename,
   ]);

  return Storage::disk('public')->path($folder . '/' . $filename);
 }
}
