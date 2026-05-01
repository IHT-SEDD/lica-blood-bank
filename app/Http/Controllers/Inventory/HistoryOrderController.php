<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AddNewOrderRequest;
use App\Services\Inventory\HistoryOrderService;
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

    // ---------- Halaman Detail Order ----------
    public function detailOrderData(string $id)
    {
        return response()->json(
            $this->service->getDataOrderAndLogById($id)
        );
    }

    // ---------- Get Data by PO ----------
    public function getDataOrderByPO(string $poNumber)
    {
        return response()->json(
            $this->service->getDataOrderByPO($poNumber)
        );
    }
}
