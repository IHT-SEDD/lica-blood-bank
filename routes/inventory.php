<?php

use App\Http\Controllers\Inventory\BloodStockController;
use App\Http\Controllers\Inventory\HistoryOrderController;
use App\Http\Controllers\Inventory\StockInController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
 Route::prefix('inventory')->name('inventory.')->group(function () {
  // ---------- Inventory Index Dashboard ----------
  Route::get('/', [InventoryController::class, 'index'])->name('index');

  // ---------- Inventory Index Blood Stock ----------
  Route::get('blood-stock', [InventoryController::class, 'bloodStockIndex'])->name('blood-stock.index');

  // ---------- Inventory Index Stock In ----------
  Route::get('stock-in', [InventoryController::class, 'stockInIndex'])->name('stock-in.index');

  // ---------- Inventory Index Stock Out ----------
  Route::get('stock-out', [InventoryController::class, 'stockOutIndex'])->name('stock-out.index');

  // --------------------------------------------------------------------------
  // Routes Group History Order
  // --------------------------------------------------------------------------
  Route::prefix('history-order')->name('history-order.')->group(function () {
   // ---------- Pages ----------
   Route::get('/', [InventoryController::class, 'historyOrderIndex'])->name('index');
   Route::get('new-order', [HistoryOrderController::class, 'newOrderIndex'])->name('new-order'); // Halaman form add order
   Route::get('new-po-number', [HistoryOrderController::class, 'generatePoNumber'])->name('generate-po-number');
   Route::get('get-data/{poNumber}', [HistoryOrderController::class, 'getDataOrderByPO'])->name('get-data-by-po-number'); // Get data by PO Number

   // ---------- PO File Group Routes ----------
   Route::prefix('po-file')->name('po-file.')->controller(HistoryOrderController::class)->group(function () {
    Route::post('generate/{poNumber}', 'generatePoFile')->name('generate'); // Generate PO File
    Route::get('preview/{poNumber}', 'previewPoFile')->middleware(['role:superadmin'])->name('preview');
    Route::get('download/{poNumber}', 'downloadPoFile')->name('download');
    Route::get('print/{poNumber}', 'printPoFile')->name('print');
   });

   // ---------- Datatable ----------
   Route::get('data', [HistoryOrderController::class, 'historyOrderTable'])->name('table'); // Datatable order

   // ---------- Detail group routes ----------
   Route::prefix('detail')->name('detail.')->controller(HistoryOrderController::class)->group(function () {
    Route::get('{id}', 'detailOrderIndex')->name('index'); // Halaman detail order
    Route::get('data/{id}', 'detailOrderData')->name('data');
   });

   // ---------- CRUD ----------
   Route::post('new-order', [HistoryOrderController::class, 'insertNewOrder'])->name('insert-new-order'); // Insert data order
   Route::delete('data/{id}', [HistoryOrderController::class, 'deleteDataOrder'])->name('delete-data');
   Route::patch('data/{id}/restore', [HistoryOrderController::class, 'restoreDataOrder'])->name('restore-data');
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
