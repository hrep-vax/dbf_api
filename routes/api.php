<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/** Authentication routes **/
Route::prefix('/auth')->group(base_path('routes/api_routes/auth.php'));

/** Profile routes */
Route::prefix('/profile')->group(base_path('routes/api_routes/profile.php'));

/** Test Resources routes */
Route::middleware(['auth:sanctum'])->prefix('/test-resources')
  ->group(base_path('routes/api_routes/test_resources.php'));

/** DBF routes */
Route::middleware(['auth:sanctum'])->prefix('/dbf')
  ->group(base_path('routes/api_routes/dbf.php'));

/** Check routes */
Route::middleware(['auth:sanctum'])->prefix('/check')
  ->group(base_path('routes/api_routes/check.php'));
