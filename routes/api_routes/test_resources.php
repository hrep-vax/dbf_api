<?php

use App\Http\Controllers\TestResourceController;

/** GET    /api/test-resources    Fetch all test resources    Private **/
Route::get('', [TestResourceController::class, 'index'])->name('fetch-test-resources');

/** POST    /api/test-resources    Store test resource    Private **/
Route::post('', [TestResourceController::class, 'store'])->name('store-test-resource');

/** GET    /api/test-resources/{id}    Show test resource    Private **/
Route::get('/{id}', [TestResourceController::class, 'show'])->name('show-test-resource');

/** PUT    /api/test-resources/{id}    Show test resource    Private **/
Route::put('/{id}', [TestResourceController::class, 'update'])->name('update-test-resource');

/** DELETE    /api/test-resources/{id}    Show test resource    Private **/
Route::delete('/{id}', [TestResourceController::class, 'destroy'])->name('delete-test-resource');