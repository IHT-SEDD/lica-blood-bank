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
        return view('pages.inventory.sub-pages.history-order.partials.new-order');
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
        return $this->service->insertNewOrder($request);
    }

    // ---------- Fungsi untuk membuat po number ----------
    public function generatePoNumber()
    {
        $poNumber = $this->service->generatePoNumber();
        return response()->json($poNumber);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
