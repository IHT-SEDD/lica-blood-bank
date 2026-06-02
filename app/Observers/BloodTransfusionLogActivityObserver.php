<?php

namespace App\Observers;

use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionLogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BloodTransfusionLogActivityObserver
{
    /**
     * Handle the BloodTransfusionLogActivity "created" event.
     */
    public function created(BloodTransfusionLogActivity $bloodTransfusionLogActivity)
    {
        $this->clearCache($bloodTransfusionLogActivity);
    }

    /**
     * Handle the BloodTransfusionLogActivity "updated" event.
     */
    public function updated(BloodTransfusionLogActivity $bloodTransfusionLogActivity)
    {
        $this->clearCache($bloodTransfusionLogActivity);
    }

    private function clearCache(Model $bloodTransfusionLogActivity)
    {
        $bloodTransfusion = BloodTransfusion::where('public_id', $bloodTransfusionLogActivity->blood_transfusion_public_id)->first();

        if ($bloodTransfusion) {
            Cache::forget("blood_transfusion_log_data:{$bloodTransfusion->blood_transfusion_public_id}");
        }
    }
}
