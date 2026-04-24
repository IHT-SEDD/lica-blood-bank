<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Halaman dashboard inventory
    public function index()
    {
        return view('pages.inventory.index');
    }

    // Halaman blood stock
    public function bloodStockIndex()
    {
        return view('pages.inventory.blood-stock.index');
    }

    // Halaman stock in
    public function stockInIndex()
    {
        return view('pages.inventory.stock-in.index');
    }

    // Halaman stock out
    public function stockOutIndex()
    {
        return view('pages.inventory.stock-out.index');
    }

    // Halaman history order
    public function historyOrderIndex()
    {
        return view('pages.inventory.history-order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
