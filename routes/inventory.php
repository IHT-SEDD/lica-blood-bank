<?php

use App\Http\Controllers\Inventory\BloodStockController;
use App\Http\Controllers\Inventory\DashboardController;
use App\Http\Controllers\Inventory\HistoryOrderController;
use App\Http\Controllers\Inventory\StockInController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
   Route::prefix('inventory')->name('inventory.')->group(function () {
      Route::get('/', [InventoryController::class, 'index'])->name('index');
      Route::get('stock-out', [InventoryController::class, 'stockOutIndex'])->name('stock-out.index');

      // --------------------------------------------------------------------------
      // Routes Group Dashboard
      // --------------------------------------------------------------------------
      Route::prefix('dashboard')->name('dashboard.')->group(function () {
         // ---------- Data Group Routes ----------
         Route::prefix('data')->name('data.')->controller(DashboardController::class)->group(function () {
            Route::get('blood-stat', 'bloodStatData')->name('blood-stat');
            Route::get('blood-stock', 'bloodStockTabe')->name('blood-stock');
         });
      });

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
         Route::prefix('data')->name('data.')->controller(HistoryOrderController::class)->group(function () {
            Route::get('/', 'historyOrderTable')->name('table'); // Datatable order
            Route::get('{id}', 'getDataOrderByID')->name('get');
            Route::delete('{id}', 'deleteOrder')->name('delete');
            Route::patch('{id}/restore', 'restoreOrder')->name('restore');
         });

         // ---------- Detail group routes ----------
         Route::prefix('detail')->name('detail.')->controller(HistoryOrderController::class)->group(function () {
            Route::get('{id}', 'detailOrderIndex')->name('index'); // Halaman detail order
            Route::get('data/{id}', 'detailOrderData')->name('data');
            Route::patch('{id}', 'updateDataOrder')->name('update');
            Route::post('set-done/{poNumber}', 'setOrderDone')->name('set-done');
         });

         // ---------- CRUD ----------
         Route::post('new-order', [HistoryOrderController::class, 'insertNewOrder'])->name('insert-new-order'); // Insert data order
      });

      // --------------------------------------------------------------------------
      // Routes Group Stock In
      // --------------------------------------------------------------------------
      Route::prefix('stock-in')->name('stock-in.')->group(function () {
         // ---------- Pages ----------
         Route::get('/', [InventoryController::class, 'stockInIndex'])->name('index');
         Route::get('/new-incoming-stock', [StockInController::class, 'newStockInIndex'])->name('new-incoming-stock');
         Route::get('/new-bag-number', [StockInController::class, 'generateBagNumber'])->name('generate-bag-number');

         // ---------- Detail ----------
         Route::prefix('detail')->name('detail.')->controller(StockInController::class)->group(function () {
            Route::get('{id}', 'detailIncomingStockIndex')->name('index');
            Route::get('/data/incoming-blood/{id}', 'incomingBloodTable')->name('incoming-blood-table');
            Route::get('/data/order-blood/{id}', 'orderBloodTable')->name('order-blood-table');
            Route::get('/data/incoming-stock/{id}', 'getIncomingStock')->name('get-incoming-stock');
            Route::get('/data/order/{id}', 'getOrderBlood')->name('get-order-blood');
         });

         // ---------- Data ----------
         Route::prefix('data')->name('data.')->controller(StockInController::class)->group(function () {
            Route::get('/', 'stockInTable')->name('stock-in-table'); // Datatable stock in
            Route::get('/get/{id}', 'getData')->name('get-incoming-stock');
            Route::get('/select/po', 'selectPO')->name('select-po');
            Route::get('/select/blood-pack/{poNumber}', 'selectBloodPack')->name('select-blood-pack');
            Route::post('/new', 'insertNewStockIn')->name('new-incoming-stock');
            Route::delete('{id}', 'deleteDataStockIn')->name('delete-incoming-stock');
            Route::patch('{id}/restore', 'restoreDataStockIn')->name('restore-incoming-stock');
         });
      });

      // --------------------------------------------------------------------------
      // Routes Group Blood Stock
      // --------------------------------------------------------------------------
      Route::prefix('blood-stock')->name('blood-stock.')->group(function () {
         // ---------- Pages ----------
         Route::get('/', [InventoryController::class, 'bloodStockIndex'])->name('index');

         // ---------- Static ----------
         Route::get('/status/label', [BloodStockController::class, 'bloodStockStatusLabel'])->name('status-label'); // Status label

         // ---------- Data ----------
         Route::prefix('data')->name('data.')->controller(BloodStockController::class)->group(function () {
            Route::get('/', 'bloodStockTable')->name('table'); // Datatable
            Route::get('/select/po', 'selectPO')->name('select-po');
            Route::post('/new', 'insertNewBloodStock')->name('new');
         });

         // ---------- Detail group routes ----------
         Route::prefix('detail')->name('detail.')->controller(BloodStockController::class)->group(function () {
            Route::get('{id}', 'detailBloodStockIndex')->name('index');
            Route::get('data/{id}', 'detailStockBloodDataTable')->name('table');
            Route::get('log/{id}', 'stockBloodLogData')->name('log');
            Route::get('get-data/{id}', 'getDataDetailStockBlood')->name('get-data');
            Route::get('print-barcode-lica/{id}', 'printBarcodeLicaBloodStock')->name('print-barcode-lica');
            Route::get('download-barcode-lica/{id}', 'downloadBarcodeLicaBloodStock')->name('download-barcode-lica');
            Route::delete('data/{id}', 'deleteBloodStockData')->name('delete');
            Route::delete('data/{id}/permanent', 'permanentDeleteBloodStockData')->name('permanent-delete');
            Route::patch('data/{id}', 'editBloodStockData')->name('edit');
            Route::patch('data/{id}/restore', 'restoreBloodStockData')->name('restore');
         });
      });
   });
});
