<?php

namespace App\Services\Report;

use App\Enums\BloodComponent;
use App\Enums\BloodGroup;
use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionStatus;
use App\Enums\OrderBloodStatus;
use App\Exports\Report\BloodExpire\ReportBloodExpireExport;
use App\Exports\Report\BloodUsage\ReportBloodUsageExport;
use App\Exports\Report\ExpeditionBook\ExpeditionBookExport;
use App\Exports\Report\BloodRequest\BloodRequestExport;
use App\Exports\Report\BloodStock\ReportBloodStockExport;
use App\Exports\Report\Incompatible\ReportIncompatibleExport;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\OrderBlood;
use App\Models\Room;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
            case 'expedition-book':
                return $this->excelExpeditionBook($request, $report);
            case 'blood-request':
                return $this->excelBloodRequest($request, $report);
            case 'blood-stock':
                return $this->excelBloodStock($request, $report);
            case 'incompatible':
                return $this->excelIncompatible($request, $report);
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

    // ---------- Excel Blood Request ----------
    private function excelBloodRequest(Request $request, string $report)
    {
        $monthYear = $request->filled('month_year')
            ? Carbon::createFromFormat('Y-m', $request->month_year)->startOfMonth()
            : Carbon::now()->startOfMonth();
        $startDate = $monthYear->copy()->startOfMonth();
        $endDate = $monthYear->copy()->endOfMonth();
        $vendor = $request->vendor;
        $paramFilenameExcel = [];

        $query = OrderBlood::withoutTrashed()
            ->with(['vendors', 'orderBloodDetails.bloodPacks'])
            ->whereIn('status', [OrderBloodStatus::ALL_ORDER_STOCK_REGISTERED, OrderBloodStatus::DONE])
            ->whereYear('created_at', $monthYear->year)
            ->whereMonth('created_at', $monthYear->month);

        if (!empty($vendor)) {
            $query->whereHas('vendors', function ($q) use ($vendor) {
                $q->where('public_id', $vendor);
            });
            $vendorName = Vendor::where('public_id', $vendor)->value('name');
            $paramFilenameExcel = [
                'vendor_name' => $vendorName,
            ];
        }

        $orderBloods = $query->get();

        $reportData = $this->prepareBloodRequestData($orderBloods, $startDate);
        $fileName = $this->buildFileName($startDate, $endDate, $report, $paramFilenameExcel);
        $storagePath = 'report/blood_request/' . $fileName;

        Excel::store(new BloodRequestExport($reportData), $storagePath, 'public');
        return Excel::download(new BloodRequestExport($reportData), $fileName);
    }
    private function prepareBloodRequestData(Collection $orderBloods, ?Carbon $referenceDate = null): array
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
        $title = 'LAPORAN PERMINTAAN DROPPING DARAH BDRS INDRAMAYU BULAN ' . $bulanLabel;

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $allDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $allDates[] = Carbon::create($year, $month, $d)->toDateString();
        }

        $emptyGroups = array_fill_keys($bloodGroups, 0);
        $emptyComps = array_fill_keys(array_keys($components), $emptyGroups);
        $totals = array_merge($emptyComps, ['_grand' => 0]);

        $rows = $orderBloods
            ->sortBy('created_at')
            ->map(function ($order) use ($components, $bloodGroups, $emptyComps, &$totals) {
                $row = array_merge($emptyComps, [
                    'created_at'  => $order->created_at,
                    'po_number' => $order->po_number,
                    'vendor_name' => $order->vendors?->name ?? '-',
                    '_total' => 0,
                ]);

                foreach ($order->orderBloodDetails as $detail) {
                    $bloodPack = $detail->bloodPacks;
                    if (!$bloodPack) continue;

                    $comp  = $bloodPack->blood_component?->value;
                    $group = $bloodPack->blood_group?->value;
                    if (!$comp || !$group) continue;
                    if (!in_array($group, $bloodGroups)) continue;

                    $row[$comp][$group] += $detail->quantity;
                    $row['_total'] += $detail->quantity;

                    $totals[$comp][$group] += $detail->quantity;
                    $totals['_grand'] += $detail->quantity;
                }

                return $row;
            })
            ->values();

        return compact('title', 'components', 'bloodGroups', 'rows', 'totals');
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

        $query = BloodStock::withoutTrashed()->with('bloodPacks')->where('blood_status', BloodStockStatus::EXPIRED)->whereBetween('expiry_date', [$startDate, $endDate])->whereNull('used_at');
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

    // ---------- Excel Expedition Book ----------
    public function excelExpeditionBook(Request $request, string $report)
    {
        $monthYear = $request->filled('month_year')
            ? Carbon::createFromFormat('Y-m', $request->month_year)->startOfMonth()
            : Carbon::now()->startOfMonth();
        $startDate = $monthYear->copy()->startOfMonth();
        $endDate = $monthYear->copy()->endOfMonth();
        $paramFilenameExcel = [];

        $bloodTransfusions = BloodTransfusion::withoutTrashed()->with(
            [
                'patient',
                'insurance',
                'room',
                'details.bloodTransfusionDetailTests.test',
                'details.bloodStock.incomingBloodDetails.incomingBloods.orderBloods.vendors'
            ]
        )->where('status', BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED)
            ->whereBetween('blood_request_at', [$startDate, $endDate])
            ->get();

        $reportData = $this->prepareExpeditionBookData($bloodTransfusions, $startDate);
        $fileName = $this->buildFileName($startDate, $endDate, $report, $paramFilenameExcel);
        $storagePath = 'report/expedition_book/' . $fileName;

        // Excel::store(new ExpeditionBookExport($reportData), $storagePath, 'public');
        return Excel::download(new ExpeditionBookExport($reportData), $fileName);
    }
    private function prepareExpeditionBookData($bloodTransfusions, $startDate): array
    {
        $data = $bloodTransfusions
            ->map(function ($transfusion) {

                $user_is_admin = $transfusion->blood_transfusion_log_activities->map(function ($log) {
                    return $log->creator?->roles->firstWhere('name', 'Admin') ? $log->creator : null;
                })->filter()->first();

                $technician = $transfusion->blood_transfusion_log_activities->map(function ($log) {
                    return $log->creator?->roles->firstWhere('name', 'Teknisi Bank Darah') ? $log->creator : null;
                })->filter()->first();

                $mappedDetails = $transfusion->details->map(function ($detail) {

                    $tests = collect($detail->bloodTransfusionDetailTests)
                        ->keyBy(fn($test) => strtolower($test->test->name ?? ''));

                    return [
                        'no_kantong_darah'    => $detail->bloodStock?->bag_number,
                        'jenis_darah'         => $detail->component,
                        'asal_labu'           => $detail->bloodStock?->incomingBloodDetails?->incomingBloods?->orderBloods?->vendors?->name,
                        'result_mayor'        => $tests['mayor']->result ?? null,
                        'result_minor'        => $tests['minor']->result ?? null,
                        'result_auto_control' => $tests['auto control']->result ?? null,
                        'result_crossmatch'   => $detail?->crossmatch_result,
                    ];
                })->all();

                return [
                    'tanggal'            => $transfusion->blood_request_at ? Carbon::parse($transfusion->blood_request_at)->format('Y-m-d') : null,
                    'nama_pasien'        => $transfusion->patient?->name,
                    'no_medrec'          => $transfusion->patient?->medrec,
                    'goldar_rhesus'      => $transfusion->patient?->blood_group . $transfusion->patient?->blood_rhesus,
                    'ruangan'            => $transfusion->room?->name,
                    'diagnosa'           => $transfusion->diagnosis,
                    'jenis_pasien'       => $transfusion->insurance?->name,
                    'jam_penerimaan'     => $transfusion->blood_request_at ? Carbon::parse($transfusion->blood_request_at)->format('H:i') : null,
                    'jam_mulai'          => $transfusion->checkin_time ? Carbon::parse($transfusion->checkin_time)->format('H:i') : '',
                    'jam_selesai'        => $transfusion->finish_at ? Carbon::parse($transfusion->finish_at)->format('H:i') : null,
                    'admin'              => $user_is_admin?->name,
                    'teknisi_bank_darah' => $technician?->name,
                    'jumlah_permintaan'  => count($transfusion->details),
                    'details'            => $mappedDetails
                ];
            })
            ->values();

        $title = 'Buku Expedisi Darah ';

        $firstRow = collect($data)->first();

        $totalColumns = 0;

        if ($firstRow) {
            $keys = array_keys($firstRow);
            $totalColumns = !empty($firstRow['details']) ? count(array_keys($firstRow['details'][0])) + count($keys) : 0;
        }

        return compact('title', 'data', 'totalColumns');
    }

    // ---------- Excel Blood Stock ----------
    public function excelBloodStock(Request $request, string $report)
    {
        $monthYear = $request->filled('month_year')
            ? Carbon::createFromFormat('Y-m', $request->month_year)->startOfMonth()
            : Carbon::now()->startOfMonth();
        $startDate = $monthYear->copy()->startOfMonth();
        $endDate = $monthYear->copy()->endOfMonth();
        $bloodComponent = $request->blood_component;
        $paramFilenameExcel = [];

        $query = BloodStock::withoutTrashed()->with('bloodPacks')
            ->where('blood_status', BloodStockStatus::AVAILABLE)
            ->whereYear('created_at', $monthYear->year)
            ->whereMonth('created_at', $monthYear->month);

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

        $reportData = $this->prepareBloodStockData($bloodStocks, $startDate);
        $fileName = $this->buildFileName($startDate, $endDate, $report, $paramFilenameExcel);
        $storagePath = 'report/blood_stock/' . $fileName;

        Excel::store(new ReportBloodStockExport($reportData), $storagePath, 'public');
        return Excel::download(new ReportBloodStockExport($reportData), $fileName);
    }
    private function prepareBloodStockData(Collection $bloodStocks, ?Carbon $referenceDate = null): array
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
        $title = 'LAPORAN STOK DARAH BDRS INDRAMAYU BULAN ' . $bulanLabel;

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $allDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $allDates[] = Carbon::create($year, $month, $d)->toDateString();
        }

        $qtyMap = [];
        foreach ($bloodStocks as $bloodStock) {
            $stockDate = $bloodStock->created_at instanceof Carbon
                ? $bloodStock->created_at->toDateString()
                : Carbon::parse($bloodStock->created_at)->toDateString();

            $bloodPack = $bloodStock->bloodPacks;
            if (!$bloodPack) continue;

            $comp = $bloodPack->blood_component?->value;
            $group = $bloodPack->blood_group?->value;
            if (!$comp || !$group) continue;

            $qtyMap[$stockDate][$comp][$group] =
                ($qtyMap[$stockDate][$comp][$group] ?? 0) + 1;
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

        $stockDates = $allDates;

        return compact('title', 'components', 'bloodGroups', 'stockDates', 'rows', 'totals');
    }

    // ---------- Excel Incompatible ----------
    public function excelIncompatible(Request $request, string $report)
    {
        [$startDate, $endDate] = $this->getDateRange($request);
        $room = $request->room_public_id;
        $paramFilenameExcel = [];

        $query = BloodTransfusion::withoutTrashed()
            ->with([
                'room',
                'insurance',
                'blood_transfusion_details.bloodPack',
                'blood_transfusion_details.bloodStock',
                'blood_transfusion_details.bloodTransfusionDetailTests.test',
            ])
            ->whereNotNull('finish_at')
            ->where('status', BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED)
            ->whereBetween('blood_transfusions.created_at', [$startDate, $endDate])
            ->whereHas('blood_transfusion_details', function ($q) {
                $q->where('crossmatch_result', 'Incompatible');
            });

        if (!empty($room)) {
            $query->whereHas('room', function ($q) use ($room) {
                $q->where('public_id', $room);
            });

            $roomName = Room::where('public_id', $room)->value('name');
            $paramFilenameExcel = [
                'room_name' => $roomName,
            ];
        }

        $bloodTransfusions = $query->get();

        $reportData = $this->prepareIncompatibleData(
            bloodTransfusions: $bloodTransfusions,
            startDate: $startDate,
            endDate: $endDate,
            referenceDate: $startDate,
        );
        $fileName = $this->buildFileName($startDate, $endDate, $report, $paramFilenameExcel);
        $storagePath = 'report/incompatible/' . $fileName;

        Excel::store(new ReportIncompatibleExport($reportData), $storagePath, 'public');
        return Excel::download(new ReportIncompatibleExport($reportData), $fileName);
    }
    public function prepareIncompatibleData(Collection $bloodTransfusions, ?string $startDate, ?string $endDate, ?Carbon $referenceDate = null,): array
    {
        $bulanLabel = '';
        if ($referenceDate) {
            $original = Carbon::getLocale();
            Carbon::setLocale('id');
            $bulanLabel = strtoupper($referenceDate->translatedFormat('F Y'));
            Carbon::setLocale($original);
        }
        $title = 'LAPORAN HASIL INCOMPATIBLE BDRS INDRAMAYU BULAN ' . $bulanLabel;

        $components = collect(BloodComponent::cases())
            ->mapWithKeys(fn(BloodComponent $c) => [$c->value => $c->exportLabel()])
            ->all();
        $bloodGroups = collect(BloodGroup::cases())
            ->map(fn(BloodGroup $g) => $g->value)
            ->all();

        $dataIncompatibles = collect();

        foreach ($bloodTransfusions as $bt) {
            foreach ($bt->blood_transfusion_details as $detail) {
                if ($detail->crossmatch_result !== 'Incompatible') {
                    continue;
                }

                $tests = $this->mapDetailTests($detail->bloodTransfusionDetailTests);

                $dataIncompatibles->push((object) [
                    'public_id' => $bt->public_id,
                    'insurance_name' => optional($bt->insurance)->name,
                    'room_name' => optional($bt->room)->name,
                    'lab_number' => $bt->lab_number,
                    'order_number' => $bt->order_number,
                    'created_at' => $bt->created_at,
                    'finish_at' => $bt->finish_at,
                    'status' => $bt->status,

                    'component' => $detail->component,
                    'bag_number' => optional($detail->bloodStock)->bag_number,
                    'crossmatch_result' => $detail->crossmatch_result,
                    'crossmatch_finish_at' => $detail->crossmatch_finish_at,

                    'mayor_result' => $tests['mayor_result'],
                    'minor_result' => $tests['minor_result'],
                    'auto_control_result' => $tests['auto_control_result'],

                    'blood_component' => optional($detail->bloodPack)->blood_component,
                    'blood_group' => optional($detail->bloodPack)->blood_group,
                    'blood_rhesus' => optional($detail->bloodPack)->blood_rhesus,
                ]);
            }
        }

        $dataIncompatibles = $dataIncompatibles->values();

        return compact('title', 'components', 'bloodGroups', 'dataIncompatibles', 'startDate', 'endDate');
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
            case 'expedition-book':
                $bulanLabel = $startDate->translatedFormat('F Y');
                return 'Buku Ekspedidisi - ' . $bulanLabel . '.xlsx';
            case 'blood-request':
                $bulanLabel = $startDate->translatedFormat('F Y');
                if (!empty($params['room_name'])) {
                    return 'Laporan Permintaan Darah - ' . $params['room_name'] . ' - ' . $bulanLabel . '.xlsx';
                }
                return 'Laporan Permintaan Dropping Darah - ' . $bulanLabel . '.xlsx';
            case 'blood-stock':
                $bulanLabel = $startDate->translatedFormat('F Y');
                if (!empty($params['blood_component'])) {
                    return 'Laporan Stok Darah - ' . $params['blood_component'] . ' - ' . $bulanLabel . '.xlsx';
                }
                return 'Laporan Stok Darah - ' . $bulanLabel . '.xlsx';
            case 'incompatible':
                if (!empty($params['room_name'])) {
                    return 'Laporan Hasil Incompatible - ' . $params['room_name'] . ' - ' . $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y') . '.xlsx';
                }
                return 'Laporan Hasil Incompatible - ' . $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y') . '.xlsx';
            default:
                abort(404, "Report '{$report}' not found.");
        }
    }
    private function mapDetailTests($tests): array
    {
        $result = [
            'mayor_result'        => null,
            'minor_result'        => null,
            'auto_control_result' => null,
        ];

        foreach ($tests as $test) {
            $name = strtolower(optional($test->test)->name ?? '');

            if (str_contains($name, 'mayor')) {
                $result['mayor_result'] = $test->result;
            } elseif (str_contains($name, 'minor')) {
                $result['minor_result'] = $test->result;
            } elseif (str_contains($name, 'auto')) {
                $result['auto_control_result'] = $test->result;
            }
        }

        return $result;
    }
}
