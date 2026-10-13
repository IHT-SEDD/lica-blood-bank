<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonorController extends Controller
{
    // ---------- Halaman index ----------
    public function index()
    {
        return view('pages.donor.index');
    }
}
