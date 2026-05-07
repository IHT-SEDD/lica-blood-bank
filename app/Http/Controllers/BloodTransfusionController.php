<?php

namespace App\Http\Controllers;

use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\Patient;
use App\Models\Test;
use App\Models\BloodTransfusionDetailTest;
use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionStatus;
use App\Http\Requests\BloodTransfusion\StoreBloodTransfusionRequest;
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
                'id' => $item->id,
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
        // selectedPacks() adalah helper dari Form Request yang decode JSON
        $selectedPacks = $request->selectedPacks();

        if (empty($selectedPacks)) {
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
                'blood_quantity'   => count($selectedPacks),
            ]);

            // ---------- 3. Buat blood transfusion details (satu per blood pack) ----------
            // Cari blood_stock_id: blood_pack_id match + belum expired + status available
            // Dapatkan test yang active
            $tests = Test::where('is_active', 1)->get();
            foreach ($selectedPacks as $pack) {
                $bloodStock = BloodStock::where('blood_pack_id', $pack['id'])
                    ->where('expiry_date', '>', $request->blood_required_at)
                    ->where('blood_status', BloodStockStatus::AVAILABLE)
                    ->whereNull('deleted_at')
                    ->first();

                // public_id di-generate otomatis oleh model booted()
                $transfusionDetail = BloodTransfusionDetail::create([
                    'blood_transfusion_id' => $transfusion->id,
                    'blood_stock_id'       => $bloodStock?->id,
                ]);

                foreach ($tests as $key => $test) {
                    BloodTransfusionDetailTest::create([
                        'bt_detail_id' => $transfusionDetail->id,
                        'test_id'      => $test->id,
                        'type'         => 'single',
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
}
