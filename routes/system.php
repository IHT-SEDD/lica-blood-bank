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

  Route::prefix('print')->name('print.')->group(function () {
   Route::get('/', 'printTestIndex')->name('index');
   Route::get('preview/{print}', 'printPreview')->name('preview');
   Route::get('pdf/{print}', 'downloadPDF')->name('pdf');
  });
 });
});
