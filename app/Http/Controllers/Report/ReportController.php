<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportDataService;
use App\Services\Report\ReportWriteService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function __construct(
        private readonly ReportDataService $dataService,
        private readonly ReportWriteService $writeService
    ) {}

    public function index(string $report)
    {
        $modules = config('report');
        abort_unless(isset($modules[$report]), 404);

        $view = $modules[$report]['view'];
        abort_unless(view()->exists($view), 404);

        $formattedReport = Str::of($report)
            ->replace(['-', '_'], ' ')
            ->title();
        return view($view, [
            'report' => $modules[$report]['label'] ?? $formattedReport,
            'reportJS' => $report,
        ]);
    }

    public function datatable(Request $request, string $report)
    {
        return $this->dataService->datatable($report, $request);
    }

    // ---------- Fungsi export data ke Excel ----------
    public function exportExcel(string $report, Request $request)
    {
        return $this->writeService->exportExcel($report, $request);
    }
}
