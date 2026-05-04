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

    // --------------------------------------------------------------------------
    // Set Locale Route
    // --------------------------------------------------------------------------
    Route::post('/language/switch', function (Illuminate\Http\Request $request) {
        $lang = $request->input('lang');

        if (!in_array($lang, ['en', 'id'])) {
            $lang = 'en';
        }

        session(['locale' => $lang]);

        return response()->json(['success' => true, 'locale' => $lang]);
    })->name('language.switch');
});

// ---------- Breeze Auth Routes ----------
require __DIR__ . '/auth.php';
// ---------- Simple Theme Routes ----------
require __DIR__ . '/ui-theme.php';
// ---------- Inventory Modules Routes ----------
require __DIR__ . '/inventory.php';
// ---------- Demo App Routes ----------
require __DIR__ . '/demo.php';
