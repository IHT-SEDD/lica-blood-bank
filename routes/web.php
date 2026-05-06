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
        // --------------------------------------------------------------------------
        // Blood Transfusion / Home Route -> blood-transfusion.index
        // --------------------------------------------------------------------------
        Route::get('/', 'index')->name('index');
        Route::get('/datatable-blood-pack', 'datatableBloodPack')->name('datatable-blood-pack');
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
        Route::delete('{master}/data/{id}', 'deleteData')->where('master', implode('|', array_keys(config('master'))))->name('delete-data');
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

// ---------- Breeze Auth Routes ----------
require __DIR__ . '/auth.php';
// ---------- Simple Theme Routes ----------
require __DIR__ . '/ui-theme.php';
// ---------- Inventory Modules Routes ----------
require __DIR__ . '/inventory.php';
// ---------- System Modules Routes ----------
require __DIR__ . '/system.php';
