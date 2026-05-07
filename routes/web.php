<?php

use App\Http\Controllers\{Home\HomeController, Profile\ProfileController};
use App\Http\Controllers\Admin\Region\RegionController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Cabinet\HomeController as CabinetHomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/verify/{token}', [VerifyController::class, 'verify'])->name('register.verify');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::prefix('cabinet')->name('cabinet.')->group(function () {
        Route::get('/', [CabinetHomeController::class, 'index'])->name('index');
    });

    Route::prefix('admin')->name('admin.')->middleware('can:admin-panel')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('regions', RegionController::class);
    });

});

require __DIR__.'/auth.php';
