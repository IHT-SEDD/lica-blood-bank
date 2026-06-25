<?php

namespace App\Services\Playground;

use App\Enums\BloodTransfusionLogActivityStatus;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionDetailTest;
use App\Models\BloodTransfusionLogActivity;
use App\Models\CrossmatchHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WriteService
{
 // ---------- Edit data crossmatch ----------
 public function editCrossmatchResult(Request $request, string $id)
 {
  DB::beginTransaction();
  try {
   $user = Auth::user();

   $btDetail = BloodTransfusionDetail::with(['bloodStock', 'bloodTransfusion'])->where('id', $id)->first();
   if (!$btDetail) {
    DB::rollBack();
    return response()->json(['message' => 'Data transfusi detail tidak ditemukan!'], 404);
   }

   $btDetailTests = BloodTransfusionDetailTest::with('test')->where('bt_detail_id', $btDetail->id)->get();
   if (!$btDetailTests) {
    DB::rollBack();
    return response()->json(['message' => 'Data transfusi detail test tidak ditemukan!'], 404);
   }

   $crossmatchHistory = CrossmatchHistory::where('blood_transfusion_detail_id', $btDetail->id)->where('blood_stock_id', $btDetail->blood_stock_id)->first();

   $resultMap = [
    'Mayor' => $request->input('mayor_result'),
    'Minor' => $request->input('minor_result'),
    'Auto Control' => $request->input('auto_control_result'),
   ];

   $updatedTests = collect();
   foreach ($btDetailTests as $detailTest) {
    $testName = $detailTest->test?->name;
    $newResult = $resultMap[$testName] ?? null;

    if (!array_key_exists($testName, $resultMap) || is_null($newResult)) {
     continue;
    }

    $detailTest->update([
     'result' => $newResult,
     'updated_at' => now(),
    ]);

    $updatedTests->push($detailTest->refresh());
   }

   $hasIncompatible = $updatedTests->contains(
    fn($t) => str_starts_with(strtolower(trim($t->result ?? '')), 'incompatible')
   );
   $hasCompatible = $updatedTests->contains(
    fn($t) => str_starts_with(strtolower(trim($t->result ?? '')), 'compatible')
   );

   if ($hasIncompatible) {
    $crossmatchResult = 'Incompatible';
   } elseif ($hasCompatible) {
    $crossmatchResult = 'Compatible';
   } else {
    DB::rollBack();
    return response()->json([
     'message' => 'Tidak ditemukan hasil Compatible atau Incompatible untuk menentukan hasil crossmatch.',
    ], 422);
   }

   $btDetail->update([
    'crossmatch_result' => $crossmatchResult,
    'updated_at' => now(),
   ]);
   if ($crossmatchHistory) {
    $crossmatchHistory->update([
     'result' => $crossmatchResult,
     'updated_at' => now(),
    ]);
   }

   $detailUpdated = implode(', ', [
    'Mayor=' . ($resultMap['Mayor'] ?? 'Tidak diubah'),
    'Minor=' . ($resultMap['Minor'] ?? 'Tidak diubah'),
    'Auto Control=' . ($resultMap['Auto Control'] ?? 'Tidak diubah'),
   ]);

   BloodTransfusionLogActivity::create([
    'blood_transfusion_public_id' => $btDetail->bloodTransfusion->public_id,
    'payload' => $updatedTests->toJson(),
    'status' => BloodTransfusionLogActivityStatus::CROSSMATCH_RESULT_UPDATED,
    'description' => generateBloodTransfusionLogDescription(
     BloodTransfusionLogActivityStatus::CROSSMATCH_RESULT_UPDATED,
     $this->generateDescription($btDetail->bloodTransfusion),
     $btDetail->bloodStock->bag_number,
     $user->username,
     $detailUpdated,
    ),
    'created_by_user_name' => $user->name,
    'timestamp' => now(),
   ]);

   DB::commit();

   globalLogger('info', 'Crossmatch result updated successfully!', [
    'data' => $updatedTests,
    'updated_by' => $user->id,
   ], 200, 'updatebloodtransfusion');
   return response()->json([
    'message' => 'Data crossmatch berhasil diperbaharui!',
    'data' => $updatedTests,
   ]);
  } catch (\Throwable $e) {
   DB::rollBack();

   globalLogger('error', 'Crossmatch result failed to update!', [
    'payload' => $request->all(),
    'error' => $e->getMessage(),
    'updated_by' => Auth::id(),
   ], 500, 'updatebloodtransfusion');
   return response()->json([
    'message' => 'Data crossmatch gagal diperbaharui!',
    'error' => $e->getMessage(),
   ], 500);
  }
 }

 // ---------- HELPERS ----------
 private function generateDescription(BloodTransfusion $transfusion): string
 {
  return match (true) {
   !empty($transfusion->order_number) => 'dengan no. order ' . $transfusion->order_number,
   !empty($transfusion->lab_number) => 'dengan no. lab ' . $transfusion->lab_number,
   default => 'dengan medrec pasien ' . $transfusion->patient->medrec,
  };
 }
}
