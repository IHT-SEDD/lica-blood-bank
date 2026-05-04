<?php

namespace App\Observers;

use App\Models\OrderBlood;
use App\Models\OrderLogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class OrderLogActivityObserver
{
    /**
     * Handle the OrderLogActivity "created" event.
     */
    public function created(OrderLogActivity $orderLogActivity)
    {
        $this->clearCache($orderLogActivity);
    }

    /**
     * Handle the OrderLogActivity "updated" event.
     */
    public function updated(OrderLogActivity $orderLogActivity)
    {
        $this->clearCache($orderLogActivity);
    }

    private function clearCache(Model $orderLogActivity)
    {
        $order = OrderBlood::where('po_number', $orderLogActivity->po_number)->first();

        if ($order) {
            Cache::forget("order_and_log_data_by_id:{$order->public_id}");
            Cache::forget("order_and_log_data_by_po:{$order->po_number}");
        }
    }
}
