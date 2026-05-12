<?php

namespace App\Services\Inventory\DestroyBlood;

use App\Enums\BloodDestroyStatus;
use App\Enums\BloodStockLogActivityStatus;
use App\Enums\BloodStockStatus;
use App\Models\BloodDestroy;
use App\Models\BloodStock;
use App\Models\BloodStockLogActivity;
use App\Models\StorageRackBlood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DestroyBloodWriteService
{
    // ---------- Fungsi undestroy data ----------
    public function undestroyData(string $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $destroyBlood = BloodDestroy::withoutTrashed()->where('public_id', $id)->where('status', BloodDestroyStatus::DESTROYED)->first();
            if (!$destroyBlood) {
                DB::rollBack();
                return response()->json(['message' => 'Data blood not found!'], 404);
            }

            $bloodStock = BloodStock::withoutTrashed()->where('id', $destroyBlood->blood_stock_id)->where('blood_status', BloodStockStatus::DESTROYED)->first();
            if (!$bloodStock) {
                DB::rollBack();
                return response()->json(['message' => 'Data blood stock not found!'], 404);
            }

            $rackStorageBlood = StorageRackBlood::where('blood_stock_id', $bloodStock->id)->first();

            // ---------- Delete ----------
            $bloodStock->update([
                'blood_status' => BloodStockStatus::AVAILABLE,
                'deleted_at' => NULL
            ]);
            if ($rackStorageBlood && $rackStorageBlood->deleted_at !== NULL) {
                $rackStorageBlood->restore();
            }
            $destroyBlood->forceDelete();

            BloodStockLogActivity::create([
                'blood_stock_public_id' => $bloodStock->public_id,
                'payload' => json_encode($bloodStock->toArray()),
                'status' => BloodStockLogActivityStatus::BLOOD_STOCK_UNDESTROYED,
                'description' => generateBloodStockLogDescription(
                    BloodStockLogActivityStatus::BLOOD_STOCK_UNDESTROYED,
                    $bloodStock->bag_number,
                    $user->id
                ),
                'created_by_user_name' => $user->name,
                'timestamp' => now(),
            ]);

            DB::commit();

            globalLogger('info', 'Blood stock undestroyed successfully!', [
                'data' => $bloodStock,
                'deleted_by' => $user->id,
            ], 200, 'destroybloodstock');
            globalLogger('info', 'Destroy blood undestroyed successfully!', [
                'data' => $destroyBlood,
                'deleted_by' => $user->id,
            ], 200, 'deleteblooddestroy');
            return response()->json([
                'message' => 'Destroy blood permanent deleted successfully!',
                'data' => $destroyBlood,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            globalLogger('error', 'Blood stock failed to undestroy!', [
                'destroy_blood_id' => $id,
                'error' => $e->getMessage(),
                'deleted_by' => Auth::id(),
            ], 500, 'destroybloodstock');
            globalLogger('error', 'Destroy blood failed to undestroy!', [
                'destroy_blood_id' => $id,
                'error' => $e->getMessage(),
                'deleted_by' => Auth::id(),
            ], 500, 'deleteblooddestroy');
            return response()->json([
                'message' => 'Destroy blood failed to undestroy!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Fungsi delete data ----------
    public function deleteData(string $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $destroyBlood = BloodDestroy::withoutTrashed()->where('public_id', $id)->first();
            if (!$destroyBlood) {
                DB::rollBack();
                return response()->json(['message' => 'Data blood not found!'], 404);
            }

            // ---------- Delete ----------
            $destroyBlood->update([
                'status' => BloodDestroyStatus::DELETED
            ]);
            $destroyBlood->delete();

            DB::commit();

            globalLogger('info', 'Destroy blood deleted successfully!', [
                'data' => $destroyBlood,
                'deleted_by' => $user->id,
            ], 200, 'deleteblooddestroy');
            return response()->json([
                'message' => 'Destroy blood deleted successfully!',
                'data' => $destroyBlood,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            globalLogger('error', 'Destroy blood failed to delete!', [
                'destroy_blood_id' => $id,
                'error' => $e->getMessage(),
                'deleted_by' => Auth::id(),
            ], 500, 'deleteblooddestroy');
            return response()->json([
                'message' => 'Destroy blood failed to delete!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Fungsi permanent delete data ----------
    public function permanentDeleteData(string $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $destroyBlood = BloodDestroy::onlyTrashed()->where('public_id', $id)->where('status', 'deleted')->whereNotNull('deleted_at')->first();
            if (!$destroyBlood) {
                DB::rollBack();
                return response()->json(['message' => 'Data blood not found!'], 404);
            }

            $bloodStock = BloodStock::onlyTrashed()->where('id', $destroyBlood->blood_stock_id)->whereNotNull('deleted_at')->first();
            if (!$bloodStock) {
                DB::rollBack();
                return response()->json(['message' => 'Data blood stock not found!'], 404);
            }

            $rackStorageBlood = StorageRackBlood::where('blood_stock_id', $bloodStock->id)->first();

            // ---------- Delete ----------
            $destroyBlood->forceDelete();
            $bloodStock->forceDelete();
            if ($rackStorageBlood) {
                $rackStorageBlood->forceDelete();
            }

            BloodStockLogActivity::create([
                'blood_stock_public_id' => $bloodStock->public_id,
                'payload' => json_encode($bloodStock->toArray()),
                'status' => BloodStockLogActivityStatus::BLOOD_STOCK_PERMANENT_DELETED,
                'description' => generateBloodStockLogDescription(
                    BloodStockLogActivityStatus::BLOOD_STOCK_PERMANENT_DELETED,
                    $bloodStock->bag_number,
                    $user->id
                ),
                'created_by_user_name' => $user->name,
                'timestamp' => now(),
            ]);

            DB::commit();

            globalLogger('info', 'Blood stock permanent deleted successfully!', [
                'data' => $bloodStock,
                'deleted_by' => $user->id,
            ], 200, 'deletebloodstock');
            globalLogger('info', 'Destroy blood permanent deleted successfully!', [
                'data' => $destroyBlood,
                'deleted_by' => $user->id,
            ], 200, 'deleteblooddestroy');
            return response()->json([
                'message' => 'Destroy blood permanent deleted successfully!',
                'data' => $destroyBlood,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            globalLogger('error', 'Blood stock failed to permanent delete!', [
                'destroy_blood_id' => $id,
                'error' => $e->getMessage(),
                'deleted_by' => Auth::id(),
            ], 500, 'deletebloodstock');
            globalLogger('error', 'Destroy blood failed to permanent delete!', [
                'destroy_blood_id' => $id,
                'error' => $e->getMessage(),
                'deleted_by' => Auth::id(),
            ], 500, 'deleteblooddestroy');
            return response()->json([
                'message' => 'Destroy blood failed to permanent delete!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Fungsi restore data ----------
    public function restoreData(string $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $destroyBlood = BloodDestroy::onlyTrashed()->where('public_id', $id)->first();
            if (!$destroyBlood) {
                DB::rollBack();
                return response()->json(['message' => 'Data blood not found!'], 404);
            }

            // ---------- Restore ----------
            $destroyBlood->update([
                'status' => BloodDestroyStatus::DESTROYED
            ]);
            $destroyBlood->restore();

            DB::commit();

            globalLogger('info', 'Destroy blood restored successfully!', [
                'data' => $destroyBlood,
                'restored_by' => $user->id,
            ], 200, 'restoreblooddestroy');
            return response()->json([
                'message' => 'Destroy blood restored successfully!',
                'data' => $destroyBlood,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            globalLogger('error', 'Destroy blood failed to restore!', [
                'destroy_blood_id' => $id,
                'error' => $e->getMessage(),
                'restored_by' => Auth::id(),
            ], 500, 'restoreblooddestroy');
            return response()->json([
                'message' => 'Destroy blood failed to restore!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
