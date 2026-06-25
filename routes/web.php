<?php

use App\Http\Controllers\BloodTransfusionController;
use App\Http\Controllers\Inventory\HistoryOrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LockSessionController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\IntegrationController;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

// --------------------------------------------------------------------------
// Default Routes -> welcome
// --------------------------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('welcome');

Route::middleware('auth')->group(function () {
    // --------------------------------------------------------------------------
    // Master Group Routes -> master.*
    // --------------------------------------------------------------------------
    Route::prefix('master')->name('master.')->middleware(['role:superadmin'])->controller(MasterController::class)
        ->group(function () {
            Route::get('{master}', 'index')->where('master', implode('|', array_keys(config('master'))))->name('index');
            Route::get('{master}/data', 'datatable')->where('master', implode('|', array_keys(config('master'))))->name('datatable');
            Route::get('{master}/data/{id}', 'getDataById')->where('master', implode('|', array_keys(config('master'))))->name('get-data');
            Route::post('{master}', 'submitData')->where('master', implode('|', array_keys(config('master'))))->name('submit-data');
            Route::patch('{master}/{id}', 'editData')->where('master', implode('|', array_keys(config('master'))))->name('edit-data');
            Route::delete('{master}/data/{id}', 'deleteData')->where('master', implode('|', array_keys(config('master'))))->name('delete-data');
            Route::patch('{master}/{id}/restore', 'restoreData')->where('master', implode('|', array_keys(config('master'))))->name('restore-data');
        });

    // --------------------------------------------------------------------------
    // Report Group Routes -> report.*
    // --------------------------------------------------------------------------
    Route::prefix('report')->name('report.')->controller(ReportController::class)
        ->group(function () {
            // ---------- Page ----------
            Route::get('{report}', 'index')->where('report', implode('|', array_keys(config('report'))))->name('index');
            Route::get('{report}/data', 'datatable')->where('report', implode('|', array_keys(config('report'))))->name('datatable');

            // ---------- Export ----------
            Route::prefix('export')->name('export.')->group(function () {
                Route::get('{report}/excel', 'exportExcel')->where('report', implode('|', array_keys(config('report'))))->name('excel');
            });
        });

    // --------------------------------------------------------------------------
    // Report Group Routes -> report.*
    // --------------------------------------------------------------------------
    Route::prefix('integration')->name('integration.')->controller(IntegrationController::class)
        ->group(function () {
            // ---------- Page ----------
            Route::get('{integration}', 'index')->name('index');
            // ---------- Datatable ----------
            Route::get('{integration}/data', 'datatable')->name('datatable');
        });

    // --------------------------------------------------------------------------
    // Utility Group Routes -> utility.*
    // --------------------------------------------------------------------------
    Route::prefix('utility')->name('utility.')->controller(UtilityController::class)->group(function () {
        Route::get('select/{select}', 'selectData')->where('select', implode('|', array_keys(config('utility'))))->name('select-data');
        Route::get('select-special/{select}/{id}', 'selectDataSpecial')->where('select', implode('|', array_keys(config('utility'))))->name('select-data-special');
        Route::get('select-batch', 'selectBatchData')->where('select', implode('|', array_keys(config('utility'))))->name('select-data-batch');
        Route::get('select-manual/bdrs-number', 'selectDataBDRSNumber')->name('select-bdrs-number');
        Route::get('select-manual/order-number', 'selectDataOrderNumber')->name('select-order-number');
        Route::get('get/{data}/{id}', 'getDataById')->name('get-data');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------- Breeze Auth Routes ----------
require __DIR__ . '/auth.php';
// ---------- Simple Theme Routes ----------
require __DIR__ . '/ui-theme.php';
// ---------- Inventory Modules Routes ----------
require __DIR__ . '/inventory.php';
// ---------- Blood Transfusion Modules Routes ----------
require __DIR__ . '/transfusion.php';
// ---------- System Modules Routes ----------
require __DIR__ . '/system.php';
// ---------- Print Routes ----------
require __DIR__ . '/print.php';
