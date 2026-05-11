<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrintController extends Controller
{
    //
    public function bloodTransfusionPrint()
    {
        return view('pdf.blood_transfusion.result_test');
    }
}
