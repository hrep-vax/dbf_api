<?php

use App\Http\Controllers\DBFController;

/** GET    /api/profile/me    Show current auth user's profile info    Private **/
Route::middleware('auth:sanctum')->get('dbf-view', [DBFController::class, 'index'])->name('dbf-view');

Route::middleware('auth:sanctum')->post('dbf-update', [DBFController::class, 'update'])->name('dbf-update');

Route::middleware('auth:sanctum')->post('dbf-add', [DBFController::class, 'store'])->name('dbf-add');

Route::middleware('auth:sanctum')->put('dbf-show', [DBFController::class, 'show'])->name('dbf-show');

Route::middleware('auth:sanctum')->put('dbf-show2', [DBFController::class, 'show2'])->name('dbf-show2');

Route::middleware('auth:sanctum')->post('dbf-upload', [DBFController::class, 'upload'])->name('dbf-upload');
