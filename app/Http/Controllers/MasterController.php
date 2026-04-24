<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMasterRequest;
use App\Services\MasterService;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected MasterService $service
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Fungsi untuk menampilkan halaman master berdasarkan jenis master data :begin ----------
    public function index($master)
    {
        // ---------- Ambil data config dari master.php ----------
        $modules = config('master');
        // ---------- Lempar 404 jika jenis master tidak ditemukan ----------
        abort_unless(isset($modules[$master]), 404);
        // ---------- Ambil path file view dari config ----------
        $view = $modules[$master]['view'];
        // ---------- Lempar 404 jika path file view tidak ditemukan ----------
        abort_unless(view()->exists($view), 404);
        // ---------- Tampilkan halaman master ----------
        return view($view, compact('master'));
    }
    // ---------- Fungsi untuk menampilkan halaman master berdasarkan jenis master data :begin ----------

    // ---------- Fungsi untuk mengambil data master berdasarkan jenis master data dari service datatable :begin ----------
    public function datatable(Request $request, $master)
    {
        return response()->json(
            $this->service->datatable($master, $request)
        );
    }
    // ---------- Fungsi untuk mengambil data master berdasarkan jenis master data dari service datatable:begin ----------

    // ---------- Fungsi untuk menambahkan data ke database berdasarkan jenis master data :begin ----------
    public function submitData(StoreMasterRequest $request, $master)
    {
        return $this->service->submitData($master, $request);
    }
    // ---------- Fungsi untuk menambahkan data ke database berdasarkan jenis master data :end ----------
}
