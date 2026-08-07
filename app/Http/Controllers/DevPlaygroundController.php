<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playground\EditCrossmatchResult;
use App\Services\Playground\DataService;
use App\Services\Playground\DatatableService;
use App\Services\Playground\PrintTestService;
use App\Services\Playground\WriteService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DevPlaygroundController extends Controller
{
    public function __construct(
        protected PrintTestService $printTestService,
        protected DatatableService $datatableService,
        protected DataService $dataService,
        protected WriteService $writeService,
    ) {}

    // Index halaman utama
    public function index()
    {
        return view('pages.playground.index');
    }

    // ---------- [INDEX] UJI COBA SECTION ----------
    // Index halaman uji coba print
    public function printTestIndex()
    {
        return view('pages.playground.print-test.index');
    }

    // ---------- [INDEX] PERBAIKAN / PENYESUAIAN SECTION ----------
    // Index halaman perbaikan hasil crossmatch
    public function fixCrossmatchResult()
    {
        return view('pages.playground.crossmatch-result.index');
    }
    // Index halaman penyesuaian data blood stock
    public function fixBloodStockData()
    {
        return view('pages.playground.blood-stock-data.index');
    }

    // ---------- [INDEX] SETTING / PENGATURAN SECTION ----------
    // ---------- Config
    // Index halaman pengaturan blood-component config
    public function settingConfigBloodComponentIndex()
    {
        return view('pages.playground.config.blood-component.index');
    }

    // ==============================================================================================
    // ============================== BATAS PEMISAH INDEX DENGAN LOGIC ==============================
    // ==============================================================================================

    // ---------- [LOGIC] UJI COBA SECTION ----------
    // Preview print hasil
    public function printPreview(string $print)
    {
        try {
            return $this->printTestService->print($print);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'File not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to print preview File!', 'error' => $e->getMessage()], 500);
        }
    }
    // Download PDF Hasil
    public function downloadPDF(string $print)
    {
        try {
            return $this->printTestService->downloadPDF($print);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'File not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to download File!', 'error' => $e->getMessage()], 500);
        }
    }

    // ---------- [LOGIC] PERBAIKAN / PENYESUAIAN SECTION ----------
    // Datatable pemeriksaan untuk perbaikan hasil crossmatch
    public function testDatatable(Request $request)
    {
        return $this->datatableService->listTestsTable($request, $request->input('transfusion_public_id'));
    }
    // Query data transfusi untuk perbaikan hasil crossmatch
    public function dataTransfusion(Request $request): JsonResponse
    {
        $bdrsNumber = $request->input('bdrs_number');
        $orderNumber = $request->input('order_number');

        if (empty($bdrsNumber) && empty($orderNumber)) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter bdrs_number atau order_number wajib diisi.',
                'data' => null,
            ], 422);
        }

        if (!empty($bdrsNumber)) {
            return $this->dataService->getDataTransfusionViaNumber('lab_number', $bdrsNumber);
        }

        return $this->dataService->getDataTransfusionViaNumber('order_number', $orderNumber);
    }
    // Query data crossmatch untuk perbaikan hasil crossmatch
    public function dataCrossmatch(Request $request, string $publicID): JsonResponse
    {
        if (empty($publicID)) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter public_id wajib diisi.',
                'data' => null,
            ], 422);
        }

        return $this->dataService->getDataCrossmatch($publicID);
    }
    // Edit hasil crossmatch
    public function editCrossmatchResult(EditCrossmatchResult $request, string $id)
    {
        return $this->writeService->editCrossmatchResult($request, $id);
    }

    // ---------- [LOGIC] PENGATURAN / SETTING SECTION ----------
    // Datatable untuk list konfigurasi komponen darah
    public function settingConfigBloodComponentData(Request $request)
    {
        return $this->datatableService->listConfigBloodComponentTable($request);
    }
}
