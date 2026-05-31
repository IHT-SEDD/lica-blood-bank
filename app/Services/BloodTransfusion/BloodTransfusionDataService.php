<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodComponent;
use App\Enums\BloodStockStatus;
use App\Enums\ResultTest;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use App\Models\BloodTransfusionLogActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BloodTransfusionDataService
{
    const CACHE_BLOOD_TRANSFUSION_LOG_KEY = "blood_transfusion_log_data";

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
    public function bloodRequestTable(Request $request): array
    {
        $draw = (int) $request->input('draw', 1);
        $searchValue = trim($request->input('search.value', ''));
        $start = max((int) $request->input('start', 0), 0);
        $length = (int) $request->input('length', 10);
        $dateRange = $request->input('date_range');

        $query = BloodTransfusion::with(['patient', 'room', 'insurance', 'doctor'])
            ->withoutTrashed();

        $this->applyDateRangeFilter($query, $dateRange);
        $this->applySearchFilter($query, $searchValue);

        $recordsTotal = BloodTransfusion::withoutTrashed()->count();
        $recordsFiltered = $query->count();

        if ($length !== -1) {
            $query->offset($start)->limit($length);
        }

        $data = $query->orderBy('lab_number', 'asc')->get();

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->map(fn($item) => $this->mapBloodRequestRow($item)),
        ];
    }

    // ---------- Fungsi Tabel List Bag Request ----------
    public function listBagRequestTable(Request $request, string $id): array
    {
        $draw = (int) $request->input('draw', 1);

        $transfusion = BloodTransfusion::where('public_id', $id)->first();
        if (!$transfusion) {
            return [
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ];
        }

        $details = BloodTransfusionDetail::with('bloodPack')
            ->where('blood_transfusion_id', $transfusion->id)
            ->get();
        $data = $details->map(fn($detail) => $this->mapBagRequestRow($detail, $transfusion));

        return [
            'draw' => $draw,
            'recordsTotal' => $details->count(),
            'recordsFiltered' => $details->count(),
            'data' => $data,
        ];
    }

    // ---------- Fungsi Tabel List Test ----------
    public function listTestTable(Request $request, string $id): array
    {
        $draw = (int) $request->input('draw', 1);
        $detailPublicId = $request->input('detail_id');

        $transfusion = BloodTransfusion::where('public_id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$transfusion) {
            return [
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'result_options' => ResultTest::toSelect(),
            ];
        }

        $detailQuery = BloodTransfusionDetail::with([
            'bloodPack:id,public_id,blood_group,blood_rhesus,blood_component',
            'bloodTransfusionDetailTests.test:id,name',
            'bloodStock:id,bag_number',
            'bloodTransfusionDetailTests.verifiedByUser:id,name',
            'bloodTransfusionDetailTests.validatedByUser:id,name',
        ])
            ->where('blood_transfusion_id', $transfusion->id)
            ->whereNull('deleted_at');

        if ($detailPublicId) {
            $detailQuery->where('public_id', $detailPublicId);
        }

        $details = $detailQuery->get();
        $rows = [];

        foreach ($details as $detail) {
            $tests = $detail->bloodTransfusionDetailTests ?? collect();

            if ($tests->isEmpty()) {
                $rows[] = [
                    'detail_test_public_id' => null,
                    'bag_number' => '-',
                    'test_name' => '-',
                    'result_value' => null,
                    'is_verified' => false,
                    'is_validated' => false,
                ];
                continue;
            }

            foreach ($tests as $detailTest) {
                $rows[] = [
                    'detail_test_public_id' => $detailTest->public_id,
                    'test_name' => $detailTest->test?->name ?? '-',
                    'bag_number' => $detail->bloodStock?->bag_number ?? '-',
                    'result_value' => $detailTest->result,
                    'verified' => !empty($detailTest->verified_at) && !empty($detailTest->verified_by_user_id),
                    'validated' => !empty($detailTest->validated_at) && !empty($detailTest->validated_by_user_id),
                ];
            }
        }

        $total = count($rows);

        return [
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $rows,
            'result_options' => ResultTest::toSelect(),
        ];
    }

    // ---------- Fungsi untuk mengambil data log berdasarkan id ----------
    public function getDataLogById(string $id)
    {
        $cacheKey = self::CACHE_BLOOD_TRANSFUSION_LOG_KEY . ":{$id}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {
            $bloodTransfusionLog = BloodTransfusionLogActivity::where('blood_transfusion_public_id', $id)
                ->orderBy('timestamp', 'desc')
                ->limit(50)
                ->get();

            return $bloodTransfusionLog;
        });
    }

    // ---------- Private Fungsi: Filter tanggal & Search untuk datatable ----------
    private function applyDateRangeFilter(Builder $query, ?string $dateRange): void
    {
        if (empty($dateRange)) {
            $query->whereDate('blood_request_at', Carbon::now()->format('Y-m-d'));
            return;
        }

        try {
            $dates = explode(' to ', $dateRange);

            if (count($dates) === 2) {
                $start = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $end   = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('blood_request_at', [$start, $end]);
            } elseif (count($dates) === 1) {
                $date = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $query->whereDate('blood_request_at', $date);
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
                        ->where('name', 'like', "{$searchValue}%")
                        ->orWhere('medrec', 'like', "{$searchValue}%")
                )
                ->orWhereHas(
                    'room',
                    fn($q) => $q
                        ->where('name', 'like', "{$searchValue}%")
                );
        });
    }

    // ---------- Private Fungsi: Mapping Blood Request, untuk list blood request table ----------
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

    // ---------- Private Fungsi: Mapping Bag Request, untuk list bag request table ----------
    private function mapBagRequestRow(BloodTransfusionDetail $detail, BloodTransfusion $transfusion): array
    {
        $availableStocks = BloodStock::where('blood_pack_id', $detail->blood_pack_id)
            ->where('blood_status', BloodStockStatus::AVAILABLE->value)
            ->where('expiry_date', '>', $transfusion->blood_request_at)
            ->get();

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
