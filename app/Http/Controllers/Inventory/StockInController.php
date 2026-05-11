<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddNewIncomingStockRequest;
use App\Services\Inventory\StockIn\StockInAddService;
use App\Services\Inventory\StockIn\StockInDataService;
use App\Services\Inventory\StockIn\StockInWriteService;
use App\Services\Inventory\StockInDetailService;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected StockInWriteService $writeService,
        protected StockInDataService $dataService,
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
            $this->dataService->stockinTable($request)
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
        $data = $this->dataService->getData($id);

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
        return $this->writeService->deleteDataStockIn($id);
    }

    // ---------- Fungsi untuk memulihkan data yang udah dihapus dari database ----------
    public function restoreDataStockIn(string $id)
    {
        return $this->writeService->restoreDataStockIn($id);
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

    // ---------- Fungsi untuk select blood pack ----------
    public function selectBloodPack(Request $request, string $poNumber)
    {
        return response()->json(
            $this->dataService->selectBloodPack($request, $poNumber)
        );
    }

    // ---------- Fungsi untuk select po ----------
    public function selectPO(Request $request)
    {
        return response()->json(
            $this->dataService->selectPO($request)
        );
    }
}
