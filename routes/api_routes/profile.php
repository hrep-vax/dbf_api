<?php

/** POST    /api/auth/login    Log-out the current auth user    Private **/

use App\Http\Controllers\ProfileController;

Route::middleware('auth:sanctum')->get('/me', [ProfileController::class, 'index'])->name('me');