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
use Illuminate\Support\Facades\Cache;
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

  // ---------- Fungsi Print Barcode ----------
  public function barcodeBlood(string $transfusionPublicID, ?string $btDetailID)
  {
    $transfusionData = BloodTransfusion::withoutTrashed()
      ->where('public_id', $transfusionPublicID)
      ->select([
        'id',
        'public_id',
        'patient_id',
        'room_id',
        'lab_number',
        'is_dct',
        'created_at',
      ])
      ->with([
        'patient:id,public_id,name,medrec,birthdate,blood_group,blood_rhesus',
        'room:id,public_id,name,class,type',
        'details' => function ($query) use ($btDetailID) {
          $query->select([
            'id',
            'public_id',
            'blood_transfusion_id',
            'blood_pack_id',
            'blood_stock_id',
            'component',
            'crossmatch_result',
          ]);
          if ($btDetailID) {
            $query->where('public_id', $btDetailID);
          }
        },
        'details.bloodStock:id,public_id,bag_number,blood_volume,aftap_date,expiry_date,process_date'
      ])
      ->firstOrFail();

    $lock = Cache::lock('print_barcode', 10);
    if (!$lock->get()) {
      throw new \RuntimeException(
        'System is currently processing another request, please try again in a moment.'
      );
    }

    try {
      return [
        'patient_name' => $transfusionData->patient->name,
        'patient_gender' => $transfusionData->patient->gender,
        'patient_medrec' => $transfusionData->patient->medrec,
        'patient_birthdate' => optional($transfusionData->patient->birthdate)?->format('Y-m-d'),
        'room_name' => $transfusionData->room->name,
        'room_class' => $transfusionData->room->class,
        'blood_details' => $transfusionData->details
          ->map(function ($detail) use ($transfusionData) {
            return [
              'bag_number' => $detail->bloodStock->bag_number,
              'blood_volume' => $detail->bloodStock->blood_volume,
              'blood_group' => $transfusionData->patient->blood_group,
              'blood_rhesus' => $transfusionData->patient->blood_rhesus,
              'component' => $detail->component,
              'aftap_date' => $detail->bloodStock->aftap_date,
              'process_date' => $detail->bloodStock->process_date,
              'expiry_date' => $detail->bloodStock->expiry_date,
              'crossmatch_result' => $detail->crossmatch_result,
            ];
          })
          ->values(),
      ];
    } finally {
      $lock->release();
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
