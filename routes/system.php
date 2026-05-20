<?php

use App\Http\Controllers\TestingController;
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
 // Testing Route Group
 // --------------------------------------------------------------------------
 Route::prefix('testing')->name('testing.')->controller(TestingController::class)->group(function () {
  Route::get('/', 'index')->name('index');
  Route::get('preview/{print}', 'printPreview')->name('preview');
 });
});
