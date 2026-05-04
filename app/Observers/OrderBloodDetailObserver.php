<?php

namespace App\Observers;

use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class OrderBloodDetailObserver
{
    /**
     * Handle the OrderBloodDetail "created" event.
     */
    public function created(OrderBloodDetail $orderBloodDetail)
    {
        $this->clearCache($orderBloodDetail);
    }

    /**
     * Handle the OrderBloodDetail "updated" event.
     */
    public function updated(OrderBloodDetail $orderBloodDetail)
    {
        $this->clearCache($orderBloodDetail);
    }

    private function clearCache(Model $orderBloodDetail)
    {
        Cache::forget("order_and_log_data_by_id:{$orderBloodDetail->public_id}");
        Cache::forget("order_and_log_data_by_po:{$orderBloodDetail->po_number}");
    }
}
