<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Inventory\StockInService;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    // ---------- Panggil semua service yang dibutuhkan :begin ----------
    public function __construct(
        protected StockInService $service
    ) {}
    // ---------- Panggil semua service yang dibutuhkan :end ----------

    // ---------- Halaman New Stock In ----------
    public function newStockInIndex()
    {
        return view('pages.inventory.sub-pages.stock-in.new-incoming-stock');
    }

    // ---------- Halaman Detail Order ----------
    public function detailStockInIndex(string $id)
    {
        return view('pages.inventory.sub-pages.stock-in.detail-incoming-stock', compact('id'));
    }
}
