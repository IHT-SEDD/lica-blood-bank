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

    // ---------- Datatable Blood Pack ----------
    public function datatableBloodPack(Request $request)
    {
        return response()->json($this->dataService->bloodPackTable($request));
    }

    // ---------- Datatable Blood Request ----------
    public function datatableBloodRequest(Request $request)
    {
        return response()->json($this->dataService->bloodRequestTable($request));
    }

    // ---------- List Bag Request Datatable ----------
    public function datatableListBagRequest(Request $request, string $id)
    {
        return response()->json(
            $this->dataService->listBagRequestTable($request, $id)
        );
    }

    // ---------- Datatable List Test ----------
    public function datatableListTest(Request $request, string $id)
    {
        return response()->json(
            $this->dataService->listTestTable($request, $id)
        );
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
        $data = BloodTransfusion::with(['patient', 'insurance', 'room', 'doctor'])->where('public_id', $public_id)->first();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'                       => $data->public_id,
                'insurance_public_id'     => $data->insurance->public_id,
                'room_public_id'          => $data->room->public_id,
                'doctor_public_id'        => $data->doctor->public_id,
                'relation_name'           => $data->relation_name,
                'relation_type'           => $data->relation_type,
                'blood_request_at'        => $data->blood_request_at,
                'diagnosis'               => $data->diagnosis,
                'is_dct'               => $data->is_dct,
                'patient_public_id'       => $data->patient->public_id,
                'patient_name'            => $data->patient->name,
                'patient_blood_group'     => $data->patient->blood_group,
                'patient_blood_rhesus'    => $data->patient->blood_rhesus,
            ],
        ]);
    }

    // ---------- Update Blood Request ----------
    public function update(UpdateBloodTransfusionRequest $request, string $id)
    {
        $result = $this->writeService->updateData($request, $id);
        return response()->json($result['data'], $result['code']);
    }

    // ---------- Delete Blood Request ----------
    public function destroy(string $id)
    {
        try {
            $transfusion = BloodTransfusion::where('public_id', $id)->first();
            // Anda bisa tambahkan validasi status di sini (misal hanya status REGISTERED yang boleh dihapus)

            $transfusion->delete(); // Soft delete

            return response()->json([
                'message' => 'Blood request successfully deleted.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete blood request.',
                'error'   => $e->getMessage(),
            ], 500);
        }
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
        // dd($request->all());
        try {
            $transfusion = BloodTransfusion::with(['patient'])->where('public_id', $id)->firstOrFail();
            DB::transaction(function () use ($transfusion, $request) {

                $oldDetails = BloodTransfusionDetail::with('bloodTransfusionDetailTests') // Sesuaikan nama relasi di model Anda
                    ->where('blood_transfusion_id', $transfusion->id)
                    ->get();

                $oldBloodTransfusionDetail = [];
                $oldResults = [];
                foreach ($oldDetails as $oldDetail) {
                    $keyDetail = $oldDetail->component . '-' . $oldDetail->public_id;
                    $oldBloodTransfusionDetail[$keyDetail] = [
                        'blood_transfusion_id' => $oldDetail->blood_transfusion_id,
                        'component'            => $oldDetail->component,
                        'blood_stock_id'       => $oldDetail->blood_stock_id,
                        'blood_pack_id'       => $oldDetail->blood_pack_id,
                        'crossmatch_result'       => $oldDetail->crossmatch_result,
                    ];
                    foreach ($oldDetail->bloodTransfusionDetailTests as $oldTest) {
                        // Simpan dengan key unik: "nama_komponen-id_test"
                        $key = $oldDetail->public_id . '-' . $oldTest->test_id;
                        $oldResults[$key] = [
                            'result' => $oldTest->result,
                            'result_by_user_id' => $oldTest->result_by_user_id,
                        ];
                    }
                }

                // Soft-delete all existing details for this transfusion
                BloodTransfusionDetail::where('blood_transfusion_id', $transfusion->id)
                    ->forceDelete();

                // Create new details based on the submitted selection
                $selected_blood_components = $request->input('blood_packs');
                // dd($selected_blood_components);

                $package = Package::with(['package_tests'])->where('is_active', 1)->first();

                foreach ($selected_blood_components as $component) {

                    $componentExists = BloodComponent::getById($component['component_id']);

                    if (!$componentExists) continue;

                    // Merge old data component if exists, otherwise create new
                    $keyComponent = $component['component_id'] . '-' . $component['public_id'];

                    $existingBloodTransfusionDetail = isset($oldBloodTransfusionDetail[$keyComponent]) ? $oldBloodTransfusionDetail[$keyComponent] : null;

                    // public_id di-generate otomatis oleh model booted()
                    $transfusionDetail = BloodTransfusionDetail::create([
                        'blood_transfusion_id' => $transfusion->id,
                        'component'            => $existingBloodTransfusionDetail ? $existingBloodTransfusionDetail['component'] : $component['component_id'],
                        'blood_stock_id'       => $existingBloodTransfusionDetail ? $existingBloodTransfusionDetail['blood_stock_id'] : null,
                        'blood_pack_id'       => $existingBloodTransfusionDetail ? $existingBloodTransfusionDetail['blood_pack_id'] : null,
                        'crossmatch_result'   => $existingBloodTransfusionDetail ? $existingBloodTransfusionDetail['crossmatch_result'] : null,
                    ]);

                    $patient = $transfusion->patient;
                    // Check Blood Stock bedasarkan Blood Group dan Rhesus Pasien
                    if (!is_null($patient->blood_group) && !is_null($patient->blood_rhesus) && is_null($transfusionDetail->blood_pack_id)) {

                        $bloodPack = BloodPack::where('blood_group', $patient->blood_group)
                            ->where('blood_rhesus', $patient->blood_rhesus)
                            ->where('blood_component', $component['component_id'])
                            ->first();

                        $availableStock = BloodStock::where('blood_pack_id', $bloodPack?->id)
                            ->where('blood_status', BloodStockStatus::AVAILABLE)
                            ->where('expiry_date', '>=', $transfusion->blood_request_at)
                            ->orderBy('expiry_date', 'asc')
                            ->first();

                        $transfusionDetail->update([
                            'blood_pack_id' => $bloodPack?->id,
                            'blood_stock_id' => $availableStock?->id
                        ]);

                        if ($availableStock) {
                            $availableStock->update([
                                'blood_status' => BloodStockStatus::IN_USE,
                                'used_at'     => now(),
                            ]);
                        }
                    }

                    foreach ($package->package_tests as $key => $test) {

                        $lookupKey = $component['public_id'] . '-' . $test->test_id;

                        // Merge old data test if exists, otherwise create new
                        $existingResult = isset($oldResults[$lookupKey]) ? $oldResults[$lookupKey] : null;

                        BloodTransfusionDetailTest::create([
                            'bt_detail_id' => $transfusionDetail->id,
                            'test_id'      => $test->test_id,
                            'type'         => 'package',
                            'result'       => $existingResult ? $existingResult['result'] : null,
                            'result_by_user_id' => $existingResult ? $existingResult['result_by_user_id'] : null,
                        ]);
                    }
                }
            });

            return response()->json([
                'message' => 'Blood components updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update blood packs.',
                'error'   => $e->getMessage(),
            ], 500);
        }
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
                'message' => 'Failed to complete test.',
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
    public function releaseBloodPack(string $detailPublicId)
    {
        try {
            $this->writeService->releaseBloodPack($detailPublicId);
            return response()->json(['message' => 'Blood pack has been released.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['message' => 'Detail not found.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to release blood pack.',
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
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to print incompatible letter file!'], 500);
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
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to print crossmatch result file!'], 500);
        }
    }

    // ---------- Get Log Data ----------
    public function bloodTransfusionLogData(string $id)
    {
        try {
            $data = $this->dataService->getDataLogById($id);
            return response()->json($data)
                ->setEtag(md5(json_encode($data)))
                ->header('Cache-Control', 'public, max-age=600');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data not found!'
            ], 404);
        }
    }
}
