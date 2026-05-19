<?php

namespace App\Http\Controllers;

use App\Services\PrintDataService;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    //
    public function __construct(
        protected PrintDataService $service
    ) {}

    public function bloodTransfusionPrint(Request $request)
    {
        $data = $this->service->bloodTransfusionPrint($request);
        // dd($data);
        return view('pdf.blood_transfusion.result_test', compact('data'));
    }
}
