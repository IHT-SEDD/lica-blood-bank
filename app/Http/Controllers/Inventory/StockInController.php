<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddNewIncomingStockRequest;
use App\Services\Inventory\StockInService;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected StockInService $service
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Halaman New Stock In ----------
    public function newStockInIndex()
    {
        return view('pages.inventory.sub-pages.stock-in.new-incoming-stock');
    }

    // ---------- Halaman Detail Order ----------
    public function detailStockInIndex(string $id)
    {
        return view('pages.inventory.sub-pages.stock-in.detail-incoming-stock', compact('id'));
    }

    // ---------- Fungsi untuk mengambil data agar ditampilkan di datatable ----------
    public function stockinTable(Request $request)
    {
        return response()->json(
            $this->service->stockinTable($request)
        );
    }

    // ---------- Fungsi untuk menambahkan data incoming stock ke database ----------
    public function insertNewStockIn(AddNewIncomingStockRequest $request)
    {
        // Delegasikan ke service sesuai method yang dipilih
        if ($request->method_add === 'manual') {
            return $this->service->insertIncomingStockByManual($request);
        }

        return $this->service->insertIncomingStockByExcel($request);
    }

    // ---------- Fungsi untuk mengambil data ----------
    public function getData(string $id)
    {
        // Panggil dan jalankan service
        $data = $this->service->getData($id);

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

    // ---------- Fungsi untuk menghapus data dari database ----------
    public function deleteDataStockIn(string $id)
    {
        return $this->service->deleteDataStockIn($id);
    }

    // ---------- Fungsi untuk memulihkan data yang udah dihapus dari database ----------
    public function restoreDataStockIn(string $id)
    {
        return $this->service->restoreDataStockIn($id);
    }
}
