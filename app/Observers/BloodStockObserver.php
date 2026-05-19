<?php

namespace App\Observers;

use App\Models\BloodStock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BloodStockObserver
{
    /**
     * Handle the BloodStock "created" event.
     */
    public function created(BloodStock $bloodStock)
    {
        $this->clearCache($bloodStock);
    }

    /**
     * Handle the BloodStock "updated" event.
     */
    public function updated(BloodStock $bloodStock)
    {
        $this->clearCache($bloodStock);
    }

    private function clearCache(Model $bloodStock)
    {
        $bloodPack = $bloodStock->bloodPacks;

        if (!$bloodPack) {
            return;
        }

        Cache::forget(
            "blood_stock_log_data:{$bloodPack->public_id}"
        );
    }
}
