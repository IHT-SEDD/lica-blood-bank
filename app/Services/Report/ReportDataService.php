<?php

namespace App\Services\Report;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class ReportDataService
{
    public function datatable(string $report, Request $request)
    {
        $modules = $this->getReportConfig($report);
        $modelClass = $modules['model'];
        $query = $this->getReportData($report);

        $this->applyDateFilter($query, $request);
        $this->applyReportFilter($query, $report, $request);

        if ($request->filled('search')) {
            $search = $request->search;
            $columns = $this->getSearchableColumns($modelClass);

            $query->where(function ($q) use ($search, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('sort_by')) {
            $query->orderBy(
                $request->sort_by,
                $request->sort_dir ?? 'asc'
            );
        }

        return $query->paginate($request->filled('per_page', 50));
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
    private function getReportData(?string $report): Builder
    {
        switch ($report) {
            case 'blood-usage':
                return '';
                break;
            case 'blood-expire':
                return '';
                break;
            case 'blood-request':
                return '';
                break;
            default:
                abort(404, "Report query for '{$report}' is not defined.");
        }
    }
    protected function applyDateFilter(Builder $query, Request $request): void
    {
        $start = $request->start_date;
        $end = $request->end_date;
        $dateField = $request->input('date_field', 'created_at');

        if (!$start || !$end) {
            return;
        }

        try {
            $startDate = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();

            $table = $query->getModel()->getTable();

            // Fallback ke created_at jika kolom tidak ada
            if (!Schema::hasColumn($table, $dateField)) {
                $dateField = 'created_at';
            }

            if (Schema::hasColumn($table, $dateField)) {
                $query->whereBetween($dateField, [$startDate, $endDate]);
            }
        } catch (\Exception $e) {
            logger()->error('Date filter error: ' . $e->getMessage());
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

            case 'blood-expire':
                if ($request->filled('component_id')) {
                    $query->where('component_id', $request->component_id);
                }
                if ($request->filled('blood_group')) {
                    $query->where('blood_group', $request->blood_group);
                }
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                break;

            case 'blood-request':
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('room_id')) {
                    $query->where('room_id', $request->room_id);
                }
                if ($request->filled('blood_group')) {
                    $query->where('blood_group', $request->blood_group);
                }
                break;
        }
    }
}
