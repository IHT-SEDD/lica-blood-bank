<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddNewBloodDestroyRequest;
use App\Services\Inventory\DestroyBlood\DestroyBloodAddService;
use App\Services\Inventory\DestroyBlood\DestroyBloodDataService;
use App\Services\Inventory\DestroyBlood\DestroyBloodWriteService;
use Illuminate\Http\Request;

class DestroyBloodController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan ----------
    public function __construct(
        protected DestroyBloodAddService $serviceAdd,
        protected DestroyBloodDataService $dataService,
        protected DestroyBloodWriteService $writeService,
    ) {}

    // ---------- Fungsi untuk mengambil data agar ditampilkan di datatable ----------
    public function destroyBloodTable(Request $request)
    {
        return response()->json(
            $this->dataService->bloodDestroyTable($request)
        );
    }

    // ---------- Fungsi untuk menambahkan data ke database ----------
    public function insertNewBloodDestroy(AddNewBloodDestroyRequest $request)
    {
        if ($request->method_add === 'manual') {
            return $this->serviceAdd->insertBloodDestroyByManual($request);
        }
        return $this->serviceAdd->insertBloodDestroyByScan($request);
    }

    // ---------- Fungsi untuk select po ----------
    public function selectBagNumber(Request $request)
    {
        return response()->json(
            $this->dataService->selectBagNumber($request)
        );
    }

    // ---------- Fungsi untuk ambil data ----------
    public function getDataDestroyBloodById(string $id)
    {
        $data = $this->dataService->getDataDestroyBloodById($id);
        if (!$data) {
            return response()->json([
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Data fetched succesfully!',
            'data' => $data
        ]);
    }

    // ---------- Fungsi untuk menghapus destroy blood ----------
    public function deleteDestroyBloodData(string $id)
    {
        return $this->writeService->deleteData($id);
    }

    // ---------- Fungsi untuk menghapus permanen destroy blood ----------
    public function permanentDeleteDestroyBloodData(string $id)
    {
        return $this->writeService->permanentDeleteData($id);
    }

    // ---------- Fungsi untuk memulihkan destroy blood ----------
    public function restoreDestroyBloodData(string $id)
    {
        return $this->writeService->restoreData($id);
    }

    // ---------- Fungsi untuk undestroy blood ----------
    public function unDestroyBloodData(string $id)
    {
        return $this->writeService->undestroyData($id);
    }
}
