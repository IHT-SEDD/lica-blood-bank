<?php

use Illuminate\Support\Facades\Route;

// --------------------------------------------------------------------------
// UI Theme Group Routes -> ui-theme.*
// --------------------------------------------------------------------------
Route::prefix('ui-theme')->name('ui-theme.')->group(function () {

 // --------------------------------------------------------------------------
 // Form Group Routes -> ui-theme.form.*
 // --------------------------------------------------------------------------
 Route::prefix('form')->name('form.')->group(function () {
  // --------------------------------------------------------------------------
  // Form / Plugin Group Routes -> form.plugin.*
  // --------------------------------------------------------------------------
  Route::get('plugin', function () {
   return view('form.plugin');
  })->name('plugin');
 });

 // --------------------------------------------------------------------------
 // Datatable Group Routes -> ui-theme.datatable.*
 // --------------------------------------------------------------------------
 Route::prefix('datatable')->name('datatable.')->group(function () {
  // --------------------------------------------------------------------------
  // Datatable / Ajax Group Routes -> datatable.ajax.*
  // --------------------------------------------------------------------------
  Route::get('ajax', function () {
   return view('tables.datatables-ajax');
  })->name('ajax');
  // --------------------------------------------------------------------------
  // Datatable / Hide Column Group Routes -> datatable.hide-column.*
  // --------------------------------------------------------------------------
  Route::get('hide-column', function () {
   return view('tables.datatables-columns');
  })->name('hide-column');
 });
});
