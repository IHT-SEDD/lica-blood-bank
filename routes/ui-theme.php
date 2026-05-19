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
   return view('simple-theme.form.plugin');
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
   return view('simple-theme.tables.datatables-ajax');
  })->name('ajax');
  // --------------------------------------------------------------------------
  // Datatable / Hide Column Group Routes -> datatable.hide-column.*
  // --------------------------------------------------------------------------
  Route::get('hide-column', function () {
   return view('simple-theme.tables.datatables-columns');
  })->name('hide-column');
 });

 // --------------------------------------------------------------------------
 // Error Group Routes -> ui-theme.error.*
 // --------------------------------------------------------------------------
 Route::prefix('error')->name('error.')->group(function () {
  // --------------------------------------------------------------------------
  // Error / Plugin Group Routes -> error.404.*
  // --------------------------------------------------------------------------
  Route::get('404', function () {
   return view('simple-theme.error.404');
  })->name('404');
 });
});
