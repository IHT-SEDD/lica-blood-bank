<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddNewIncomingStockRequest;
use App\Services\Inventory\StockInAddService;
use App\Services\Inventory\StockInService;
use App\Services\Inventory\StockInDetailService;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected StockInService $service,
        protected StockInAddService $serviceAdd,
        protected StockInDetailService $serviceDetail
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Halaman New Stock In ----------
    public function newStockInIndex()
    {
        return view('pages.inventory.sub-pages.stock-in.new-incoming-stock');
    }

    // ---------- Halaman Detail Order ----------
    public function detailIncomingStockIndex(string $id)
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

    // ---------- Fungsi untuk mengambil data agar ditampilkan di datatable incoming blood detail ----------
    public function incomingBloodTable(Request $request, string $id)
    {
        return response()->json(
            $this->serviceDetail->incomingStockTable($request, $id)
        );
    }

    // ---------- Fungsi untuk mengambil data agar ditampilkan di datatable order blood detail ----------
    public function orderBloodTable(Request $request, string $id)
    {
        return response()->json(
            $this->serviceDetail->orderDataTable($request, $id)
        );
    }

    // ---------- Fungsi untuk menambahkan data incoming stock ke database ----------
    public function insertNewStockIn(AddNewIncomingStockRequest $request)
    {
        // Delegasikan ke service sesuai method yang dipilih
        if ($request->method_add === 'manual') {
            return $this->serviceAdd->insertIncomingStockByManual($request);
        }

        return $this->serviceAdd->insertIncomingStockByExcel($request);
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

    // ---------- Fungsi untuk mengambil data incoming stock ----------
    public function getIncomingStock(string $id)
    {
        return $this->serviceDetail->getDataIncoming($id);
    }

    // ---------- Fungsi untuk mengambil data order blood ----------
    public function getOrderBlood(string $id)
    {
        return $this->serviceDetail->getDataOrder($id);
    }
}
