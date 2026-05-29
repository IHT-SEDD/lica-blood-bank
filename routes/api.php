<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\BloodTransfusionApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Middleware auth:sanctum, jika internal lica blood bank

// --------------------------------------------------------------------------
// Routes API V1
// --------------------------------------------------------------------------
Route::prefix('v1')->name('v1.')->group(function () {
    // ---------- Insert New Request ----------
    Route::get('/token', [ApiController::class, 'generateToken'])->name('generate-token');
    
    // --------------------------------------------------------------------------
    // Routes API Blood Transfusion
    // --------------------------------------------------------------------------
    Route::prefix('blood-transfusion')->name('blood-transfusion.')->controller(BloodTransfusionApiController::class)->group(function () {
        // ---------- Insert New Request ----------
        Route::post('/', 'newRequest')->name('new');
    });
});
