<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Inventory\Dashboard\DashboardDataService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected DashboardDataService $dataService,
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Fungsi ambil data untuk blood stat ----------
    public function bloodStatData()
    {
        try {
            return response()->json($this->dataService->bloodStatData());
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to fetch blood stat data!'], 500);
        }
    }

    // ---------- Fungsi ambil data untuk blood stock table ----------
    public function bloodStockTabe(Request $request)
    {
        return response()->json(
            $this->dataService->bloodStockTable($request)
        );
    }
}
