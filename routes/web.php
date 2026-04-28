<?php

use App\Http\Controllers\BloodTranfusionController;
use App\Http\Controllers\Inventory\HistoryOrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LockSessionController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\UtilityController;
use Illuminate\Support\Facades\Route;

// --------------------------------------------------------------------------
// Default Routes -> welcome
// --------------------------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('welcome');

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
            Route::get('/new-order', 'newOrderIndex')->name('new-order');
            Route::get('/data', 'historyOrderTable')->name('history-order-table');
            Route::get('/new-po-number', 'generatePoNumber')->name('generate-po-number');
            Route::post('/new-order', 'insertNewOrder')->name('insert-new-order');
            Route::delete('/data/{id}', 'deleteDataOrder')->name('delete-data-order');
            Route::patch('/data/{id}/restore', 'restoreDataOrder')->name('restore-data-order');
        });
    });

    // --------------------------------------------------------------------------
    // Blood Tranfusion Group Routes -> blood-tranfusion.*
    // --------------------------------------------------------------------------
    Route::prefix('blood-tranfusion')->name('blood-tranfusion.')->controller(BloodTranfusionController::class)->group(function () {
        // --------------------------------------------------------------------------
        // Blood Tranfusion / Home Route -> blood-tranfusion.index
        // --------------------------------------------------------------------------
        Route::get('/', 'index')->name('index');
    });

    // --------------------------------------------------------------------------
    // Master Group Routes -> master.*
    // --------------------------------------------------------------------------
    Route::prefix('master')->name('master.')->middleware(['role:superadmin'])->controller(MasterController::class)->group(function () {
        Route::get('{master}', 'index')->where('master', implode('|', array_keys(config('master'))))->name('index');
        Route::get('{master}/data', 'datatable')->where('master', implode('|', array_keys(config('master'))))->name('datatable');
        Route::get('{master}/data/{id}', 'getDataById')->where('master', implode('|', array_keys(config('master'))))->name('get-data');
        Route::post('{master}', 'submitData')->where('master', implode('|', array_keys(config('master'))))->name('submit-data');
        Route::patch('{master}/{id}', 'editData')->where('master', implode('|', array_keys(config('master'))))->name('edit-data');
        Route::delete('{master}/{id}', 'deleteData')->where('master', implode('|', array_keys(config('master'))))->name('delete-data');
        Route::patch('{master}/{id}/restore', 'restoreData')->where('master', implode('|', array_keys(config('master'))))->name('restore-data');
    });

    // --------------------------------------------------------------------------
    // Utility Group Routes -> utility.*
    // --------------------------------------------------------------------------
    Route::prefix('utility')->name('utility.')->controller(UtilityController::class)->group(function () {
        Route::get('select/{select}', 'selectData')->where('select', implode('|', array_keys(config('utility'))))->name('select-data');
        Route::get('get/{data}/{id}', 'getDataById')->name('get-data');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/ui-theme.php';
