<?php

namespace App\Exports\Inventory\BloodStock;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class BloodStockExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(protected Collection $bloodStocks) {}

    public function title(): string
    {
        return 'Blood Stock';
    }

    public function view(): View
    {
        return view('exports.blood_stock.blood_stock_excel', [
            'bloodStocks' => $this->bloodStocks,
        ]);
    }
}
