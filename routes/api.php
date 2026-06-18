<?php

use App\Http\Controllers\Api\AdvertController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ---- Public ----
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/adverts', [AdvertController::class, 'index']);
    Route::get('/adverts/{advert:id}', [AdvertController::class, 'show']);

    // ---- Protected (token kerak) ----
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Profile
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);

        // Favorites
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/adverts/{advert:id}/favorite', [FavoriteController::class, 'store']);
        Route::delete('/adverts/{advert:id}/favorite', [FavoriteController::class, 'destroy']);
    });
});
