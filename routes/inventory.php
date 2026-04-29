<?php

use App\Http\Controllers\Inventory\HistoryOrderController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
 // --------------------------------------------------------------------------
 // Inventory Group Routes -> inventory.*
 // --------------------------------------------------------------------------
 Route::prefix('inventory')->name('inventory.')->group(function () {
  // --------------------------------------------------------------------------
  // General Route Group Inventory
  // --------------------------------------------------------------------------
  Route::controller(InventoryController::class)->group(function () {
   // --------------------------------------------------------------------------
   // Inventory / Dashboard Route -> inventory.index
   // --------------------------------------------------------------------------
   Route::get('/', 'index')->name('index');

   // --------------------------------------------------------------------------
   // Inventory / Blood Stock Group Routes -> inventory.blood-stock.*
   // --------------------------------------------------------------------------
   Route::prefix('blood-stock')->name('blood-stock.')->group(function () {
    Route::get('/', 'bloodStockIndex')->name('index');
   });

   // --------------------------------------------------------------------------
   // Inventory / Stock In Group Routes -> inventory.stock-in.*
   // --------------------------------------------------------------------------
   Route::prefix('stock-in')->name('stock-in.')->group(function () {
    Route::get('/', 'stockInIndex')->name('index');
   });

   // --------------------------------------------------------------------------
   // Inventory / Stock Out Group Routes -> inventory.stock-out.*
   // --------------------------------------------------------------------------
   Route::prefix('stock-out')->name('stock-out.')->group(function () {
    Route::get('/', 'stockOutIndex')->name('index');
   });

   // --------------------------------------------------------------------------
   // Inventory / History Order Group Routes -> inventory.history-order.*
   // --------------------------------------------------------------------------
   Route::prefix('history-order')->name('history-order.')->group(function () {
    Route::get('/', 'historyOrderIndex')->name('index');
   });
  });

  // --------------------------------------------------------------------------
  // Spesific Route Group -> History Order
  // --------------------------------------------------------------------------
  Route::prefix('history-order')->name('history-order.')->controller(HistoryOrderController::class)->group(function () {
   // ---------- Static Route ----------
   Route::get('/new-order', 'newOrderIndex')->name('new-order'); // Halaman form add order
   Route::get('/detail-order/{id}', 'detailOrderIndex')->name('detail-order'); // Halaman detail order
   Route::get('/new-po-number', 'generatePoNumber')->name('generate-po-number'); // Generate PO Number

   // ---------- Datatable ----------
   Route::get('/data', 'historyOrderTable')->name('history-order-table'); // Datatable order

   // ---------- Detail data ----------
   Route::get('/data/detail/{id}', 'detailOrderData')->name('detail-order-data'); // Ambil data detail order

   // ---------- Write data order ----------
   Route::post('/new-order', 'insertNewOrder')->name('insert-new-order'); // Insert data order

   // ---------- Delete & Restore ----------
   Route::delete('/data/{id}', 'deleteDataOrder')->name('delete-data-order'); // Hapus data order
   Route::patch('/data/{id}/restore', 'restoreDataOrder')->name('restore-data-order'); // Pulihkan data order
  });
 });
});
