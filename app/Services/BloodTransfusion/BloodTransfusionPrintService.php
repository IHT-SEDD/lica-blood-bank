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

class BloodTransfusionPrintService
{
 protected array $printMap = [
  'incompatible-letter' => 'pdf.blood_transfusion.incompatible-letter',
  'crossmatch-result' => 'pdf.blood_transfusion.crossmatch-result',
  'blood-patient-card' => 'pdf.blood_transfusion.blood_card_patient',
 ];

 // ---------- Fungsi Print Incompatible Letter ----------
 public function incompatibleLetter(string $transfusionPublicID, string $print): \Symfony\Component\HttpFoundation\BinaryFileResponse
 {
  if (! array_key_exists($print, $this->printMap)) {
   abort(404, "Print template [{$print}] not found.");
  }

  $printData = BloodTransfusion::withoutTrashed()->where('public_id', $transfusionPublicID)
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
    'details:id,public_id,blood_transfusion_id,blood_stock_id,component',
    'details.bloodStock:id,public_id,bag_number',
    'details.bloodTransfusionDetailTests:id,public_id,bt_detail_id,test_id,result',
    'details.bloodTransfusionDetailTests.test:id,public_id,name'
   ])
   ->firstOrFail();

  $printBy = Auth::user()->name;
  // dd($printData->toArray());

  $fileName = strtoupper($print) . '_' . $printData->lab_number . '.pdf';
  $storagePath = 'blood-transfusion/prints/' . $fileName;

  $pdf = Pdf::loadView($this->printMap[$print], [
   'data' => $printData,
   'printBy' => $printBy,
  ]);
  Storage::disk('public')->put($storagePath, $pdf->output());

  $absolutePath = Storage::disk('public')->path($storagePath);

  return response()->download($absolutePath, $fileName, [
   'Content-Type' => 'application/pdf',
   'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
  ]);
 }
}
