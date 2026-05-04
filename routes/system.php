<?php

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
});
