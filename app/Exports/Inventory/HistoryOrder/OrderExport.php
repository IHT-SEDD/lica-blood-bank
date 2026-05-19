<?php

namespace App\Exports\Inventory\HistoryOrder;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrderExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(protected Collection $orders) {}

    public function title(): string
    {
        return 'History Order';
    }

    public function view(): View
    {
        return view('exports.history_order.order_excel', [
            'orders' => $this->orders,
        ]);
    }
}
