<?php

namespace App\Services\Report;

use App\Enums\BloodTransfusionStatus;
use App\Models\BloodTransfusion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class ReportDataService
{
    public function datatable(string $report, Request $request)
    {
        switch ($report) {
            case 'blood-usage':
                return $this->datatableBloodUsage($request);
            default:
                abort(404, "Report '{$report}' not found.");
        }
    }

    // ---------- Helper ----------
    private function getReportConfig(string $report): array
    {
        $modules = config('report');
        abort_unless(isset($modules[$report]), 404, "Report '{$report}' not found.");
        return $modules[$report];
    }
    private function getSearchableColumns(string $model): array
    {
        return (new $model)->getFillable();
    }
    protected function getDateRange(Request $request): array
    {
        $start = $request->start_date;
        $end = $request->end_date;
        if (!$start || !$end) {
            return [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay(),
            ];
        }

        try {
            return [
                Carbon::createFromFormat('d-m-Y', $start)->startOfDay(),
                Carbon::createFromFormat('d-m-Y', $end)->endOfDay(),
            ];
        } catch (\Exception $e) {
            logger()->error('Date range error: ' . $e->getMessage());
            return [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay(),
            ];
        }
    }
    protected function applyReportFilter(Builder $query, string $report, Request $request): void
    {
        switch ($report) {
            case 'blood-usage':
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('room_id')) {
                    $query->where('room_id', $request->room_id);
                }
                if ($request->filled('doctor_id')) {
                    $query->where('doctor_id', $request->doctor_id);
                }
                if ($request->filled('blood_group')) {
                    $query->where('patient_blood_group', $request->blood_group);
                }
                break;
            default:
                null;
        }
    }

    // ---------- Datatable Blood Usage ----------
    private function datatableBloodUsage(Request $request)
    {
        [$startDate, $endDate] = $this->getDateRange($request);

        $transfusions = BloodTransfusion::withoutTrashed()
            ->where('status', BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED->value)
            ->whereBetween('blood_transfusions.created_at', [$startDate, $endDate])
            ->with(['room', 'details.bloodPack'])
            ->get();

        $grouped = $transfusions
            ->flatMap(function ($transfusion) {
                return $transfusion->details->map(function ($detail) use ($transfusion) {
                    return [
                        'room_id' => $transfusion->room_id,
                        'room_name' => $transfusion->room?->name,
                        'blood_pack_id' => $detail->blood_pack_id,
                        'blood_component' => $detail->bloodPack?->blood_component,
                        'blood_group' => $detail->bloodPack?->blood_group,
                        'blood_rhesus' => $detail->bloodPack?->blood_rhesus,
                    ];
                });
            })
            ->groupBy(fn($row) => $row['room_id'] . '_' . $row['blood_pack_id'])
            ->map(function ($rows) {
                $first = $rows->first();
                return [
                    'room_id' => $first['room_id'],
                    'room_name' => $first['room_name'],
                    'blood_pack_id' => $first['blood_pack_id'],
                    'blood_component' => $first['blood_component'],
                    'blood_group' => $first['blood_group'],
                    'blood_rhesus' => $first['blood_rhesus'],
                    'total_per_room_per_pack' => $rows->count(),
                ];
            })
            ->values();
        $roomTotals = $grouped
            ->groupBy('room_id')
            ->map(fn($rows) => $rows->sum('total_per_room_per_pack'));

        $data = $grouped->map(function ($row) use ($roomTotals) {
            $row['room_grand_total'] = $roomTotals[$row['room_id']] ?? 0;
            return $row;
        });

        return DataTables::of($data)->toJson();
    }
}
