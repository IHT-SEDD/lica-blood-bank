<?php

use App\Http\Controllers\DevPlaygroundController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:superadmin'])->group(function () {
 // --------------------------------------------------------------------------
 // Dev Playground Group Routes -> playground.*
 // --------------------------------------------------------------------------
 Route::prefix('playground')->name('playground.')->controller(DevPlaygroundController::class)->group(function () {
  // ---------- Halaman awal dev playground ----------
  Route::get('/', 'index')->name('index');

  // --------------------------------------------------------------------------
  // API Group Routes -> api.*
  // --------------------------------------------------------------------------
  Route::prefix('api')->name('api.')->group(function () {
   // ---------- Index ----------
   Route::get('/', 'ApiIndex')->name('index');
  });

  // // --------------------------------------------------------------------------
  // // Print Group Routes -> print.*
  // // --------------------------------------------------------------------------
  // Route::prefix('print')->name('print.')->group(function () {
  //  // ---------- Index ----------
  //  Route::get('/', 'printTestIndex')->name('index');
  //  Route::get('preview/{print}', 'printPreview')->name('preview');
  //  Route::get('pdf/{print}', 'downloadPDF')->name('pdf');
  // });

  // // --------------------------------------------------------------------------
  // // Fixing Group Routes -> fixing.*
  // // --------------------------------------------------------------------------
  // Route::prefix('fixing')->name('fixing.')->group(function () {
  //  // Fixing crossmatch result
  //  Route::prefix('crossmatch-result')->name('crossmatch-result.')->group(function () {
  //   Route::get('/', 'fixCrossmatchResult')->name('index');
  //   Route::patch('/{id}', 'editCrossmatchResult')->name('update');
  //   Route::get('/test-table', 'testDatatable')->name('test-table');
  //   Route::get('/data-transfusion', 'dataTransfusion')->name('data-transfusion');
  //   Route::get('/get-data/{id}', 'dataCrossmatch')->name('data-crossmatch');
  //  });

  //  // Fixing blood stock data
  //  Route::prefix('blood-stock-data')->name('blood-stock-data.')->group(function () {
  //   Route::get('/', 'fixBloodStockData')->name('index');
  //   Route::get('/data', 'testDatatable')->name('test-table');
  //   Route::patch('/{id}', 'editCrossmatchResult')->name('update');
  //  });
  // });

  // // --------------------------------------------------------------------------
  // // Setting Group Routes -> setting.*
  // // --------------------------------------------------------------------------
  // Route::prefix('setting')->name('setting.')->group(function () {
  //  // --------------------------------------------------------------------------
  //  // Config Group Routes -> config.*
  //  // --------------------------------------------------------------------------
  //  Route::prefix('config')->name('config.')->group(function () {
  //   // --------------------------------------------------------------------------
  //   // Blood Component Group Routes -> blood-component.*
  //   // --------------------------------------------------------------------------
  //   Route::prefix('blood-component')->name('blood-component.')->group(function () {
  //    Route::get('/', 'settingConfigBloodComponentIndex')->name('index');
  //    Route::get('/data', 'settingConfigBloodComponentData')->name('datatable');
  //   });

  //   // --------------------------------------------------------------------------
  //   // Application Group Routes -> application.*
  //   // --------------------------------------------------------------------------
  //   Route::prefix('application')->name('application.')->group(function () {
  //    Route::patch('update-mfa', 'updateMfaStatus')->name('update-mfa');
  //   });
  //  });

  //  // Fixing blood stock data
  //  Route::prefix('blood-stock-data')->name('blood-stock-data.')->group(function () {
  //   Route::get('/', 'fixBloodStockData')->name('index');
  //   Route::get('/data', 'testDatatable')->name('test-table');
  //   Route::patch('/{id}', 'editCrossmatchResult')->name('update');
  //  });
  // });
 });
});
