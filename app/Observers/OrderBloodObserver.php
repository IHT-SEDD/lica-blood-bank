<?php

namespace App\Observers;

use App\Models\OrderBlood;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class OrderBloodObserver
{
    /**
     * Handle the OrderBlood "created" event.
     */
    public function created(OrderBlood $orderBlood)
    {
        $this->clearCache($orderBlood);
    }

    /**
     * Handle the OrderBlood "updated" event.
     */
    public function updated(OrderBlood $orderBlood)
    {
        $this->clearCache($orderBlood);
    }

    private function clearCache(Model $orderBlood)
    {
        Cache::forget("order_and_log_data_by_id:{$orderBlood->public_id}");
        Cache::forget("order_and_log_data_by_po:{$orderBlood->po_number}");
    }
}
