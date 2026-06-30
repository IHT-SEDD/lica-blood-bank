<?php

namespace App\Exports\Report\ExpeditionBook;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class ExpeditionBookExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $reportData) {}

    public function title(): string
    {
        return 'Laporan Buku Ekspedisi';
    }

    public function view(): View
    {
        return view('exports.report.expedition_book_excel', $this->reportData);
    }
}
