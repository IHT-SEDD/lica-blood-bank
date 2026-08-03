<?php

namespace App\Exports\Report\BloodStock;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportBloodStockExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $reportData) {}

    public function title(): string
    {
        return 'Laporan Stok Darah';
    }

    public function view(): View
    {
        return view('exports.report.blood_stock_excel', $this->reportData);
    }
}
