<?php

use App\Http\Controllers\DBFController;

/** GET    /api/profile/me    Show current auth user's profile info    Private **/
Route::middleware('auth:sanctum')->get('dbf-view', [DBFController::class, 'index'])->name('dbf-view');
