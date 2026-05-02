<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Inventory\BloodStockService;
use Illuminate\Http\Request;

class BloodStockController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected BloodStockService $service
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Fungsi untuk mengambil data agar ditampilkan di datatable ----------
    public function bloodStockTable(Request $request)
    {
        return response()->json(
            $this->service->bloodStockTable($request)
        );
    }

    // ---------- Fungsi untuk status label ----------
    public function bloodStockStatusLabel()
    {
        return response()->json([
            'message' => 'Success get blood stock status',
            'data' => $this->service->bloodStockStatusLabel()
        ]);
    }
}
