<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // ---------- Halaman dashboard inventory ----------
    public function index()
    {
        return view('pages.inventory.index');
    }

    // ---------- Halaman blood stock inventory ----------
    public function bloodStockIndex()
    {
        return view('pages.inventory.sub-pages.blood-stock.index');
    }

    // ---------- Halaman stock in inventory ----------
    public function stockInIndex()
    {
        return view('pages.inventory.sub-pages.stock-in.index');
    }

    // ---------- Halaman stock out inventory ----------
    public function stockOutIndex()
    {
        return view('pages.inventory.sub-pages.stock-out.index');
    }

    // ---------- Halaman history order inventory ----------
    public function historyOrderIndex()
    {
        return view('pages.inventory.sub-pages.history-order.index');
    }

    // ---------- Halaman destroy blood inventory ----------
    public function destroyBloodIndex()
    {
        return view('pages.inventory.sub-pages.destroy-blood.index');
    }
}
