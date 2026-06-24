<?php

namespace App\Services\Playground;

use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionDetailTest;
use Illuminate\Http\JsonResponse;

class DataService
{
 // ---------- Fungsi Mengambil Data Blood Transfusion ----------
 public function getDataTransfusionViaNumber(string $key, string $value): JsonResponse
 {
  $allowedKeys = ['lab_number', 'order_number'];
  if (!in_array($key, $allowedKeys, true)) {
   return response()->json([
    'success' => false,
    'message' => 'Key tidak valid.',
    'data' => null,
   ], 422);
  }

  $transfusion = BloodTransfusion::withoutTrashed()
   ->with(['patient', 'insurance', 'doctor', 'room', 'checkinBy'])
   ->where($key, $value)
   ->first();
  if (!$transfusion) {
   return response()->json([
    'success' => false,
    'message' => 'Data tidak ditemukan.',
    'data' => null,
   ], 404);
  }

  $data = [
   // Data Pasien
   'patient_name' => $transfusion->patient?->name,
   'gender' => $transfusion->patient?->gender,
   'email' => $transfusion->patient?->email,
   'blood_group' => $transfusion->patient?->blood_group,
   'rhesus' => $transfusion->patient?->blood_rhesus,
   'address' => $transfusion->patient?->address,
   'data_patient' => $transfusion->patient,

   // Data Order
   'insurance' => $transfusion->insurance?->name,
   'room' => $transfusion->room?->name,
   'type' => $transfusion->room?->type,
   'doctor' => $transfusion->doctor?->name,
   'diagnosis' => $transfusion->diagnosis,
   'created_at' => $transfusion->created_at,
   'checkin_by' => $transfusion->checkinBy->name,
   'bdrs_number' => $transfusion->lab_number,
   'order_number' => $transfusion->order_number,
   'public_id' => $transfusion->public_id,
  ];

  return response()->json([
   'success' => true,
   'message' => 'Data ditemukan.',
   'data' => $data,
  ]);
 }

 // ---------- Fungsi Mengambil Data Crossmatch ----------
 public function getDataCrossmatch(string $publicID): JsonResponse
 {
  $btDetail = BloodTransfusionDetail::withoutTrashed()->where('public_id', $publicID)->first();
  $data = BloodTransfusionDetailTest::withoutTrashed()->with('test')->where('bt_detail_id', $btDetail->id)->get();
  return response()->json([
   'success' => true,
   'message' => 'Data ditemukan.',
   'data' => $data,
  ]);
 }
}
