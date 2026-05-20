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
use App\Services\BloodTransfusion\BloodTransfusionDetailTestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BloodTransfusionController extends Controller
{

    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected BloodTransfusionDetailTestService $bloodTransfusionDetailTestService,
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
        $searchValue = trim($request->input('search.value', ''));
        $start = max((int) $request->input('start', 0), 0);
        $length = (int) $request->input('length', 10);
        $draw = (int) $request->input('draw', 1);

        $data = BloodComponent::toSelect();
        $recordsTotal = count(BloodComponent::toSelect());
        $recordsFiltered = count(BloodComponent::toSelect());

        // dd($data);
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    // ---------- Datatable Blood Request ----------
    public function datatableBloodRequest(Request $request)
    {
        $searchValue = trim($request->input('search.value', ''));
        $start = max((int) $request->input('start', 0), 0);
        $length = (int) $request->input('length', 10);
        $draw = (int) $request->input('draw', 1);
        $dateRange = $request->input('date_range');

        $query = BloodTransfusion::with(['patient', 'room', 'insurance', 'doctor'])
            ->whereNull('deleted_at');

        // Handle Date Range
        if (!empty($dateRange)) {
            $dates = explode(' to ', $dateRange);
            try {
                if (count($dates) === 2) {
                    $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
                    $query->whereBetween('blood_request_at', [$startDate, $endDate]);
                } elseif (count($dates) === 1) {
                    $date = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                    $query->whereDate('blood_request_at', $date);
                }
            } catch (\Exception $e) {
                // If parsing fails, do not apply date filter
            }
        } else {
            $query->whereDate('blood_request_at', \Carbon\Carbon::now()->format('Y-m-d'));
        }

        // Handle Search
        if ($searchValue !== '') {
            $query->where(function ($sub) use ($searchValue) {
                $sub->where('order_number', 'like', "{$searchValue}%")
                    ->orWhere('lab_number', 'like', "{$searchValue}%")
                    ->orWhereHas('patient', function ($q) use ($searchValue) {
                        $q->where('name', 'like', "{$searchValue}%")
                            ->orWhere('medrec', 'like', "{$searchValue}%");
                    })
                    ->orWhereHas('room', function ($q) use ($searchValue) {
                        $q->where('name', 'like', "{$searchValue}%");
                    });
            });
        }

        $recordsTotal = BloodTransfusion::whereNull('deleted_at')->count();
        $recordsFiltered = $query->count();

        // Handle Pagination
        if ($length !== -1) {
            $query->offset($start)->limit($length);
        }

        $data = $query->orderBy('blood_request_at', 'desc')->get();

        // Transform data
        $pageData = $data->map(function ($item) {
            return [
                'public_id' => $item->public_id,
                'blood_request_at' => $item->blood_request_at ? \Carbon\Carbon::parse($item->blood_request_at)->format('Y/m/d') : '-',
                'order_number' => $item->order_number ?? '-',
                'lab_number' => $item->lab_number ?? '-',
                'diagnosis' => $item->diagnosis ?? '-',
                'patient' => [
                    'medrec' => $item->patient->medrec ?? '-',
                    'name' => $item->patient->name ?? '-',
                    'gender' => $item->patient->gender === 'M' ? 'Male' : ($item->patient->gender === 'F' ? 'Female' : '-'),
                    'email' => $item->patient->email ?? '-',
                    'address' => $item->patient->address ?? '-',
                    'age' => $item->patient->birthdate ? \Carbon\Carbon::parse($item->patient->birthdate)->diff(\Carbon\Carbon::now())->format('%yY/%mM/%dD') : '-',
                    'blood_group' => $item->patient->blood_group ?? '-',
                    'blood_rhesus' => $item->patient->blood_rhesus ?? '-',
                ],
                'room' => [
                    'name' => $item->room->name ?? '-',
                    'type' => $item->room->type ? str_replace('_', ' ', str::kebab($item->room->type)) : '-'
                ],
                'insurance' => [
                    'name' => $item->insurance->name ?? '-',
                ],
                'doctor' => [
                    'name' => $item->doctor->name ?? '-',
                ],
                'is_cito' => false, // Placeholder since we don't have is_cito column yet
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $pageData,
        ]);
    }

    // ---------- Store Blood Request ----------
    public function store(StoreBloodTransfusionRequest $request)
    {
        // dd($request->all());
        // selectedPacks() adalah helper dari Form Request yang decode JSON
        $selected_blood_components = json_decode($request->selected_blood_components);

        if (empty($selected_blood_components)) {
            return response()->json([
                'message' => 'At least one blood pack must be selected.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // ---------- 1. Upsert / create patient ----------
            // public_id dan medrec di-generate otomatis oleh model booted()
            if (!empty($request->patient_id)) {
                $patient = Patient::findOrFail($request->patient_id);
                $patient->update([
                    'name'         => $request->name,
                    'gender'       => $request->gender,
                    'birthdate'    => $request->birthdate,
                    'email'        => $request->email,
                    'phone'        => $request->phone_number,
                    'blood_group'  => $request->blood_group,
                    'blood_rhesus' => $request->blood_rhesus,
                    'address'      => $request->address,
                ]);
            } else {
                $patient = Patient::create([
                    'name'         => $request->name,
                    'gender'       => $request->gender,
                    'birthdate'    => $request->birthdate,
                    'email'        => $request->email,
                    'phone'        => $request->phone_number,
                    'blood_group'  => $request->blood_group,
                    'blood_rhesus' => $request->blood_rhesus,
                    'address'      => $request->address,
                    'is_active'    => true,
                ]);
            }

            // ---------- 2. Buat blood transfusion ----------
            // public_id di-generate otomatis oleh model booted()
            $transfusion = BloodTransfusion::create([
                'patient_id'       => $patient->id,
                'insurance_id'     => $request->insurance_id,
                'room_id'          => $request->room_id,
                'doctor_id'        => $request->doctor_id,
                'relation_name'    => $request->relation_name,
                'relation_type'    => $request->relation_type,
                'blood_request_at' => $request->blood_required_at,
                'diagnosis'        => $request->diagnosis,
                'status'           => BloodTransfusionStatus::BLOOD_TRANSFUSION_REGISTERED,
                'blood_quantity'   => count($selected_blood_components),
            ]);

            // ---------- 3. Buat blood transfusion details (satu per blood pack) ----------
            // Cari blood_stock_id: blood_pack_id match + belum expired + status available
            // Dapatkan test yang active
            $package = Package::with(['package_tests'])->where('is_active', 1)->first();

            foreach ($selected_blood_components as $component) {

                // public_id di-generate otomatis oleh model booted()
                $transfusionDetail = BloodTransfusionDetail::create([
                    'blood_transfusion_id' => $transfusion->id,
                    'component'        => $component?->id,
                ]);

                // Check Blood Stock bedasarkan Blood Group dan Rhesus Pasien
                if(!is_null($patient->blood_group) && !is_null($patient->blood_rhesus)){
                    $bloodPack = BloodPack::where('blood_group', $patient->blood_group)
                                ->where('blood_rhesus', $patient->blood_rhesus)
                                ->where('blood_component', $component->id)
                                ->first();

                    $transfusionDetail->update([
                        'blood_pack_id' => $bloodPack?->id,
                    ]);
                }

                foreach ($package->package_tests as $key => $test) {
                    BloodTransfusionDetailTest::create([
                        'bt_detail_id' => $transfusionDetail->id,
                        'test_id'      => $test->test_id,
                        'type'         => 'package',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message'      => 'Blood request successfully created.',
                'public_id'    => $transfusion->public_id,
                'patient_name' => $patient->name,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create blood request.',
                'error'   => $e->getMessage(),
            ], 500);
        }
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
    public function update(UpdateBloodTransfusionRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $transfusion = BloodTransfusion::with(['patient', 'insurance', 'room', 'doctor', 'details'])->findOrFail($request->id);

            // Update transaksi
            $transfusion->update([
                'insurance_id'     => $request->insurance_id,
                'room_id'          => $request->room_id,
                'doctor_id'        => $request->doctor_id,
                'relation_name'    => $request->relation_name,
                'relation_type'    => $request->relation_type,
                'blood_request_at' => $request->blood_required_at,
                'is_dct'           => $request->is_dct,
                'diagnosis'        => $request->diagnosis,
            ]);

            // Update data pasien (blood_group & blood_rhesus)
            if ($transfusion->patient_id) {
                $transfusion->patient->update([
                    'blood_group'  => $request->blood_group,
                    'blood_rhesus' => $request->blood_rhesus,
                ]);

                if(!is_null($transfusion->patient->blood_group) && !is_null($transfusion->patient->blood_rhesus)){

                    foreach ($transfusion->details as $bloodTransfusionDetail) {
                        // dd($transfusion->patient->blood_group, $transfusion->patient->blood_rhesus, $bloodTransfusionDetail->component);
                        $bloodPack = BloodPack::where('blood_group', $transfusion->patient->blood_group)
                                ->where('blood_rhesus', $transfusion->patient->blood_rhesus)
                                ->where('blood_component', $bloodTransfusionDetail->component)
                                ->first();

                        $availableStock = BloodStock::where('blood_pack_id', $bloodPack?->id)
                                            ->where('blood_status', BloodStockStatus::AVAILABLE)
                                            ->where('expiry_date', '>=', $transfusion->blood_request_at)
                                            ->orderBy('expiry_date', 'asc')
                                            ->first();

                        $bloodTransfusionDetail->update([
                            'blood_stock_id' => $availableStock?->id,
                            'blood_pack_id' => $bloodPack?->id,
                        ]);

                        $availableStock->update([
                            'blood_status' => BloodStockStatus::IN_USE,
                        ]);
                    }
                }
            }

            DB::commit();

            $data = [
                'public_id' => $transfusion->public_id,
                'blood_request_at' => $transfusion->blood_request_at ? \Carbon\Carbon::parse($transfusion->blood_request_at)->format('Y/m/d') : '-',
                'order_number' => $transfusion->order_number ?? '-',
                'lab_number' => $transfusion->lab_number ?? '-',
                'diagnosis' => $transfusion->diagnosis ?? '-',
                'patient' => [
                    'medrec' => $transfusion->patient->medrec ?? '-',
                    'name' => $transfusion->patient->name ?? '-',
                    'gender' => $transfusion->patient->gender === 'M' ? 'Male' : ($transfusion->patient->gender === 'F' ? 'Female' : '-'),
                    'email' => $transfusion->patient->email ?? '-',
                    'address' => $transfusion->patient->address ?? '-',
                    'age' => $transfusion->patient->birthdate ? \Carbon\Carbon::parse($transfusion->patient->birthdate)->diff(\Carbon\Carbon::now())->format('%yY/%mM/%dD') : '-',
                    'blood_group' => $transfusion->patient->blood_group ?? '-',
                    'blood_rhesus' => $transfusion->patient->blood_rhesus ?? '-',
                ],
                'room' => [
                    'name' => $transfusion->room->name ?? '-',
                    'type' => $transfusion->room->type ? str_replace('_', ' ', str::kebab($transfusion->room->type)) : '-'
                ],
                'insurance' => [
                    'name' => $transfusion->insurance->name ?? '-',
                ],
                'doctor' => [
                    'name' => $transfusion->doctor->name ?? '-',
                ],
                'is_cito' => false, // Placeholder since we don't have is_cito column yet
            ];

            return response()->json([
                'message' => 'Blood request successfully updated.',
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update blood request.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Delete Blood Request ----------
    public function destroy($id)
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
    public function checkin($id)
    {
        try {
            $transfusion = BloodTransfusion::where('public_id', $id)->firstOrFail();

            if ($transfusion->lab_number) {
                return response()->json([
                    'message' => 'This request has already been checked in.',
                ], 400);
            }

            $datePrefix = now()->format('ymd');

            // Use Cache lock to prevent race condition
            $lock = Cache::lock('generate_lab_number', 10);

            if ($lock->get()) {
                try {
                    $latestLabNumber = BloodTransfusion::where('lab_number', 'like', $datePrefix . '%')
                        ->orderBy('lab_number', 'desc')
                        ->value('lab_number');

                    if ($latestLabNumber) {
                        $sequence = (int) substr($latestLabNumber, -3);
                        $nextSequence = $sequence + 1;
                    } else {
                        $nextSequence = 1;
                    }

                    $labNumber = $datePrefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

                    $transfusion->update([
                        'lab_number' => $labNumber,
                        'status' => BloodTransfusionStatus::BLOOD_TRANSFUSION_CHECKED_IN ?? $transfusion->status, // Use appropriate status if you have it
                        'checkin_by_user_id' => Auth::id(),
                    ]);
                } finally {
                    $lock->release();
                }
            } else {
                return response()->json([
                    'message' => 'System is currently processing another request, please try again in a moment.',
                ], 429);
            }

            return response()->json([
                'message' => 'Successfully checked in with Lab Number: ' . $labNumber,
                'lab_number' => $labNumber,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to check in blood request.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- List Bag Request Datatable ----------
    public function datatableListBagRequest(Request $request, $id)
    {
        $draw = (int) $request->input('draw', 1);
        // dd(1111);
        $transfusion = BloodTransfusion::where('public_id', $id)->first();

        if (!$transfusion) {
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        $details = BloodTransfusionDetail::with('bloodPack')
            ->where('blood_transfusion_id', $transfusion->id)
            ->get();

        $pageData = $details->map(function ($detail) use ($transfusion) {
            // Find available blood stocks
            $availableStocks = BloodStock::where('blood_pack_id', $detail->blood_pack_id)
                ->where('blood_status', BloodStockStatus::AVAILABLE->value)
                ->where('expiry_date', '>', $transfusion->blood_request_at)
                ->get();

            // If a stock is already selected, make sure it's included in the list even if it's no longer AVAILABLE (e.g., IN_USE)
            $selectedStock = null;
            if ($detail->blood_stock_id) {
                $selectedStock = BloodStock::find($detail->blood_stock_id);
                if ($selectedStock && !$availableStocks->contains('id', $selectedStock->id)) {
                    $availableStocks->push($selectedStock);
                }
            }

            $hasAvailableStock = $availableStocks->isNotEmpty();

            $options = $availableStocks->map(function ($stock) {
                return [
                    'id' => $stock->id,
                    'text' => $stock->bag_number
                ];
            })->values()->toArray();

            return [
                'public_id'           => $detail->public_id,
                'blood_pack_label'    => $detail->bloodPack ? $detail->bloodPack->label : '-',
                'blood_group'         => $detail->bloodPack ? $detail->bloodPack->blood_group->value : '-',
                'blood_rhesus'        => $detail->bloodPack ? $detail->bloodPack->blood_rhesus : '-',
                'blood_component'     => $detail->bloodPack ? $detail->bloodPack->blood_component->value : '-',
                'blood_pack_public_id' => $detail->bloodPack ? $detail->bloodPack->public_id : null,
                'has_available_stock' => $hasAvailableStock,
                'available_stocks'    => $options,
                'selected_stock_id'   => $detail->blood_stock_id,
                'component_id'        => $detail->component,
                'component_text'      => BloodComponent::getById($detail->component),
                'crossmatch_result'  => $detail->crossmatch_result,
                'transfusion_result'  => $detail->transfusion_result,
                'blood_stock_status'  => $detail->bloodStock ? $detail->bloodStock->blood_status : null,
                'is_approval_incompatible' => (bool)$detail->is_approval_incompatible,
                'blood_release_status' => (bool)$detail->blood_release_status,
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $details->count(),
            'recordsFiltered' => $details->count(),
            'data' => $pageData
        ]);
    }

    // ---------- Update Bag Number (Blood Stock ID) ----------
    public function updateBagNumber(Request $request, $detailPublicId)
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
            if($bloodStockOld){
                $bloodStockOld->update([
                    'blood_status' => BloodStockStatus::AVAILABLE,
                    'used_at'     => null,
                ]);
            }

            // Currently only updating the detail record.
            $detail->update([
                'blood_stock_id' => $request->blood_stock_id
            ]);

            if($request->blood_stock_id){
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
    public function updateBloodPacks(UpdateBloodPacksRequest $request, $id)
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
                $keyDetail = $oldDetail->component .'-'. $oldDetail->public_id;
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

                    if(!$componentExists) continue;

                    // Merge old data component if exists, otherwise create new
                    $keyComponent = $component['component_id'] .'-'. $component['public_id'];

                    $existingBloodTransfusionDetail = isset($oldBloodTransfusionDetail[$keyComponent]) ? $oldBloodTransfusionDetail[$keyComponent] : null;

                    // public_id di-generate otomatis oleh model booted()
                    $transfusionDetail = BloodTransfusionDetail::create([
                        'blood_transfusion_id' => $transfusion->id,
                        'component'            => $existingBloodTransfusionDetail ? $existingBloodTransfusionDetail['component'] : $component['component_id'] ,
                        'blood_stock_id'       => $existingBloodTransfusionDetail ? $existingBloodTransfusionDetail['blood_stock_id'] : null,
                        'blood_pack_id'       => $existingBloodTransfusionDetail ? $existingBloodTransfusionDetail['blood_pack_id'] : null,
                        'crossmatch_result'   => $existingBloodTransfusionDetail ? $existingBloodTransfusionDetail['crossmatch_result'] : null,
                    ]);

                    $patient = $transfusion->patient;
                    // Check Blood Stock bedasarkan Blood Group dan Rhesus Pasien
                    if(!is_null($patient->blood_group) && !is_null($patient->blood_rhesus) && is_null($transfusionDetail->blood_pack_id)){

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

                        if($availableStock){
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
    // ---------- Datatable List Test ----------
    public function datatableListTest(Request $request, $id)
    {
        $draw = (int) $request->input('draw', 1);
        $detailPublicId = $request->input('detail_id');

        return response()->json(
            $this->bloodTransfusionDetailTestService->getDatatableData($id, $draw, $detailPublicId)
        );
    }

    // ---------- Update Result Test ----------
    public function updateTestResult(Request $request, $id)
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

    public function updateTestVerifiedValidated(Request $request, $id)
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
    public function completeTest($detailPublicId)
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
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Workflow Actions ----------

    public function holdBloodPack($detailPublicId)
    {
        try {
            $detail = BloodTransfusionDetail::where('public_id', $detailPublicId)->firstOrFail();
            if ($detail->blood_stock_id) {
                $stock = BloodStock::find($detail->blood_stock_id);
                if ($stock) {
                    $stock->update(['blood_status' => BloodStockStatus::ALREADY_TAKEN]);
                }
            }

            return response()->json([
                'message' => 'Blood pack has been held.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to hold blood pack.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function acceptIncompatible($detailPublicId)
    {
        try {
            $detail = BloodTransfusionDetail::where('public_id', $detailPublicId)->firstOrFail();
            $detail->update([
                'is_approval_incompatible' => true
            ]);

            return response()->json([
                'message' => 'Incompatible blood has been accepted.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to accept incompatible blood.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function releaseBloodPack($detailPublicId)
    {
        try {
            $detail = BloodTransfusionDetail::where('public_id', $detailPublicId)->firstOrFail();
            $detail->update(['blood_release_status' => true]);

            if ($detail->blood_stock_id) {
                $stock = BloodStock::find($detail->blood_stock_id);
                if ($stock) {
                    $stock->update(['blood_status' => BloodStockStatus::TAKEN_OUT]);
                }
            }

            return response()->json([
                'message' => 'Blood pack has been released.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to release blood pack.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function unreleaseBloodPack($detailPublicId)
    {
        try {
            $detail = BloodTransfusionDetail::where('public_id', $detailPublicId)->firstOrFail();
            $detail->update(['blood_release_status' => false]);

            if ($detail->blood_stock_id) {
                $stock = BloodStock::find($detail->blood_stock_id);
                if ($stock) {
                    $stock->update(['blood_status' => BloodStockStatus::USED]);
                }
            }

            return response()->json([
                'message' => 'Blood pack has been unreleased (status: USED).'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to unrelease blood pack.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
