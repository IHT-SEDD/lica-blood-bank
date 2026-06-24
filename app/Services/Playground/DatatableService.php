<?php

namespace App\Services\Playground;

use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class DatatableService
{
  // ---------- Fungsi Tabel List Tests ----------
  public function listTestsTable(Request $request, string $id): JsonResponse
  {
    $detailPublicId = $request->input('detail_id');

    $transfusion = BloodTransfusion::where('public_id', $id)
      ->whereNull('deleted_at')
      ->first();
    if (!$transfusion) {
      return DataTables::of([])->toJson();
    }

    $detailQuery = BloodTransfusionDetail::withoutTrashed()
      ->with([
        'bloodStock:id,bag_number',
        'bloodTransfusionDetailTests.test:id,name',
      ])
      ->where('blood_transfusion_id', $transfusion->id);
    if ($detailPublicId) {
      $detailQuery->where('public_id', $detailPublicId);
    }

    $rows = [];
    foreach ($detailQuery->get() as $detail) {
      $tests = $detail->bloodTransfusionDetailTests ?? collect();

      $row = [
        'detail_public_id' => $detail->public_id,
        'mayor_detail_test_public_id' => null,
        'minor_detail_test_public_id' => null,
        'auto_control_detail_test_public_id' => null,

        'bag_number' => $detail->bloodStock?->bag_number ?? '-',
        'component' => $detail->component,

        'mayor' => null,
        'minor' => null,
        'auto_control' => null,

        'bag_released' => $detail->blood_release_status,
        'crossmatch_result' => $detail->crossmatch_result,
      ];

      foreach ($tests as $detailTest) {
        $testName = strtolower(trim($detailTest->test?->name ?? ''));
        switch ($testName) {
          case 'mayor':
            $row['mayor'] = $detailTest->result;
            $row['mayor_detail_test_public_id'] = $detailTest->public_id;
            break;
          case 'minor':
            $row['minor'] = $detailTest->result;
            $row['minor_detail_test_public_id'] = $detailTest->public_id;
            break;
          case 'auto control':
            $row['auto_control'] = $detailTest->result;
            $row['auto_control_detail_test_public_id'] = $detailTest->public_id;
            break;
        }
      }
      $rows[] = $row;
    }
    return DataTables::of($rows)->toJson();
  }
}
