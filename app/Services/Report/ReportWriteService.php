<?php

namespace App\Services\Report;

use App\Enums\BloodComponent;
use App\Enums\BloodGroup;
use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionStatus;
use App\Exports\Report\BloodExpire\ReportBloodExpireExport;
use App\Exports\Report\BloodUsage\ReportBloodUsageExport;
use App\Models\BloodStock;
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
            case 'blood-expire':
                return $this->excelBloodExpire($request, $report);
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

    // ---------- Excel Blood Expire ----------
    public function excelBloodExpire(Request $request, string $report)
    {
        $monthYear = $request->filled('month_year')
            ? Carbon::createFromFormat('Y-m', $request->month_year)->startOfMonth()
            : Carbon::now()->startOfMonth();
        $startDate = $monthYear->copy()->startOfMonth();
        $endDate = $monthYear->copy()->endOfMonth();
        $bloodComponent = $request->blood_component;
        $paramFilenameExcel = [];

        $query = BloodStock::withoutTrashed()->with('bloodPacks')->where('blood_status', BloodStockStatus::EXPIRED)->whereBetween('expiry_date', [$startDate, $endDate]);
        if (!empty($bloodComponent)) {
            $query->whereHas('bloodPacks', function ($q) use ($bloodComponent) {
                $q->where('blood_component', $bloodComponent);
            });
            $componentName = BloodComponent::from($bloodComponent)->label();
            $paramFilenameExcel = [
                'blood_component' => $componentName
            ];
        }
        $bloodStocks = $query->get();

        $reportData = $this->prepareBloodExpireData($bloodStocks, $startDate);
        $fileName = $this->buildFileName($startDate, $endDate, $report, $paramFilenameExcel);
        $storagePath = 'report/blood_expire/' . $fileName;

        Excel::store(new ReportBloodExpireExport($reportData), $storagePath, 'public');
        return Excel::download(new ReportBloodExpireExport($reportData), $fileName);
    }
    public function prepareBloodExpireData(Collection $bloodStocks, ?Carbon $referenceDate = null): array
    {
        $components = collect(BloodComponent::cases())
            ->mapWithKeys(fn(BloodComponent $c) => [$c->value => $c->exportLabel()])
            ->all();
        $bloodGroups = collect(BloodGroup::cases())
            ->map(fn(BloodGroup $g) => $g->value)
            ->all();
        $bulanLabel = '';
        $year = $referenceDate?->year ?? now()->year;
        $month = $referenceDate?->month ?? now()->month;

        if ($referenceDate) {
            $original = Carbon::getLocale();
            Carbon::setLocale('id');
            $bulanLabel = strtoupper($referenceDate->translatedFormat('F Y'));
            Carbon::setLocale($original);
        }
        $title = 'LAPORAN DARAH EXPIRE BDRS INDRAMAYU BULAN ' . $bulanLabel;

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $allDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $allDates[] = Carbon::create($year, $month, $d)->toDateString();
        }

        $qtyMap = [];
        foreach ($bloodStocks as $bloodStock) {
            $expDate = $bloodStock->expiry_date instanceof Carbon
                ? $bloodStock->expiry_date->toDateString()
                : Carbon::parse($bloodStock->expiry_date)->toDateString();
            $bloodPack = $bloodStock->bloodPacks;
            if (!$bloodPack instanceof \App\Models\BloodPack) continue;

            $comp = $bloodPack->blood_component?->value;
            $group = $bloodPack->blood_group?->value;
            if (!$comp || !$group) continue;

            $qtyMap[$expDate][$comp][$group] =
                ($qtyMap[$expDate][$comp][$group] ?? 0) + 1;
        }

        $emptyGroups = array_fill_keys($bloodGroups, 0);
        $emptyComps = array_fill_keys(array_keys($components), $emptyGroups);

        $rows = [];
        foreach ($allDates as $date) {
            $rows[$date] = array_merge($emptyComps, ['_total' => 0]);
        }
        $totals = array_merge($emptyComps, ['_grand' => 0]);

        foreach ($qtyMap as $date => $comps) {
            if (!isset($rows[$date])) continue; // lewati jika di luar bulan
            foreach ($comps as $comp => $groups) {
                foreach ($groups as $group => $qty) {
                    if (!isset($rows[$date][$comp][$group])) continue;
                    $rows[$date][$comp][$group] += $qty;
                    $rows[$date]['_total'] += $qty;
                    $totals[$comp][$group] += $qty;
                    $totals['_grand'] += $qty;
                }
            }
        }

        $expDates = $allDates;

        return compact('title', 'components', 'bloodGroups', 'expDates', 'rows', 'totals');
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
            case 'blood-expire':
                $bulanLabel = $startDate->translatedFormat('F Y');
                if (!empty($params['blood_component'])) {
                    return 'Laporan Darah Kadaluarsa - ' . $params['blood_component'] . ' - ' . $bulanLabel . '.xlsx';
                }
                return 'Laporan Darah Kadaluarsa - ' . $bulanLabel . '.xlsx';
            default:
                abort(404, "Report '{$report}' not found.");
        }
    }
}
