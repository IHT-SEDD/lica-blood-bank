<?php

namespace App\Services\Report;

use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionStatus;
use App\Models\BloodStock;
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
            case 'blood-expire':
                return $this->datatableBloodExpire($request);
            case 'expedition-book':
                return $this->datatableExpeditionBook($request);
            default:
                abort(404, "Report '{$report}' not found.");
        }
    }

    // ---------- Helper ----------
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
        $bloodPackPublicID = $request->blood_pack_public_id;
        $roomPublicID = $request->room_public_id;

        $transfusions = BloodTransfusion::withoutTrashed()
            ->where('status', BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED->value)
            ->whereBetween('blood_transfusions.created_at', [$startDate, $endDate])
            ->with(['room', 'details.bloodPack'])
            ->when($bloodPackPublicID, function ($query) use ($bloodPackPublicID) {
                $query->whereHas('details.bloodPack', function ($q) use ($bloodPackPublicID) {
                    $q->where('public_id', $bloodPackPublicID);
                });
            })
            ->when($roomPublicID, function ($query) use ($roomPublicID) {
                $query->whereHas('room', function ($q) use ($roomPublicID) {
                    $q->where('public_id', $roomPublicID);
                });
            })
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
    // ---------- Datatable Blood Expire ----------
    private function datatableBloodExpire(Request $request)
    {
        $monthYear = $request->month_year;
        $bloodComponent = $request->blood_component;

        $query = BloodStock::withoutTrashed()->with('bloodPacks')->where('blood_status', BloodStockStatus::EXPIRED);
        if (!empty($monthYear)) {
            $date = Carbon::createFromFormat('Y-m', $monthYear);
            $query->whereYear('expiry_date', $date->year)->whereMonth('expiry_date', $date->month);
        }
        if (!empty($bloodComponent)) {
            $query->whereHas('bloodPacks', function ($q) use ($bloodComponent) {
                $q->where('blood_component', $bloodComponent);
            });
        }
        $bloodStocks = $query->get();

        $grouped = $bloodStocks
            ->map(fn($stock) => [
                'expiry_date' => $stock->expiry_date instanceof \Carbon\Carbon
                    ? $stock->expiry_date->format('Y-m-d H:i:s')
                    : (string) $stock->expiry_date,
                'blood_pack_id' => $stock->blood_pack_id,
                'blood_component' => $stock->bloodPacks?->blood_component,
                'blood_group' => $stock->bloodPacks?->blood_group,
                'blood_rhesus' => $stock->bloodPacks?->blood_rhesus,
            ])
            ->groupBy(fn($row) => $row['expiry_date'] . '|' . $row['blood_pack_id'])
            ->map(function ($rows) {
                $first = $rows->first();
                return [
                    'expiry_date' => $first['expiry_date'],
                    'blood_pack_id' => $first['blood_pack_id'],
                    'blood_component' => $first['blood_component'],
                    'blood_group' => $first['blood_group'],
                    'blood_rhesus' => $first['blood_rhesus'],
                    'total' => $rows->count(),
                ];
            })
            ->values()
            ->sortBy('expiry_date')
            ->values();
        $dateTotals = $grouped
            ->groupBy('expiry_date')
            ->map(fn($rows) => $rows->sum('total'));

        $data = $grouped->map(function ($row) use ($dateTotals) {
            $row['date_grand_total'] = $dateTotals[$row['expiry_date']] ?? 0;
            return $row;
        });

        return DataTables::of($data)->toJson();
    }

       // ---------- Datatable Expedition Book ----------
    private function datatableExpeditionBook(Request $request)
    {
        $monthYear = $request->month_year;

        $query = BloodTransfusion::withoutTrashed()->with(
            ['patient', 
            'insurance',
             'room', 
             'blood_transfusion_log_activities.creator.roles',
             'details.bloodTransfusionDetailTests.test',
             'details.bloodStock.incomingBloodDetails.incomingBloods.orderBloods.vendors'
             ]
            )->where('status', BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED);
        if (!empty($monthYear)) {
            $date = Carbon::createFromFormat('Y-m', $monthYear);
            $query->whereYear('blood_request_at', $date->year)
                  ->whereMonth('blood_request_at', $date->month);
        }

        $bloodTransfusions = $query->get();
    
        $data = $bloodTransfusions
            ->flatMap(function ($transfusion) {

                return $transfusion->details->map(function ($detail) use ($transfusion) {

                    return [
                        'transfusion' => $transfusion,
                        'detail' => $detail,
                    ];
                });

            })
            ->groupBy(fn($item) => $item['detail']->blood_stock_id)
            ->map(function ($rows) {

                $first = $rows->first();
                $transfusion = $first['transfusion'];
                $detail = $first['detail'];

                $user_is_admin = $transfusion->blood_transfusion_log_activities->map(function($log){
                    $admin = $log->creator?->roles->firstWhere('name', 'Admin');
                    if ($admin) {
                        return $log->creator;
                    }
                    return null;
                })->filter()->first();

                $technician = $transfusion->blood_transfusion_log_activities->map(function($log){
                    $admin = $log->creator?->roles->firstWhere('name', 'Teknisi Bank Darah');
                    if ($admin) {
                        return $log->creator;
                    }
                    return null;
                })->filter()->first();

        
                $tests = $rows
                    ->flatMap(fn($row) => $row['detail']->bloodTransfusionDetailTests)
                    ->keyBy(fn($test) => strtolower($test->test->name ?? '') );
                
                return [

                    'tanggal' => optional(Carbon::parse($transfusion->blood_request_at))->format('Y-m-d'),

                    'asal_labu' => $detail->bloodStock?->incomingBloodDetails?->incomingBloods?->orderBloods?->vendors->name,

                    'nama_pasien' => $transfusion->patient?->name,

                    'no_medrec' => $transfusion->patient?->medrec,

                    'goldar_rhesus' =>
                        $transfusion->patient?->blood_group .
                        $transfusion->patient?->blood_rhesus,

                    'ruangan' => $transfusion->room?->name,

                    'diagnosa' => $transfusion->diagnosis,

                    'jenis_pasien' => $transfusion->insurance?->name,

                    'jenis_darah' => $detail->component,

                    'jam_penerimaan' => optional(Carbon::parse($transfusion->blood_request_at))->format('H:i'),

                    'jam_mulai' => $transfusion->checkin_time ? Carbon::parse($transfusion->checkin_time)->format('H:i') : '',

                    'jam_selesai' => optional(Carbon::parse($transfusion->finish_at))->format('H:i'),

                    'no_kantong_darah' => $detail->bloodStock?->bag_number,

                    'result_mayor' => $tests['mayor']->result ?? null,

                    'result_minor' => $tests['minor']->result ?? null,

                    'result_auto_control' => $tests['auto control']->result ?? null,

                    'result_crossmatch' => $detail?->crossmatch_result,

                    'admin' => $user_is_admin?->name,

                    'teknisi_bank_darah' => $technician?->name,
                ];

            })
            ->values();

        return DataTables::of($data)->toJson();
    }
}
