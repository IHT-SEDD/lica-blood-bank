<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddNewBloodStockRequest;
use App\Services\Inventory\BloodStock\BloodStockDataService;
use App\Services\Inventory\BloodStock\BloodStockAddService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BloodStockController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected BloodStockAddService $serviceAdd,
        protected BloodStockDataService $dataService,
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Fungsi untuk mengambil data agar ditampilkan di datatable ----------
    public function bloodStockTable(Request $request)
    {
        return response()->json(
            $this->dataService->bloodStockTable($request)
        );
    }

    // ---------- Fungsi untuk status label ----------
    public function bloodStockStatusLabel()
    {
        return response()->json([
            'message' => 'Success get blood stock status',
            'data' => $this->dataService->bloodStockStatusLabel()
        ]);
    }

    // ---------- Fungsi untuk menambahkan data blood stock ke database ----------
    public function insertNewBloodStock(AddNewBloodStockRequest $request)
    {
        if ($request->method_add === 'manual') {
            return $this->serviceAdd->insertBloodStockByManual($request);
        }
        return $this->serviceAdd->insertBloodStockByScan($request);
    }

    // ---------- Halaman Detail Stock ----------
    public function detailBloodStockIndex(string $id)
    {
        return view('pages.inventory.sub-pages.blood-stock.detail-stock', compact('id'));
    }

    // ---------- Stock blood data detail table ----------
    public function detailStockBloodDataTable(Request $request, string $id)
    {
        return response()->json(
            $this->dataService->detailStockBloodDataTable($request, $id)
        );
    }

    // ---------- Stock blood data by id ----------
    public function getDataDetailStockBlood(string $id)
    {
        // Panggil dan jalankan service
        $data = $this->dataService->getDataDetailStockBlood($id);

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

    // ---------- Fungsi untuk select po ----------
    public function selectPO(Request $request)
    {
        return response()->json(
            $this->dataService->selectPO($request)
        );
    }

    // ---------- Get Log Data ----------
    public function stockBloodLogData(string $id)
    {
        try {
            $data = $this->dataService->getDataLogById($id);
            return response()->json($data)
                ->setEtag(md5(json_encode($data)))
                ->header('Cache-Control', 'public, max-age=600');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data not found!'
            ], 404);
        }
    }
}
