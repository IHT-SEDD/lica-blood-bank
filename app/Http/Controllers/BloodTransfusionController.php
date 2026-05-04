<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BloodTransfusionController extends Controller
{
    // ---------- Halaman index ----------
    public function index()
    {
        return view('pages.blood-transfusion.index');
    }
}
