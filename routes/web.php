<?php

use App\Http\Controllers\BloodTransfusionController;
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
    // Blood Transfusion Group Routes -> blood-transfusion.*
    // --------------------------------------------------------------------------
    Route::prefix('blood-transfusion')->name('blood-transfusion.')->controller(BloodTransfusionController::class)->group(function () {
        // ---------- Main Page ----------
        Route::get('/', 'index')->name('index');

        // ---------- Archive group routes ----------
        Route::prefix('archive')->name('archive.')->group(function () {
            Route::get('/', 'arhiveIndex')->name('index');
        });

        // ---------- Datatable group routes ----------
        Route::prefix('datatable')->name('datatable.')->group(function () {
            Route::get('blood-pack', 'datatableBloodPack')->name('blood-pack');
            Route::get('blood-request', 'datatableBloodRequest')->name('blood-request');
            Route::get('{id}/bag-requests', 'datatableListBagRequest')->name('datatable-bag-request');
        });

        // ---------- Test group routes ----------
        Route::prefix('test')->name('test.')->group(function () {
            Route::patch('{id}/update-result', 'updateTestResult')->name('update-result');
            Route::patch('{id}/update-verified-validated', 'updateTestVerifiedValidated')->name('update-verified-validated');
        });

        // ---------- CRUD ----------
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'getDataById')->name('get-data');
        Route::patch('{id}', 'update')->name('update');
        Route::delete('{id}', 'destroy')->name('destroy');

        // ---------- Checkin ----------
        Route::post('{id}/checkin', 'checkin')->name('checkin');

        // ---------- Bag Request ----------
        Route::get('{id}/bag-requests', 'datatableListBagRequest')
            ->name('datatable-bag-request');

        // ---------- Tests ----------
        Route::get('{id}/tests', 'datatableListTest')
            ->name('datatable-list-test');

        // ---------- Blood Packs ----------
        Route::patch('detail/{id}/update-stock', 'updateBagNumber')
            ->name('update-bag-number');

        Route::patch('{id}/update-blood-packs', 'updateBloodPacks')
            ->name('update-blood-packs');
    });

    // --------------------------------------------------------------------------
    // Master Group Routes -> master.*
    // --------------------------------------------------------------------------
    Route::prefix('master')->name('master.')->middleware(['role:superadmin'])->controller(MasterController::class)->group(function () {
        Route::get('{master}', 'index')->where('master', implode('|', array_keys(config('master'))))->name('index');
        Route::get('{master}/data', 'datatable')->where('master', implode('|', array_keys(config('master'))))->name('datatable');
        Route::get('{master}/data/{id}', 'getDataById')->where('master', implode('|', array_keys(config('master'))))->name('get-data');
        Route::post('{master}', 'submitData')->where('master', implode('|', array_keys(config('master'))))->name('submit-data')->middleware('throttle:master');
        Route::patch('{master}/{id}', 'editData')->where('master', implode('|', array_keys(config('master'))))->name('edit-data')->middleware('throttle:master');
        Route::delete('{master}/data/{id}', 'deleteData')->where('master', implode('|', array_keys(config('master'))))->name('delete-data')->middleware('throttle:master');
        Route::patch('{master}/{id}/restore', 'restoreData')->where('master', implode('|', array_keys(config('master'))))->name('restore-data')->middleware('throttle:master');
    });

    // --------------------------------------------------------------------------
    // Utility Group Routes -> utility.*
    // --------------------------------------------------------------------------
    Route::prefix('utility')->name('utility.')->controller(UtilityController::class)->group(function () {
        Route::get('select/{select}', 'selectData')->where('select', implode('|', array_keys(config('utility'))))->name('select-data');
        Route::get('select-special/{select}/{id}', 'selectDataSpecial')->where('select', implode('|', array_keys(config('utility'))))->name('select-data-special');
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
// ---------- System Modules Routes ----------
require __DIR__ . '/system.php';
