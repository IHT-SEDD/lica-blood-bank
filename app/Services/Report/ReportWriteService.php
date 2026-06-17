<?php

namespace App\Services\Report;

use App\Enums\BloodComponent;
use App\Enums\BloodGroup;
use App\Enums\BloodTransfusionStatus;
use App\Exports\Report\BloodUsage\ReportBloodUsageExport;
use App\Models\BloodTransfusion;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ReportWriteService
{
    // ---------- Fungsi export ----------
    public function exportExcel(string $report, Request $request)
    {
        switch ($report) {
            case 'blood-usage':
                return $this->excelBloodUsage($request, $report);
            default:
                abort(404, "Report '{$report}' not found.");
        }
    }

    // ---------- Excel Blood Usage ----------
    public function excelBloodUsage(Request $request, string $report)
    {
        [$startDate, $endDate] = $this->getDateRange($request);

        $transfusions = BloodTransfusion::withoutTrashed()
            ->where('status', BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED->value)
            ->whereBetween('blood_transfusions.created_at', [$startDate, $endDate])
            ->with(['room', 'details.bloodPack'])
            ->when($request->blood_pack_public_id, function ($query, $id) {
                $query->whereHas('details.bloodPack', fn($q) => $q->where('public_id', $id));
            })
            ->when($request->room_public_id, function ($query, $id) {
                $query->whereHas('room', fn($q) => $q->where('public_id', $id));
            })
            ->get();

        $paramFilenameExcel = [];
        if ($request->room_public_id) {
            $roomName = Room::where('public_id', $request->room_public_id)->value('name');
            $paramFilenameExcel = [
                'room_name' => $roomName
            ];
        }

        $reportData  = $this->prepareBloodUsageData($transfusions, $startDate);
        $fileName = $this->buildFileName($startDate, $endDate, $report, $paramFilenameExcel);
        $storagePath = 'report/blood_usage/' . $fileName;

        Excel::store(new ReportBloodUsageExport($reportData), $storagePath, 'public');
        return Excel::download(new ReportBloodUsageExport($reportData), $fileName);
    }
    public function prepareBloodUsageData(Collection $transfusions, ?Carbon $referenceDate = null): array
    {
        $components = collect(BloodComponent::cases())
            ->mapWithKeys(fn(BloodComponent $c) => [$c->value => $c->exportLabel()])
            ->all();
        $bloodGroups = collect(BloodGroup::cases())
            ->map(fn(BloodGroup $g) => $g->value)
            ->all();

        $bulanLabel = '';
        if ($referenceDate) {
            $original = Carbon::getLocale();
            Carbon::setLocale('id');
            $bulanLabel = strtoupper($referenceDate->translatedFormat('F Y'));
            Carbon::setLocale($original);
        }
        $title = 'LAPORAN PEMAKAIAN KOMPONEN DARAH BDRS INDRAMAYU BULAN ' . $bulanLabel;

        $rooms = $transfusions
            ->map(fn($t) => $t->room?->name ?? '-')
            ->unique()
            ->values()
            ->all();

        $qtyMap = [];
        foreach ($transfusions as $transfusion) {
            $room = $transfusion->room?->name ?? '-';
            $transfusion->details
                ->groupBy(fn($detail) => $detail->blood_pack_id)
                ->each(function ($detailGroup) use (&$qtyMap, $room) {
                    $first = $detailGroup->first();
                    $comp  = $first->bloodPack?->blood_component?->value;
                    $group = $first->bloodPack?->blood_group?->value;

                    if (!$comp || !$group) return;

                    $qtyMap[$room][$comp][$group] =
                        ($qtyMap[$room][$comp][$group] ?? 0) + $detailGroup->count();
                });
        }

        $emptyGroups = array_fill_keys($bloodGroups, 0);
        $emptyComps  = array_fill_keys(array_keys($components), $emptyGroups);
        $rows = array_fill_keys($rooms, array_merge($emptyComps, ['_total' => 0]));
        $totals = array_merge($emptyComps, ['_grand' => 0]);

        foreach ($qtyMap as $room => $comps) {
            foreach ($comps as $comp => $groups) {
                foreach ($groups as $group => $qty) {
                    if (!isset($rows[$room][$comp][$group])) continue;

                    $rows[$room][$comp][$group] += $qty;
                    $rows[$room]['_total'] += $qty;
                    $totals[$comp][$group] += $qty;
                    $totals['_grand'] += $qty;
                }
            }
        }

        return compact('title', 'components', 'bloodGroups', 'rooms', 'rows', 'totals');
    }

    // ---------- HELPERS ----------
    protected function getDateRange(Request $request): array
    {
        $start = $request->start_date;
        $end = $request->end_date;
        if (!$start || !$end) {
            return [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()];
        }
        try {
            return [
                Carbon::createFromFormat('d-m-Y', $start)->startOfDay(),
                Carbon::createFromFormat('d-m-Y', $end)->endOfDay(),
            ];
        } catch (\Exception $e) {
            logger()->error('Date range error: ' . $e->getMessage());
            return [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()];
        }
    }
    protected function buildFileName(Carbon $startDate, Carbon $endDate, string $report, array $params = []): string
    {
        switch ($report) {
            case 'blood-usage':
                if (!empty($params['room_name'])) {
                    return 'Laporan Penggunaan Darah - ' . $params['room_name'] . ' - ' . $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y') . '.xlsx';
                }
                return 'Laporan Penggunaan Darah - ' . $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y') . '.xlsx';
            default:
                abort(404, "Report '{$report}' not found.");
        }
    }
}
