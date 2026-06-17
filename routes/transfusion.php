<?php

use App\Http\Controllers\BloodTransfusionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
  Route::prefix('blood-transfusion')->name('blood-transfusion.')->controller(BloodTransfusionController::class)
    ->group(function () {
      // ---------- Main Page ----------
      Route::get('/', 'index')->name('index');

      // ---------- Archive group routes ----------
      Route::prefix('archive')->name('archive.')->group(function () {
        Route::get('/', 'arhiveIndex')->name('index');
      });

      // ---------- Datatable group routes ----------
      Route::prefix('datatable')->name('datatable.')->group(function () {
        Route::get('blood-pack', 'datatableBloodPack')->name('blood-pack');
        Route::get('blood-request', 'datatableBloodRequest')->name('blood-request');
        Route::get('{id}/bag-requests', 'datatableListBagRequest')->name('datatable-bag-request');
        Route::get('{patientId}/history-test', 'datatableListHistoryTest')->name('datatable-history-test');

        Route::prefix('archive')->name('archive.')->group(function () {
          Route::get('blood-request', 'datatableBloodRequestArchive')->name('blood-request');
          Route::get('bag-request', 'datatableBagRequestArchive')->name('bag-request');
          Route::get('test', 'datatableTestArchive')->name('test');
        });
      });

      // ---------- Detail group routes ----------
      Route::prefix('detail')->name('detail.')->group(function () {
        Route::patch('{id}/update-stock', 'updateBagNumber')->name('update-stock');

        Route::prefix('print')->name('print.')->group(function () {
          Route::get('incompatible-letter/{id}', 'printIncompatibleLetter')->name('incompatible-letter');
          Route::get('nota/{id}', 'printNota')->name('nota');
          Route::get('crossmatch-result/{id}/{btDetailID?}', 'printCrossmatchResult')->name('crossmatch-result');
          Route::get('barcode/{id}/{btDetailID?}', 'printBarcodeBlood')->name('barcode');
        });

        Route::prefix('{id}')->group(function () {
          Route::post('delete', 'deleteBloodPack')->name('delete-blood-pack');
          Route::post('hold', 'holdBloodPack')->name('hold-blood-pack');
          Route::post('accept-incompatible', 'acceptIncompatible')->name('accept-incompatible');
          Route::post('release', 'releaseBloodPack')->name('release-blood-pack');
          Route::post('release-all', 'releaseAllBloodPack')->name('release-all-blood-pack');
          Route::post('unrelease', 'unreleaseBloodPack')->name('unrelease-blood-pack');
        });
      });

      // ---------- Test group routes ----------
      Route::prefix('test')->name('test.')->group(function () {
        Route::patch('{id}/update-result', 'updateTestResult')->name('update-result');
        Route::patch('{id}/update-verified-validated', 'updateTestVerifiedValidated')->name('update-verified-validated');
        Route::post('{id}/complete', 'completeTest')->name('complete');
      });

      // ---------- CRUD ----------
      Route::post('store', 'store')->name('store');

      Route::prefix('{id}')->group(function () {
        Route::get('/', 'getDataById')->name('get-data');
        Route::get('log', 'bloodTransfusionLogData')->name('log');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'deleteBloodTransfusion')->name('delete-transfusion');

        Route::post('checkin', 'checkin')->name('checkin');
        Route::post('archive', 'archiveBloodTransfusion')->name('archive');
        Route::post('complete', 'completeTransaction')->name('complete');
        Route::post('send-result', 'sendResultToSIMRS')->name('send-result');
        Route::get('bag-requests', 'datatableListBagRequest')->name('bag-requests');
        Route::get('tests', 'datatableListTest')->name('tests');
        Route::patch('update-blood-packs', 'updateBloodPacks')->name('update-blood-packs');
      });
    });
});
