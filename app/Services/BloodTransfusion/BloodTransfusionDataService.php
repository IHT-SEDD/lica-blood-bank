<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodComponent;
use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionStatus;
use App\Enums\ResultTest;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionLogActivity;
use App\Models\CrossmatchHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BloodTransfusionDataService
{
    // ---------- Fungsi Tabel Blood Pack ----------
    public function bloodPackTable(Request $request): array
    {
        $draw = (int) $request->input('draw', 1);
        $data = BloodComponent::toSelect();

        return [
            'draw' => $draw,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data,
        ];
    }

    // ---------- Fungsi Tabel Blood Request ----------
    public function bloodRequestTable(Request $request): JsonResponse
    {
        $query = BloodTransfusion::with(['patient', 'room', 'insurance', 'doctor'])
            ->withoutTrashed()
            ->whereNull('archived_at');
        $this->applyDateRangeFilter($query, $request->input('date_range'));

        return DataTables::eloquent($query)
            ->filter(function ($query) use ($request) {
                $search = trim($request->input('search.value', ''));
                if (!empty($search)) {
                    $this->applySearchFilter($query, $search);
                }
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
            })
            ->order(function ($query) use ($request) {
                $columns = [
                    0 => 'lab_number',
                    1 => 'patient_id',
                    2 => 'doctor_id',
                    3 => 'room_id',
                    4 => 'created_at',
                ];
                $order = $request->input('order.0.column');
                $dir = $request->input('order.0.dir', 'asc');
                if (isset($columns[$order])) {
                    $query->orderBy($columns[$order], $dir);
                } else {
                    $query->orderBy('lab_number', 'asc');
                }
            })
            ->addColumn('row_data', function ($item) {
                return $this->mapBloodRequestRow($item);
            })
            ->toJson();
    }
    // ---------- Fungsi Tabel Blood Request Archive ----------
    public function bloodRequestTableArchive(Request $request): JsonResponse
    {
        $query = BloodTransfusion::with(['patient', 'room', 'insurance', 'doctor'])
            ->withoutTrashed()->whereNotNull('archived_at');
        $this->applyArchiveDateRangeFilter($query, $request->input('date_range'));

        return DataTables::eloquent($query)
            ->filter(function ($query) use ($request) {
                $search = trim($request->input('search.value', ''));
                if (!empty($search)) {
                    $this->applySearchFilter($query, $search);
                }
            })
            ->order(function ($query) use ($request) {
                $columns = [
                    0 => 'lab_number',
                    1 => 'patient_id',
                    2 => 'doctor_id',
                    3 => 'room_id',
                    4 => 'created_at',
                ];
                $order = $request->input('order.0.column');
                $dir = $request->input('order.0.dir', 'asc');
                if (isset($columns[$order])) {
                    $query->orderBy($columns[$order], $dir);
                } else {
                    $query->orderBy('lab_number', 'asc');
                }
            })
            ->addColumn('row_data', function ($item) {
                return $this->mapBloodRequestRow($item);
            })
            ->toJson();
    }

    // ---------- Fungsi Tabel List Bag Request ----------
    public function listBagRequestTable(Request $request, string $id): JsonResponse
    {
        $transfusion = BloodTransfusion::where('public_id', $id)->first();
        if (!$transfusion) {
            return DataTables::of(collect())->toJson();
        }

        $query = BloodTransfusionDetail::with(['bloodPack', 'bloodStock'])
            ->where('blood_transfusion_id', $transfusion->id);

        return DataTables::eloquent($query)
            ->addColumn('row_data', function ($detail) use ($transfusion) {
                return $this->mapBagRequestRow($detail, $transfusion);
            })
            ->toJson();
    }
    // ---------- Fungsi Tabel List Archive Bag Request ----------
    public function listArchiveBagRequestTable(Request $request): JsonResponse
    {
        $transfusionPublicID = $request->input('transfusion_public_id');

        $transfusion = BloodTransfusion::where('public_id', $transfusionPublicID)->first();
        if (!$transfusion) {
            return DataTables::of(collect())->toJson();
        }

        $query = BloodTransfusionDetail::with(['bloodPack', 'bloodStock'])
            ->where('blood_transfusion_id', $transfusion->id);

        return DataTables::eloquent($query)
            ->addColumn('row_data', function ($detail) use ($transfusion) {
                return $this->mapBagRequestRow($detail, $transfusion);
            })
            ->toJson();
    }

    // ---------- Fungsi Tabel List History Test ----------
    public function listHistoryTestTable(Request $request, string $patientId): JsonResponse
    {
        $transfusions = BloodTransfusion::where('patient_id', $patientId)
            ->select([
                'id',
                'public_id',
                'lab_number',
                'order_number',
                'blood_request_at',
            ])
            ->where('status', BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED->value)
            ->get();
        if (!$transfusions) {
            return DataTables::of(collect())->toJson();
        }

        $query = BloodTransfusionDetail::whereIn('blood_transfusion_id', $transfusions->pluck('id'))
            ->with([
                'bloodPack:id,public_id,blood_group,blood_rhesus,blood_component',
                'bloodStock:id,public_id,bag_number,bag_number_lica,blood_volume,aftap_date,expiry_date,process_date,is_hiv,is_hcv,is_hbsag,is_syphilis,blood_status,used_at,created_at',
                'bloodTransfusion',
                'bloodTransfusion.patient:id,public_id,name,medrec,gender,birthdate',
                'bloodTransfusion.room:id,public_id,name,type',
            ]);

        return DataTables::eloquent($query)
            ->addColumn('blood_request_at', function ($row) {
                return $row->bloodTransfusion?->blood_request_at ?? '-';
            })
            ->addColumn('lab_number', function ($row) {
                return $row->bloodTransfusion?->lab_number ?? '-';
            })
            ->addColumn('order_number', function ($row) {
                return $row->bloodTransfusion?->order_number ?? '-';
            })
            ->addColumn('bag_number', function ($row) {
                return $row->bloodStock?->bag_number ?? '-';
            })
            ->toJson();
    }

    // ---------- Fungsi Tabel List Test ----------
    public function listTestTable(Request $request, string $id): JsonResponse
    {
        $detailPublicId = $request->input('detail_id');

        $transfusion = BloodTransfusion::where('public_id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$transfusion) {
            return DataTables::of([])->with(['result_options' => ResultTest::toSelect()])
                ->toJson();
        }

        $detailQuery = BloodTransfusionDetail::withoutTrashed()
            ->with([
                'bloodPack:id,public_id,blood_group,blood_rhesus,blood_component',
                'bloodTransfusionDetailTests.test:id,name',
                'bloodStock:id,bag_number',
                'bloodTransfusionDetailTests.verifiedByUser:id,name',
                'bloodTransfusionDetailTests.validatedByUser:id,name',
            ])
            ->where('blood_transfusion_id', $transfusion->id);
        if ($detailPublicId) {
            $detailQuery->where('public_id', $detailPublicId);
        }

        $rows = [];
        foreach ($detailQuery->get() as $detail) {
            $tests = $detail->bloodTransfusionDetailTests ?? collect();
            if ($tests->isEmpty()) {
                $rows[] = [
                    'detail_test_public_id' => null,
                    'component' => $detail->component,
                    'bag_number' => '-',
                    'test_name' => '-',
                    'result_value' => null,
                    'verified' => false,
                    'validated' => false,
                    'bag_released' => false,
                ];
                continue;
            }
            foreach ($tests as $detailTest) {
                $rows[] = [
                    'detail_test_public_id' => $detailTest->public_id,
                    'component' => $detail->component,
                    'test_name' => $detailTest->test?->name ?? '-',
                    'bag_number' => $detail->bloodStock?->bag_number ?? '-',
                    'result_value' => $detailTest->result,
                    'verified' => !empty($detailTest->verified_at)
                        && !empty($detailTest->verified_by_user_id),
                    'validated' => !empty($detailTest->validated_at)
                        && !empty($detailTest->validated_by_user_id),
                    'bag_released' => $detail->blood_release_status
                ];
            }
        }

        return DataTables::of($rows)->with(['result_options' => ResultTest::toSelect()])
            ->toJson();
    }
    // ---------- Fungsi Tabel Archive Test ----------
    public function listArchiveTable(Request $request): JsonResponse
    {
        $detailPublicId = $request->input('transfusion_detail_public_id');
        $transfusionPublicID = $request->input('transfusion_public_id');

        $transfusion = BloodTransfusion::where('public_id', $transfusionPublicID)
            ->whereNull('deleted_at')
            ->first();
        if (!$transfusion) {
            return DataTables::of([])->toJson();
        }

        $detailQuery = BloodTransfusionDetail::withoutTrashed()
            ->with([
                'bloodPack:id,public_id,blood_group,blood_rhesus,blood_component',
                'bloodTransfusionDetailTests.test:id,name',
                'bloodStock:id,bag_number',
                'bloodTransfusionDetailTests.resultByUser:id,name',
                'bloodTransfusionDetailTests.verifiedByUser:id,name',
                'bloodTransfusionDetailTests.validatedByUser:id,name',
            ])
            ->where('blood_transfusion_id', $transfusion->id);
        if ($detailPublicId) {
            $detailQuery->where('public_id', $detailPublicId);
        }

        $rows = [];
        foreach ($detailQuery->get() as $detail) {
            $tests = $detail->bloodTransfusionDetailTests ?? collect();
            if ($tests->isEmpty()) {
                $rows[] = [
                    'detail_test_public_id' => null,
                    'component' => $detail->component,
                    'bag_number' => '-',
                    'test_name' => '-',
                    'result_value' => null,
                    'verified' => false,
                    'validated' => false,
                    'bag_released' => false,
                    'result_by_user_name' => null,
                ];
                continue;
            }
            foreach ($tests as $detailTest) {
                $rows[] = [
                    'detail_test_public_id' => $detailTest->public_id,
                    'component' => $detail->component,
                    'test_name' => $detailTest->test?->name ?? '-',
                    'bag_number' => $detail->bloodStock?->bag_number ?? '-',
                    'result_value' => $detailTest->result,
                    'verified' => !empty($detailTest->verified_at)
                        && !empty($detailTest->verified_by_user_id),
                    'validated' => !empty($detailTest->validated_at)
                        && !empty($detailTest->validated_by_user_id),
                    'bag_released' => $detail->blood_release_status,
                    'result_by_user_name' => $detailTest->resultByUser->name ?? '-',
                ];
            }
        }

        return DataTables::of($rows)->toJson();
    }

    // ---------- Fungsi untuk mengambil data log berdasarkan id ----------
    public function getDataLogById(string $id)
    {
        $bloodTransfusionLog = BloodTransfusionLogActivity::where(
            'blood_transfusion_public_id',
            $id
        )
            ->orderBy('timestamp', 'asc')
            ->limit(50)
            ->get();

        return $bloodTransfusionLog;
    }

    // ---------- Fungsi untuk mengambil data berdasarkan id ----------
    public function getDataById(string $public_id)
    {
        $data = BloodTransfusion::with(['patient', 'insurance', 'room', 'doctor'])
            ->where('public_id', $public_id)
            ->first();
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $data->public_id,
                'insurance_public_id' => $data->insurance->public_id,
                'room_public_id' => $data->room->public_id,
                'doctor_public_id' => $data->doctor->public_id,
                'relation_name' => $data->relation_name,
                'relation_type' => $data->relation_type,
                'blood_request_at' => $data->blood_request_at,
                'diagnosis' => $data->diagnosis,
                'is_dct' => $data->is_dct,
                'patient_public_id' => $data->patient->public_id,
                'patient_name' => $data->patient->name,
                'patient_blood_group' => $data->patient->blood_group,
                'patient_blood_rhesus' => $data->patient->blood_rhesus,
                'blood_transfusion' => $data
            ],
        ]);
    }

    // ---------- HELPERS ----------
    private function applyDateRangeFilter(Builder $query, ?string $dateRange): void
    {
        if (empty($dateRange)) {
            $query->whereDate('created_at', Carbon::now()->format('Y-m-d'));
            return;
        }

        try {
            $dates = explode(' to ', $dateRange);

            if (count($dates) === 2) {
                $start = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $end   = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            } elseif (count($dates) === 1) {
                $date = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $query->whereDate('created_at', $date);
            }
        } catch (\Exception) {
            // Parsing gagal, filter tanggal tidak diterapkan
        }
    }
    private function applyArchiveDateRangeFilter(Builder $query, ?string $dateRange): void
    {
        if (empty($dateRange)) {
            $query->whereDate('archived_at', Carbon::now()->format('Y-m-d'));
            return;
        }

        try {
            $dates = explode(' to ', $dateRange);

            if (count($dates) === 2) {
                $start = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $end   = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('archived_at', [$start, $end]);
            } elseif (count($dates) === 1) {
                $date = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $query->whereDate('archived_at', $date);
            }
        } catch (\Exception) {
            // Parsing gagal, filter tanggal tidak diterapkan
        }
    }
    private function applySearchFilter(Builder $query, string $searchValue): void
    {
        if ($searchValue === '') return;

        $query->where(function ($sub) use ($searchValue) {
            $sub->where('order_number', 'like', "{$searchValue}%")
                ->orWhere('lab_number', 'like', "{$searchValue}%")
                ->orWhereHas(
                    'patient',
                    fn($q) => $q
                        ->where('name', 'like', "%{$searchValue}%")
                        ->orWhere('medrec', 'like', "%{$searchValue}%")
                )
                ->orWhereHas(
                    'room',
                    fn($q) => $q
                        ->where('name', 'like', "%{$searchValue}%")
                );
        });
    }
    private function mapBloodRequestRow($item): array
    {
        return [
            'public_id' => $item->public_id,
            'blood_request_at' => $item->blood_request_at
                ? Carbon::parse($item->blood_request_at)->format('Y/m/d')
                : '-',
            'deleted_at' => $item->deleted_at
                ? Carbon::parse($item->deleted_at)->format('Y/m/d')
                : '-',
            'order_number' => $item->order_number ?? '-',
            'lab_number' => $item->lab_number ?? '-',
            'diagnosis' => $item->diagnosis ?? '-',
            'is_cito' => false,
            'status' => $item->status,
            'patient' => [
                'medrec' => $item->patient->medrec ?? '-',
                'name' => $item->patient->name ?? '-',
                'gender' => match ($item->patient->gender ?? null) {
                    'M' => 'Male',
                    'F' => 'Female',
                    default => '-',
                },
                'email' => $item->patient->email ?? '-',
                'address' => $item->patient->address ?? '-',
                'age' => $item->patient->birthdate
                    ? Carbon::parse($item->patient->birthdate)->diff(Carbon::now())->format('%yY/%mM/%dD')
                    : '-',
                'blood_group'  => $item->patient->blood_group ?? '-',
                'blood_rhesus' => $item->patient->blood_rhesus ?? '-',
            ],
            'room' => [
                'name' => $item->room->name ?? '-',
                'type' => $item->room->type
                    ? str_replace('_', ' ', Str::kebab($item->room->type))
                    : '-',
            ],
            'insurance' => [
                'name' => $item->insurance->name ?? '-',
            ],
            'doctor' => [
                'name' => $item->doctor->name ?? '-',
            ],
        ];
    }
    private function mapBagRequestRow(BloodTransfusionDetail $detail, BloodTransfusion $transfusion): array
    {
        $availableStocks = BloodStock::where('blood_pack_id', $detail->blood_pack_id)
            ->whereIn('blood_status', [
                BloodStockStatus::AVAILABLE,
                BloodStockStatus::USED,
            ])
            ->where('expiry_date', '>', $transfusion->blood_request_at)
            ->get();

        $patientId = $transfusion->patient_id;
        $availableStocks = $availableStocks->filter(function ($stock) use ($patientId, $detail) {
            $isIncompatible = CrossmatchHistory::where('blood_stock_id', $stock->id)
                ->whereHas('bloodTransfusionDetail.bloodTransfusion', function ($query) use ($patientId) {
                    $query->where('patient_id', $patientId);
                })
                ->where('result', 'Incompatible')
                ->exists();
            if ($isIncompatible) {
                return $stock->id === $detail->blood_stock_id;
            }
            return true;
        })->values();

        if ($detail->blood_stock_id) {
            $selectedStock = BloodStock::find($detail->blood_stock_id);
            if ($selectedStock && !$availableStocks->contains('id', $selectedStock->id)) {
                $availableStocks->push($selectedStock);
            }
        }

        $options = $availableStocks->map(fn($stock) => [
            'id' => $stock->id,
            'text' => $stock->bag_number,
            'expiry' => $stock->expiry_date,
        ])->values()->toArray();

        $bloodPack = $detail->bloodPack;

        return [
            'public_id' => $detail->public_id,
            'blood_pack_label' => $bloodPack?->label ?? '-',
            'blood_group' => $bloodPack?->blood_group->value ?? '-',
            'blood_rhesus' => $bloodPack?->blood_rhesus ?? '-',
            'blood_component' => $bloodPack?->blood_component->value ?? '-',
            'blood_pack_public_id' => $bloodPack?->public_id,
            'has_available_stock' => $availableStocks->isNotEmpty(),
            'available_stocks' => $options,
            'selected_stock_id' => $detail->blood_stock_id,
            'selected_bag_number' => $detail->bloodStock?->bag_number ?? null,
            'bag_number' => $detail->bloodStock?->bag_number ?? null,
            'component_id' => $detail->component,
            'component_text' => BloodComponent::getById($detail->component),
            'crossmatch_result' => $detail->crossmatch_result,
            'transfusion_result' => $detail->transfusion_result,
            'blood_stock_status' => $detail->bloodStock?->blood_status,
            'is_approval_incompatible' => (bool) $detail->is_approval_incompatible,
            'is_print_incompatible_letter' => (bool) $detail->is_print_incompatible_letter,
            'blood_release_status' => (bool) $detail->blood_release_status,
        ];
    }
}
