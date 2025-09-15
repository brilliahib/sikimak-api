<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('/get-auth', [AuthController::class, 'getAuth']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
        Route::put('/update-account', [AuthController::class, 'updateAccount']);
    });

    Route::apiResource('applications', ApplicationController::class);
});
