<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\BloodTransfusionApiController;
use App\Http\Controllers\API\EmsifaApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Middleware auth:sanctum, jika internal lica blood bank

// --------------------------------------------------------------------------
// Routes API V1
// --------------------------------------------------------------------------
Route::prefix('v1')->name('v1.')->group(function () {
    // ---------- Generate Token ----------
    Route::get('/token', [ApiController::class, 'generateToken'])->name('Generate Token');

    // --------------------------------------------------------------------------
    // Routes API Blood Transfusion
    // --------------------------------------------------------------------------
    Route::prefix('blood-transfusion')->name('Blood Transfusion.')->controller(BloodTransfusionApiController::class)->group(function () {
        // ---------- Check Connection ----------
        Route::get('/check-connection', 'checkConnection')->name('Check Connection');
        // ---------- Insert New Request ----------
        Route::post('/', 'newRequest')->name('New Request');
        // ----------- Send Result ----------------
        Route::post('/send-result/{orderNumber}', 'sendResult')->name('Send Result');
    });

    // --------------------------------------------------------------------------
    // EMSIFA Group Routes -> emsifa.*
    // --------------------------------------------------------------------------
    Route::prefix('emsifa')->name('emsifa.')->controller(EmsifaApiController::class)->group(function () {
        // ---------- Check Connection ----------
        Route::get('/check-connection', 'checkConnection')->name('Check Connection');
    });
});
