<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/** Authentication routes **/
Route::prefix('/auth')->group(base_path('routes/api_routes/auth.php'));

/** Profile routes */
Route::prefix('/profile')->group(base_path('routes/api_routes/profile.php'));

/** Test Resources routes */
Route::middleware(['auth:sanctum', 'role:regular'])->prefix('/test-resources')
    ->group(base_path('routes/api_routes/test_resources.php'));
