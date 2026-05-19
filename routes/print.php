<?php

use App\Http\Controllers\PrintController;

use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::prefix('blood-transfusion-print')->name('blood-transfusion.')->group(function () {
        Route::get('/', [PrintController::class, 'bloodTransfusionPrint']);
    });
});
