<?php

use App\Http\Controllers\{Cabinet\ProfileController, Home\HomeController};
use App\Http\Controllers\Admin\Adverts;
use App\Http\Controllers\Admin\Region\RegionController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Cabinet\HomeController as CabinetHomeController;
use App\Http\Controllers\Cabinet\TwoFactorController;
use App\Http\Controllers\Cabinet\Adverts\AdvertController;
use App\Http\Controllers\Cabinet\Adverts\CreateController;
use App\Http\Controllers\Cabinet\Adverts\ManageController;
use App\Http\Controllers\Adverts\AdvertShowController;

use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/verify/{token}', [VerifyController::class, 'verify'])->name('register.verify');
Route::get('/ajax/regions', [\App\Http\Controllers\Ajax\RegionController::class, 'get'])->name('ajax.regions');

// ---- Public advert show ----
Route::get('/adverts/show/{advert}', [AdvertShowController::class, 'show'])->name('adverts.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::prefix('cabinet')->name('cabinet.')->group(function () {

        // Cabinet home (filled-profile kerak)
        Route::middleware('filled-profile')->group(function () {
            Route::get('/', [CabinetHomeController::class, 'index'])->name('index');
        });

        // ---- ADVERTS ----
        Route::prefix('adverts')->name('adverts.')->group(function () {

            // Index — e'lonlar ro'yxati
            Route::get('/', [AdvertController::class, 'index'])->name('index');

            // Create flow
            Route::prefix('create')->name('create.')->group(function () {
                Route::get('/', [CreateController::class, 'category'])->name('category');
                Route::get('/region/{category}', [CreateController::class, 'region'])->name('region');
                Route::get('/region/{category}/{region}', [CreateController::class, 'region'])->name('region.region');
                Route::get('/advert/{category}', [CreateController::class, 'advert'])->name('advert');
                Route::get('/advert/{category}/{region}', [CreateController::class, 'advert'])->name('advert.region');
                Route::post('/advert/{category}', [CreateController::class, 'store'])->name('store');
                Route::post('/advert/{category}/{region}', [CreateController::class, 'store'])->name('store.region');
            });

            // Manage routes — SHOW dan OLDIN (tartib muhim!)
            Route::get('/{advert}/attributes', [ManageController::class, 'editAttributes'])->name('attributes.edit');
            Route::put('/{advert}/attributes', [ManageController::class, 'UpdateAttributes'])->name('attributes.update');
            Route::get('/{advert}/photos', [ManageController::class, 'editPhotos'])->name('photos.edit');
            Route::post('/{advert}/photos', [ManageController::class, 'UpdatePhotos'])->name('photos.update');
            Route::delete('/{advert}/photos/{photo}', [ManageController::class, 'destroyPhoto'])->name('photos.destroy');
            Route::post('/{advert}/moderation', [ManageController::class, 'sendToModeration'])->name('send-to-moderation');
            Route::delete('/{advert}', [ManageController::class, 'destroy'])->name('destroy');

            // Show (owner) — ENG OXIRIDA
            Route::get('/{advert}', [AdvertController::class, 'show'])->name('show');
        });

        // ---- PROFILE ----
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Cabinet\ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [\App\Http\Controllers\Cabinet\ProfileController::class, 'edit'])->name('edit');
            Route::put('/edit', [\App\Http\Controllers\Cabinet\ProfileController::class, 'update'])->name('update');

            Route::get('/phone', [\App\Http\Controllers\Cabinet\PhoneController::class, 'form'])->name('phone');
            Route::post('/phone', [\App\Http\Controllers\Cabinet\PhoneController::class, 'request'])->name('phone.request');
            Route::put('/phone', [\App\Http\Controllers\Cabinet\PhoneController::class, 'verify'])->name('phone.verify');

            Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two_factor.enable');
            Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two_factor.disable');
        });
    });

    // ---- ADMIN ----
    Route::prefix('admin')->name('admin.')->middleware('can:admin-panel')->group(function () {
        Route::get('/', [HomeController::class, 'dashboard'])->name('home');
        Route::resource('users', UserController::class);
        Route::resource('regions', RegionController::class);

        Route::post('regions/{region}/first', [RegionController::class, 'first'])->name('regions.first');
        Route::post('regions/{region}/up', [RegionController::class, 'up'])->name('regions.up');
        Route::post('regions/{region}/down', [RegionController::class, 'down'])->name('regions.down');
        Route::post('regions/{region}/last', [RegionController::class, 'last'])->name('regions.last');

        Route::prefix('adverts')->name('adverts.')->group(function () {

            Route::resource('categories', Adverts\CategoryController::class)->names('categories');

            Route::prefix('categories/{category}')->name('categories.')->group(function () {
                Route::get('/attributes/create', [Adverts\AttributeController::class, 'create'])->name('attributes.create');
                Route::post('/attributes', [Adverts\AttributeController::class, 'store'])->name('attributes.store');
                Route::get('/attributes/{attribute}/edit', [Adverts\AttributeController::class, 'edit'])->name('attributes.edit');
                Route::put('/attributes/{attribute}', [Adverts\AttributeController::class, 'update'])->name('attributes.update');
                Route::delete('/attributes/{attribute}', [Adverts\AttributeController::class, 'destroy'])->name('attributes.destroy');
            });

            Route::get('/{advert}', [Adverts\ManageController::class, 'show'])->name('show');
            Route::post('/{advert}/moderate', [Adverts\ManageController::class, 'moderate'])->name('moderate');
            Route::post('/{advert}/reject', [Adverts\ManageController::class, 'reject'])->name('reject');
            Route::delete('/{advert}', [Adverts\ManageController::class, 'destroy'])->name('destroy');
        });
    });
});

require __DIR__ . '/auth.php';
