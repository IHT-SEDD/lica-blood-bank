<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddNewOrderRequest;
use App\Services\Inventory\HistoryOrder\HistoryOrderDataService;
use App\Services\Inventory\HistoryOrder\HistoryOrderWriteService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class HistoryOrderController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected HistoryOrderDataService $dataService,
        protected HistoryOrderWriteService $writeService,
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Halaman New Order ----------
    public function newOrderIndex()
    {
        return view('pages.inventory.sub-pages.history-order.new-order');
    }

    // ---------- Halaman Detail Order ----------
    public function detailOrderIndex(string $id)
    {
        return view('pages.inventory.sub-pages.history-order.detail-order', compact('id'));
    }

    // ---------- Fungsi untuk mengambil data order agar ditampilkan di datatable ----------
    public function historyOrderTable(Request $request)
    {
        return response()->json(
            $this->dataService->historyOrderTable($request)
        );
    }

    // ---------- Fungsi untuk menambahkan data order ke database ----------
    public function insertNewOrder(AddNewOrderRequest $request)
    {
        $draft = $request->boolean('draft') ? 'draft' : null;
        return $this->writeService->insertNewOrder($request, $draft);
    }

    // ---------- Fungsi untuk update data order ----------
    public function updateDataOrder(Request $request, string $id)
    {
        try {
            $result = $this->writeService->updateDataOrder($request, $id);
            return $result;
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to update order data!'], 500);
        }
    }

    // ---------- Fungsi untuk membuat po number ----------
    public function generatePoNumber()
    {
        $poNumber = $this->writeService->generatePoNumber();
        return response()->json($poNumber);
    }

    // ---------- Fungsi untuk membuat file po ----------
    public function generatePoFile(string $poNumber)
    {
        try {
            $result = $this->writeService->generatePoFile($poNumber);
            return $result;
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to generate PO File!'], 500);
        }
    }

    // ---------- Fungsi untuk melihat file po ----------
    public function previewPoFile(string $poNumber)
    {
        try {
            return $this->dataService->previewPoFile($poNumber);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to preview PO File!',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    // ---------- Fungsi untuk download file po ----------
    public function downloadPoFile(string $poNumber)
    {
        try {
            return $this->writeService->downloadPoFile($poNumber);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to download PO File!'], 500);
        }
    }

    // ---------- Fungsi untuk print file po ----------
    public function printPoFile(string $poNumber)
    {
        try {
            return $this->writeService->printPoFile($poNumber);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to print PO File!'], 500);
        }
    }

    // ---------- Get Detail Order ----------
    public function detailOrderData(string $id)
    {
        try {
            $data = $this->dataService->getDataOrderAndLogById($id);

            return response()->json($data)
                ->setEtag(md5(json_encode($data)))
                ->header('Cache-Control', 'public, max-age=600');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data not found!'
            ], 404);
        }
    }

    // ---------- Get Data by PO ----------
    public function getDataOrderByPO(string $poNumber)
    {
        try {
            $data = $this->dataService->getDataOrderByPO($poNumber);

            return response()->json($data)
                ->setEtag(md5(json_encode($data)))
                ->header('Cache-Control', 'public, max-age=600');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data not found!'
            ], 404);
        }
    }

    // ---------- Get Data by ID ----------
    public function getDataOrderByID(string $id)
    {
        $data = $this->dataService->getDataOrderByID($id);
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

    // ---------- Fungsi untuk mengubah status order ke done ----------
    public function setOrderDone(string $poNumber)
    {
        try {
            $result = $this->writeService->setOrderDone($poNumber);
            return $result;
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to set order to done!'], 500);
        }
    }

    // ---------- Fungsi untuk menghapus order ----------
    public function deleteOrder(string $id)
    {
        try {
            $result = $this->writeService->deleteOrder($id);
            return $result;
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to delete order!'], 500);
        }
    }
}
