<?php

namespace App\Exports\Report\BloodExpire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportBloodExpireExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $reportData) {}

    public function title(): string
    {
        return 'Laporan Darah Kadaluarsa';
    }

    public function view(): View
    {
        return view('exports.report.blood_expire_excel', $this->reportData);
    }
}
