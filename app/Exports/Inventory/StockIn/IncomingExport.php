<?php

namespace App\Exports\Inventory\StockIn;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class IncomingExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(protected Collection $incomings) {}

    public function title(): string
    {
        return 'Incoming Stock';
    }

    public function view(): View
    {
        return view('exports.stock_in.incoming_excel', [
            'incomings' => $this->incomings,
        ]);
    }
}
