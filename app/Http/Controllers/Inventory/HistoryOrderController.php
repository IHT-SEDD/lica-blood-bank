<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddNewOrderRequest;
use App\Services\Inventory\HistoryOrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class HistoryOrderController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected HistoryOrderService $service
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
            $this->service->historyOrderTable($request)
        );
    }

    // ---------- Fungsi untuk menambahkan data order ke database ----------
    public function insertNewOrder(AddNewOrderRequest $request)
    {
        $draft = $request->boolean('draft') ? 'draft' : null;
        return $this->service->insertNewOrder($request, $draft);
    }

    // ---------- Fungsi untuk membuat po number ----------
    public function generatePoNumber()
    {
        $poNumber = $this->service->generatePoNumber();
        return response()->json($poNumber);
    }

    // ---------- Fungsi untuk membuat file po ----------
    public function generatePoFile(string $poNumber)
    {
        try {
            $result = $this->service->generatePoFile($poNumber);
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
            return $this->service->previewPoFile($poNumber);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to preview PO File!'], 500);
        }
    }

    // ---------- Get Detail Order ----------
    public function detailOrderData(string $id)
    {
        try {
            $data = $this->service->getDataOrderAndLogById($id);

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
            $data = $this->service->getDataOrderByPO($poNumber);

            return response()->json($data)
                ->setEtag(md5(json_encode($data)))
                ->header('Cache-Control', 'public, max-age=600');
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data not found!'
            ], 404);
        }
    }

    // ---------- Fungsi untuk download file po ----------
    public function downloadPoFile(string $poNumber)
    {
        try {
            return $this->service->downloadPoFile($poNumber);
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
            return $this->service->printPoFile($poNumber);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found!'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to print PO File!'], 500);
        }
    }
}
