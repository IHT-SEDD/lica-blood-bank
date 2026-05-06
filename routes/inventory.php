<?php

use App\Http\Controllers\Inventory\BloodStockController;
use App\Http\Controllers\Inventory\HistoryOrderController;
use App\Http\Controllers\Inventory\StockInController;
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
   Route::get('/get-data/{poNumber}', 'getDataOrderByPO')->name('get-data-by-po-number'); // Get data by PO Number

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

  // --------------------------------------------------------------------------
  // Spesific Route Group -> Stock In
  // --------------------------------------------------------------------------
  Route::prefix('stock-in')->name('stock-in.')->controller(StockInController::class)->group(function () {
   // ---------- Static Route ----------
   Route::get('/new-incoming-stock', 'newStockInIndex')->name('new-incoming-stock');
   Route::get('/new-bag-number', 'generateBagNumber')->name('generate-bag-number');
   Route::get('/get-data/{id}', 'getData')->name('get-data');

   // ---------- Datatable ----------
   Route::get('/data', 'stockInTable')->name('stock-in-table'); // Datatable stock in

   // ---------- Detail group routes ----------
   Route::prefix('detail')->group(function () {
    Route::get('incoming-stock/{id}', 'detailIncomingStockIndex')->name('detail-incoming-stock');
    Route::get('/data/incoming-blood/{id}', 'incomingBloodTable')->name('incoming-blood-table');
    Route::get('/data/order-blood/{id}', 'orderBloodTable')->name('order-blood-table');
    Route::get('/data/incoming-stock/{id}', 'getIncomingStock')->name('get-incoming-stock');
    Route::get('/data/order/{id}', 'getOrderBlood')->name('get-order-blood');
   });

   // ---------- Write data ----------
   Route::post('/new-incoming-stock', 'insertNewStockIn')->name('insert-new-incoming-stock');

   // ---------- Delete & Restore ----------
   Route::delete('/data/{id}', 'deleteDataStockIn')->name('delete-data-incoming-stock');
   Route::patch('/data/{id}/restore', 'restoreDataStockIn')->name('restore-data-incoming-stock');
  });

  // --------------------------------------------------------------------------
  // Spesific Route Group -> Blood Stock
  // --------------------------------------------------------------------------
  Route::prefix('blood-stock')->name('blood-stock.')->controller(BloodStockController::class)->group(function () {
   // ---------- Static Route ----------
   Route::get('/status/label', 'bloodStockStatusLabel')->name('blood-stock-status-label'); // Status label

   // ---------- Datatable ----------
   Route::get('/data', 'bloodStockTable')->name('blood-stock-table'); // Datatable

   // ---------- Detail group routes ----------
   Route::prefix('detail')->group(function () {
    Route::get('{id}', 'detailBloodStockIndex')->name('detail-blood-stock');
    Route::get('/data/{id}', 'detailStockBloodDataTable')->name('stock-blood-table');
    Route::get('/get-data/{id}', 'getDataDetailStockBlood')->name('get-stock-blood');
    Route::get('/print-barcode-lica/{id}', 'printBarcodeLicaBloodStock')->name('print-barcode-lica-stock-blood');
    Route::get('/download-barcode-lica/{id}', 'downloadBarcodeLicaBloodStock')->name('download-barcode-lica-stock-blood');
    Route::delete('/data/{id}', 'deleteStockBloodData')->name('delete-stock-blood');
    Route::patch('/data/{id}/restore', 'restoreStockBloodData')->name('restore-stock-blood');
   });

   // ---------- Write data ----------
   Route::post('/new-blood-stock', 'insertNewBloodStock')->name('insert-new-blood-stock');
  });
 });
});
