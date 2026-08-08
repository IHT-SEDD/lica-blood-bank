<?php

use App\Http\Controllers\DevPlaygroundController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
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

 // --------------------------------------------------------------------------
 // Dev Playground Route Group
 // --------------------------------------------------------------------------
 Route::prefix('playground')->name('playground.')->controller(DevPlaygroundController::class)->group(function () {
  Route::get('/', 'index')->name('index');

  // Route group print test
  Route::prefix('print')->name('print.')->group(function () {
   Route::get('/', 'printTestIndex')->name('index');
   Route::get('preview/{print}', 'printPreview')->name('preview');
   Route::get('pdf/{print}', 'downloadPDF')->name('pdf');
  });

  // Route group fixing
  Route::prefix('fixing')->name('fixing.')->group(function () {
   // Fixing crossmatch result
   Route::prefix('crossmatch-result')->name('crossmatch-result.')->group(function () {
    Route::get('/', 'fixCrossmatchResult')->name('index');
    Route::patch('/{id}', 'editCrossmatchResult')->name('update');
    Route::get('/test-table', 'testDatatable')->name('test-table');
    Route::get('/data-transfusion', 'dataTransfusion')->name('data-transfusion');
    Route::get('/get-data/{id}', 'dataCrossmatch')->name('data-crossmatch');
   });

   // Fixing blood stock data
   Route::prefix('blood-stock-data')->name('blood-stock-data.')->group(function () {
    Route::get('/', 'fixBloodStockData')->name('index');
    Route::get('/data', 'testDatatable')->name('test-table');
    Route::patch('/{id}', 'editCrossmatchResult')->name('update');
   });
  });

  // Route group setting
  Route::prefix('setting')->name('setting.')->group(function () {
   // Setting - Config
   Route::prefix('config')->name('config.')->group(function () {
    // Blood Component
    Route::prefix('blood-component')->name('blood-component.')->group(function () {
     Route::get('/', 'settingConfigBloodComponentIndex')->name('index');
     Route::get('/data', 'settingConfigBloodComponentData')->name('datatable');
    });
   });

   // Fixing blood stock data
   Route::prefix('blood-stock-data')->name('blood-stock-data.')->group(function () {
    Route::get('/', 'fixBloodStockData')->name('index');
    Route::get('/data', 'testDatatable')->name('test-table');
    Route::patch('/{id}', 'editCrossmatchResult')->name('update');
   });
  });
 });
});
