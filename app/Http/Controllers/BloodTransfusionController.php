<?php

namespace App\Http\Controllers;

use App\Enums\BloodComponent;
use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\Patient;
use App\Models\Package;
use App\Models\BloodTransfusionDetailTest;
use App\Enums\BloodStockStatus;
use App\Enums\BloodSComponent;
use App\Enums\BloodTransfusionStatus;
use App\Http\Requests\BloodTransfusion\StoreBloodTransfusionRequest;
use App\Http\Requests\BloodTransfusion\UpdateBloodTransfusionRequest;
use App\Http\Requests\BloodTransfusion\UpdateBloodPacksRequest;
use App\Services\BloodTransfusion\BloodTransfusionAddService;
use App\Services\BloodTransfusion\BloodTransfusionDataService;
use App\Services\BloodTransfusion\BloodTransfusionDetailTestService;
use App\Services\BloodTransfusion\BloodTransfusionPrintService;
use App\Services\BloodTransfusion\BloodTransfusionWriteService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BloodTransfusionController extends Controller
{

    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected BloodTransfusionDataService $dataService,
        protected BloodTransfusionAddService $addService,
        protected BloodTransfusionDetailTestService $bloodTransfusionDetailTestService,
        protected BloodTransfusionPrintService $printService,
        protected BloodTransfusionWriteService $writeService,
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Halaman index ----------
    public function index()
    {
        return view('pages.blood-transfusion.index');
    }
    public function arhiveIndex()
    {
        return view('pages.blood-transfusion.archive-index');
    }

    // ---------- Datatable Blood Pack ----------
    public function datatableBloodPack(Request $request)
    {
        return response()->json($this->dataService->bloodPackTable($request));
    }

    // ---------- Datatable Blood Request ----------
    public function datatableBloodRequest(Request $request)
    {
        return $this->dataService->bloodRequestTable($request);
    }
    // ---------- Datatable Blood Request Archive ----------
    public function datatableBloodRequestArchive(Request $request)
    {
        return $this->dataService->bloodRequestTableArchive($request);
    }

    // ---------- List Bag Request Datatable ----------
    public function datatableListBagRequest(Request $request, string $id)
    {
        return $this->dataService->listBagRequestTable($request, $id);
    }
    // ---------- List Archive Bag Request Datatable ----------
    public function datatableBagRequestArchive(Request $request)
    {
        return $this->dataService->listArchiveBagRequestTable($request);
    }

    // ---------- List History Test Datatable ----------
    public function datatableListHistoryTest(Request $request, string $patientId)
    {
        return $this->dataService->listHistoryTestTable($request, $patientId);
    }

    // ---------- Datatable List Test ----------
    public function datatableListTest(Request $request, string $id)
    {
        return $this->dataService->listTestTable($request, $id);
    }
    // ---------- Datatable Archive Test ----------
    public function datatableTestArchive(Request $request)
    {
        return $this->dataService->listArchiveTable($request);
    }

    // ---------- Store Blood Request ----------
    public function store(StoreBloodTransfusionRequest $request)
    {
        $result = $this->addService->newRequest($request);
        return response()->json($result['data'], $result['code']);
    }

    // ---------- Get Data By Id ----------
    public function getDataById(string $public_id)
    {
        return $this->dataService->getDataById($public_id);
    }

    // ---------- Update Blood Request ----------
    public function update(UpdateBloodTransfusionRequest $request, string $id)
    {
        $result = $this->writeService->updateData($request, $id);
        return response()->json($result['data'], $result['code']);
    }

    // ---------- Delete Blood Request ----------
    public function deleteBloodTransfusion(string $id)
    {
        return $this->writeService->deleteBloodTransfusionData($id);
    }

    // ---------- Check In Blood Request ----------
    public function checkin(string $id)
    {
        try {
            $labNumber = $this->writeService->checkinTransaction($id);
            return response()->json([
                'message' => 'Successfully checked in with Lab Number: ' . $labNumber,
                'lab_number' => $labNumber,
            ], 200);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to check in blood request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Update Bag Number (Blood Stock ID) ----------
    public function updateBagNumber(Request $request, string $detailPublicId)
    {
        // $request->validate([
        //     'blood_stock_id' => 'required|exists:blood_stocks,id'
        // ]);

        try {
            $detail = BloodTransfusionDetail::where('public_id', $detailPublicId)->firstOrFail();
            // dd($request->all());
            // Optional: You could update the previous and new BloodStock statuses here if needed.

            $bloodStockOld = BloodStock::find($detail->blood_stock_id);
            // dd($bloodStockOld);
            if ($bloodStockOld) {
                $bloodStockOld->update([
                    'blood_status' => BloodStockStatus::AVAILABLE,
                    'used_at'     => null,
                ]);
            }

            // Currently only updating the detail record.
            $detail->update([
                'blood_stock_id' => $request->blood_stock_id
            ]);

            if ($request->blood_stock_id) {
                // Update status Blood Stock to IN_USE
                $bloodStock = BloodStock::find($request->blood_stock_id);
                $bloodStock->update([
                    'blood_status' => BloodStockStatus::IN_USE,
                    'used_at'     => now(),
                ]);
            }

            return response()->json([
                'message' => 'Bag number successfully updated.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update bag number.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Update Blood Packs (Edit Bag Request List) ----------
    public function updateBloodPacks(UpdateBloodPacksRequest $request, string $id)
    {
        $result = $this->writeService->updateBloodPacks($request, $id);
        return response()->json($result['data'], $result['code']);
    }

    // ---------- Update Result Test ----------
    public function updateTestResult(Request $request, string $id)
    {
        // $request->validate([
        //     'result' => 'required|string',
        // ]);
        // dd($request->all());

        $outcome = $this->bloodTransfusionDetailTestService->updateResult($id, $request->result);

        $status = $outcome['success'] ? 200 : 422;

        return response()->json([
            'message' => $outcome['message'],
        ], $status);
    }

    public function updateTestVerifiedValidated(Request $request, string $id)
    {
        $request->validate([
            'field' => 'required|in:verified,validated',
            'value' => 'required|boolean',
        ]);

        try {

            $result = $this->bloodTransfusionDetailTestService->updateVerifiedValidated($id, $request->field, $request->value);

            $status = $result['success'] ? 200 : 422;

            return response()->json([
                'message' => $result['message'],
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update ' . $request->field . '.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Complete Test (Done) ----------
    public function completeTest(string $detailPublicId)
    {
        try {
            $result = $this->bloodTransfusionDetailTestService->completeTest($detailPublicId);

            $status = $result['success'] ? 200 : 422;
            return response()->json([
                'message' => $result['message'],
                'transfusion_result' => $result['transfusion_result'] ?? null,
            ], $status);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyelesaikan crossmatch/pemeriksaan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Complete Transaction ----------
    public function completeTransaction(string $id)
    {
        try {
            $this->writeService->completeTransaction($id);
            return response()->json(['message' => 'Blood Request Completed Successfully']);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to complete blood request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Archive Transaction ----------
    public function archiveBloodTransfusion(string $id)
    {
        try {
            $this->writeService->archiveTransaction($id);
            return response()->json(['message' => 'Permintaan darah berhasil diarsipkan']);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengarsipkan permintaan darah',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Hold Blood Pack ----------
    public function holdBloodPack(string $detailPublicId)
    {
        try {
            $this->writeService->holdBloodPack($detailPublicId);

            return response()->json(['message' => 'Blood pack has been held.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['message' => 'Detail not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to hold blood pack.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Release Blood Pack ----------
    public function releaseBloodPack(Request $request, string $detailPublicId)
    {
        $request->validate([
            'blood_received_by' => 'required|string|max:255',
            'blood_number' => 'required|string|max:255',
        ]);

        try {
            $this->writeService->releaseBloodPack($request, $detailPublicId);
            return response()->json(['message' => 'Darah berhasil dikeluarkan.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['message' => 'Detail not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Darah gagal dikeluarkan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Release All Blood Pack ----------
    public function releaseAllBloodPack(Request $request, string $transfusionPublicID)
    {
        $request->validate([
            'blood_received_by' => 'required|string|max:255',
            'blood_numbers' => 'required|array|min:1',
            'blood_numbers.*' => 'required|string|max:255',
        ]);

        try {
            $bloodTransfusionId = BloodTransfusion::where('public_id', $transfusionPublicID)->value('id');
            $details = BloodTransfusionDetail::with(['bloodTransfusion'])
                ->where('blood_transfusion_id', $bloodTransfusionId)
                ->where('blood_release_status', false)
                ->get();
            foreach ($details as $detail) {
                $matchedNumber = collect($request->blood_numbers)
                    ->first(
                        fn($num) =>
                        strtolower(trim($num)) === strtolower(trim($detail->bloodStock?->bag_number ?? ''))
                    );
                if (!$matchedNumber) {
                    throw new \RuntimeException(
                        "Nomor darah untuk labu {$detail->bloodStock?->bag_number} tidak ditemukan!"
                    );
                }

                $perDetailRequest = new \Illuminate\Http\Request();
                $perDetailRequest->replace([
                    'blood_received_by' => $request->blood_received_by,
                    'blood_number' => $matchedNumber,
                ]);

                $this->writeService->releaseBloodPack($perDetailRequest, $detail->public_id);
            }
            return response()->json([
                'message' => 'Semua labu darah berhasil dikeluarkan',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['message' => 'Detail not found.'], 404);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Semua labu darah gagal dikeluarkan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Delete Blood Pack ----------
    public function deleteBloodPack(string $detailPublicId)
    {
        try {
            $this->writeService->deleteBloodPack($detailPublicId);
            return response()->json(['message' => 'Darah berhasil dihapus']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['message' => 'Detail not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus darah',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Approve Incompatible ----------
    public function acceptIncompatible(string $detailPublicId)
    {
        try {
            $this->writeService->approveIncompatible($detailPublicId);
            return response()->json(['message' => 'Incompatible Blood Has Been Approved.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['message' => 'Detail not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to approve incompatible blood.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Unrelease Blood Pack ----------
    public function unreleaseBloodPack(string $detailPublicId)
    {
        try {
            $this->writeService->unReleaseBloodPack($detailPublicId);
            return response()->json(['message' => 'Blood has been un-released.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['message' => 'Detail not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to unrelease blood.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Print Incompatible Letter ----------
    public function printIncompatibleLetter(string $transfusionPublicID)
    {
        try {
            $print = 'incompatible-letter';
            return $this->printService->incompatibleLetter($transfusionPublicID, $print);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'File not found!'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mencetak surat incompatible',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal mencetak file surat incompatible!', 'error' => $e->getMessage()], 500);
        }
    }

    // ---------- Print Nota ----------
    public function printNota(string $transfusionPublicID)
    {
        try {
            $print = 'nota';
            return $this->printService->nota($transfusionPublicID, $print);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'File not found!'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mencetak nota',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal mencetak file nota!', 'error' => $e->getMessage()], 500);
        }
    }

    // ---------- Print Crossmatch Result ----------
    public function printCrossmatchResult(string $transfusionPublicID, ?string $btDetailID = null)
    {
        try {
            $print = 'crossmatch-result';
            return $this->printService->crossmatchResult($transfusionPublicID, $btDetailID, $print);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'File not found!'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mencetak hasil crossmatch',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Gagal mencetak file hasil crossmatch!', 'error' => $e->getMessage()], 500);
        }
    }

    // ---------- Print Barcode Blood ----------
    public function printBarcodeBlood(string $id, ?string $btDetailID = null)
    {
        try {
            $data = $this->printService->barcodeBlood($id, $btDetailID);
            return response()->json([
                'message' => 'Successfully Print Barcode',
                'data' => $data,
            ], 200);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to print barcode.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Get Log Data ----------
    public function bloodTransfusionLogData(string $id)
    {
        try {
            $data = $this->dataService->getDataLogById($id);
            return response()->json($data);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data not found!'
            ], 404);
        }
    }
}
