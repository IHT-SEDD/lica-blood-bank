<?php

namespace App\Http\Controllers;

use App\Http\Requests\Master\EditMasterRequest;
use App\Http\Requests\Master\StoreMasterRequest;
use App\Services\MasterService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $formattedMaster = Str::of($master)
            ->replace(['-', '_'], ' ')
            ->title();
        // ---------- Tampilkan halaman master ----------
        return view($view, [
            'master' => $formattedMaster,
            'masterJS' => $master,
        ]);
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

    // ---------- Fungsi untuk mengambil data data ke database berdasarkan jenis master data dan id data :begin ----------
    public function getDataById($master, $id)
    {
        // Panggil dan jalankan service
        $data = $this->service->getDataById($master, $id);

        // Lempar data not found
        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        // Lempar data ke frontend
        return response()->json([
            'message' => 'Data fetched succesfully!',
            'data' => $data
        ]);
    }
    // ---------- Fungsi untuk mengambil data data ke database berdasarkan jenis master data dan id data :end ----------

    // ---------- Fungsi untuk memperbaharui data dari database berdasarkan jenis master data dan id data :begin ----------
    public function editData(EditMasterRequest $request, $master, $id)
    {
        return $this->service->editData($master, $request, $id);
    }
    // ---------- Fungsi untuk memperbaharui data dari database berdasarkan jenis master data dan id data :end ----------
}
