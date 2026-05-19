<?php

namespace App\Services;

use App\Models\BloodTransfusion;
use Illuminate\Http\Request;

class PrintDataService
{
    //
    public function bloodTransfusionPrint(Request $request)
    {
        $bt =  BloodTransfusion::with([
            'patient',
            'doctor',
            'insurance',
            'details',
            'room',
            'details.bloodStock',
            'details.bloodPack',
            'details.bloodTransfusionDetailTests'
        ])->where('public_id', $request->public_id)->first();

        return $bt;
    }
}
