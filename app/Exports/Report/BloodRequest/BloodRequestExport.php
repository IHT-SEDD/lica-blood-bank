<?php

namespace App\Exports\Report\BloodRequest;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class BloodRequestExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $reportData) {}

    public function title(): string
    {
        return 'LAPORAN PERMINTAAN DROPPING DARAH BDRS INDRAMAYU';
    }

    public function view(): View
    {
        return view('exports.report.blood_request_excel', $this->reportData);
    }
}
