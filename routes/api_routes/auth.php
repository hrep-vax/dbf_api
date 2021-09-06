<?php

use App\Http\Controllers\AuthController;

/** POST    /api/auth/register    Create new user via registration    Public **/
Route::post('/register', [AuthController::class, 'register'])->name('register');

/** POST    /api/auth/login    Log-in to an existing user    Public **/
Route::middleware('auth:guest')->post('login', [AuthController::class, 'login'])->name('login');

/** POST    /api/auth/login    Log-out the current auth user    Private **/
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout'])->name('logout');

