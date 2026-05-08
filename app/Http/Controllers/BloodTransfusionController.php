<?php

namespace App\Http\Controllers;

use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\Patient;
use App\Models\Package;
use App\Models\BloodTransfusionDetailTest;
use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionStatus;
use App\Http\Requests\BloodTransfusion\StoreBloodTransfusionRequest;
use App\Http\Requests\BloodTransfusion\UpdateBloodTransfusionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BloodTransfusionController extends Controller
{
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
// dd($request->all());
        $allItemsCacheKey = sprintf('blood-transfusion.blood-pack.all.%s', md5($searchValue ?: 'all'));

        $filteredItems = Cache::remember($allItemsCacheKey, 60, function () use ($searchValue) {
            $query = BloodPack::select(
                'blood_packs.id',
                'blood_packs.public_id',
                'blood_packs.blood_group',
                'blood_packs.blood_rhesus',
                'blood_packs.blood_component'
            )
                ->where('blood_packs.is_active', 1)
                ->whereNull('blood_packs.deleted_at');
                
            // if ($request->blood_group !== '') {
            //     $query->where('blood_packs.blood_group', $request->blood_group);
            // }

            // if ($request->blood_rhesus !== '') {
            //     $query->where('blood_packs.blood_rhesus', $request->blood_rhesus);
            // }

            if ($searchValue !== '') {
                $query->where(function ($sub) use ($searchValue) {
                    $sub->where('blood_packs.blood_group', 'like', "{$searchValue}%")
                        ->orWhere('blood_packs.blood_rhesus', 'like', "{$searchValue}%")
                        ->orWhere('blood_packs.blood_component', 'like', "{$searchValue}%");
                });
            }

            return $query->orderBy('blood_packs.blood_group')
                ->orderBy('blood_packs.blood_rhesus')
                ->orderBy('blood_packs.blood_component')
                ->get();
        });

        $recordsTotal = Cache::remember('blood-transfusion.blood-pack.records-total', 60, function () {
            return BloodPack::where('is_active', 1)
                ->whereNull('deleted_at')
                ->count();
        });

        $recordsFiltered = $filteredItems->count();
        if ($length === -1) {
            $pageData = $filteredItems->slice($start)->values();
        } else {
            $pageData = $filteredItems->slice($start, max($length, 1))->values();
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $pageData,
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

        $query = BloodTransfusion::with(['patient', 'room'])
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
                'patient' => [
                    'medrec' => $item->patient->medrec ?? '-',
                    'name' => $item->patient->name ?? '-',
                ],
                'room' => [
                    'name' => $item->room->name ?? '-',
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
        $selected_blood_packs = json_decode($request->selected_blood_packs);

        if (empty($selected_blood_packs)) {
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
                'blood_quantity'   => count($selected_blood_packs),
            ]);

            // ---------- 3. Buat blood transfusion details (satu per blood pack) ----------
            // Cari blood_stock_id: blood_pack_id match + belum expired + status available
            // Dapatkan test yang active
            $package = Package::with(['package_tests'])->where('is_active', 1)->first();
            // dd($package->package_tests);
            foreach ($selected_blood_packs as $pack) {

                $bloodPack = BloodPack::where('public_id', $pack->public_id)
                    ->where('is_active', 1)
                    ->whereNull('deleted_at')
                    ->first();
   
                // public_id di-generate otomatis oleh model booted()
                $transfusionDetail = BloodTransfusionDetail::create([
                    'blood_transfusion_id' => $transfusion->id,
                    'blood_pack_id'        => $bloodPack?->id,
                ]);

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
    public function getDataById($public_id)
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
                'patient_public_id'       => $data->patient->public_id,
                'patient_name'       => $data->patient->name,
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
            $transfusion = BloodTransfusion::findOrFail($request->id);
            
            // Update transaksi
            $transfusion->update([
                'insurance_id'     => $request->insurance_id,
                'room_id'          => $request->room_id,
                'doctor_id'        => $request->doctor_id,
                'relation_name'    => $request->relation_name,
                'relation_type'    => $request->relation_type,
                'blood_request_at' => $request->blood_required_at,
                'diagnosis'        => $request->diagnosis,
            ]);

            // Update data pasien (blood_group & blood_rhesus)
            if ($transfusion->patient_id) {
                Patient::where('id', $transfusion->patient_id)->update([
                    'blood_group'  => $request->blood_group,
                    'blood_rhesus' => $request->blood_rhesus,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Blood request successfully updated.',
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
}
