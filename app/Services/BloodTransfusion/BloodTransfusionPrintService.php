<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodComponent;
use App\Enums\BloodStockStatus;
use App\Enums\ResultTest;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class BloodTransfusionPrintService
{
 protected array $printMap = [
  'incompatible-letter' => 'pdf.blood_transfusion.incompatible-letter',
  'crossmatch-result' => 'pdf.blood_transfusion.crossmatch-result',
  'blood-patient-card' => 'pdf.blood_transfusion.blood_card_patient',
 ];

 // ---------- Fungsi Print Incompatible Letter ----------
 public function incompatibleLetter(string $transfusionPublicID, string $print): BinaryFileResponse
 {
  try {
   DB::beginTransaction();

   $this->validatePrintTemplate($print);
   $printData = $this->queryTransfusionData($transfusionPublicID, null, 'Incompatible');

   BloodTransfusionDetail::query()->where('blood_transfusion_id', $printData->id)
    ->where('crossmatch_result', 'Incompatible')
    ->update([
     'is_print_incompatible_letter' => true,
    ]);
   $response = $this->generatePdfResponse($print, $printData);

   DB::commit();

   return $response;
  } catch (Throwable $th) {
   DB::rollBack();
   throw $th;
  }
 }

 // ---------- Fungsi Print Crossmatch Result ----------
 public function crossmatchResult(string $transfusionPublicID, ?string $btDetailID, string $print): BinaryFileResponse
 {
  try {
   DB::beginTransaction();

   $this->validatePrintTemplate($print);
   $printData = $this->queryTransfusionData($transfusionPublicID, $btDetailID);
   $response = $this->generatePdfResponse($print, $printData, $btDetailID);

   DB::commit();
   return $response;
  } catch (Throwable $th) {
   DB::rollBack();
   throw $th;
  }
 }

 // ---------- Helpers ----------
 private function queryTransfusionData(string $transfusionPublicID, ?string $btDetailID = null, ?string $crossmatchResult = null): BloodTransfusion
 {
  return BloodTransfusion::withoutTrashed()
   ->where('public_id', $transfusionPublicID)
   ->select([
    'id',
    'public_id',
    'patient_id',
    'insurance_id',
    'room_id',
    'doctor_id',
    'lab_number',
    'is_dct',
    'created_at',
   ])
   ->with([
    'doctor:id,public_id,name',
    'patient:id,public_id,name,medrec,gender,address,blood_group,blood_rhesus',
    'details' => function ($query) use ($btDetailID, $crossmatchResult) {
     $query->select([
      'id',
      'public_id',
      'blood_transfusion_id',
      'blood_stock_id',
      'component',
      'crossmatch_result',
      'is_print_incompatible_letter',
     ]);
     if ($btDetailID) {
      $query->where('public_id', $btDetailID);
     }
     if ($crossmatchResult) {
      $query->where('crossmatch_result', $crossmatchResult);
     }
    },
    'details.bloodStock:id,public_id,bag_number',
    'details.bloodTransfusionDetailTests:id,public_id,bt_detail_id,test_id,result',
    'details.bloodTransfusionDetailTests.test:id,public_id,name',
   ])
   ->firstOrFail();
 }
 private function validatePrintTemplate(string $print): void
 {
  if (!array_key_exists($print, $this->printMap)) {
   abort(404, "Print template [{$print}] not found.");
  }
 }
 private function generatePdfResponse(string $print, BloodTransfusion $printData, ?string $btDetailID = null): BinaryFileResponse
 {
  $printBy = Auth::user()->name;
  $fileName = strtoupper($print) . '_' . $printData->lab_number;

  // print per blood bag
  if ($btDetailID && $printData->details->isNotEmpty()) {
   $detail = $printData->details->first();
   $bagNumber = $detail?->bloodStock?->bag_number;
   if ($bagNumber) {
    $fileName .= '_' . $bagNumber;
   }
  }

  $fileName .= '.pdf';
  $storagePath = 'blood-transfusion/prints/' . $fileName;
  $pdf = Pdf::loadView($this->printMap[$print], ['data' => $printData, 'printBy' => $printBy]);

  Storage::disk('public')->put($storagePath, $pdf->output());
  $absolutePath = Storage::disk('public')->path($storagePath);

  return response()->download($absolutePath, $fileName, [
   'Content-Type' => 'application/pdf',
   'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
  ]);
 }
}
