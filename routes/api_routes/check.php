<?php

use App\Http\Controllers\CheckController;

/** GET    /api/profile/me    Show current auth user's profile info    Private **/
Route::middleware('auth:sanctum')->get('check-view', [CheckController::class, 'index'])->name('check-view');

Route::middleware('auth:sanctum')->post('check-update', [CheckController::class, 'update'])->name('check-update');

Route::middleware('auth:sanctum')->post('check-add', [CheckController::class, 'store'])->name('check-add');

Route::middleware('auth:sanctum')->put('check-show', [CheckController::class, 'show'])->name('check-show');
