<?php

use App\Http\Controllers\ProfileController;

/** GET    /api/profile/me    Show current auth user's profile info    Private **/
Route::middleware('auth:sanctum')->get('/me', [ProfileController::class, 'show'])->name('show-profile');

/** PUT    /api/profile/me    Update current auth user's profile info    Private **/
Route::middleware('auth:sanctum')->put('/me', [ProfileController::class, 'update'])->name('update-profile');