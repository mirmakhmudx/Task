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

use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/verify/{token}', [VerifyController::class, 'verify'])->name('register.verify');
Route::get('/ajax/regions', [\App\Http\Controllers\Ajax\RegionController::class, 'get'])->name('ajax.regions');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::prefix('cabinet')->name('cabinet.')->group(function () {

        Route::resource('adverts', AdvertController::class)->only([
            'index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

        Route::prefix('adverts')->name('adverts.')->group(function () {
            Route::get('/', [AdvertController::class, 'index'])->name('index');

            Route::prefix('create')->name('create.')->group(function () {
                Route::get('/', [CreateController::class, 'category'])->name('category');
                Route::get('/region/{category}', [CreateController::class, 'region'])->name('region');
                Route::get('/region/{category}/{region}', [CreateController::class, 'region'])->name('region.region');
                Route::get('/advert/{category}', [CreateController::class, 'advert'])->name('advert');
                Route::get('/advert/{category}/{region}', [CreateController::class, 'advert'])->name('advert.region');
                Route::post('/advert/{category}', [CreateController::class, 'store'])->name('store');
                Route::post('/advert/{category}/{region}', [CreateController::class, 'store'])->name('store.region');
            });
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/',     [ProfileController::class, 'show'])->name('show');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/edit', [ProfileController::class, 'update'])->name('update');

            Route::get('/phone',  [\App\Http\Controllers\Cabinet\PhoneController::class, 'form'])->name('phone');
            Route::post('/phone', [\App\Http\Controllers\Cabinet\PhoneController::class, 'request'])->name('phone.request');
            Route::put('/phone',  [\App\Http\Controllers\Cabinet\PhoneController::class, 'verify'])->name('phone.verify');

            Route::post('/two-factor/enable',  [TwoFactorController::class, 'enable'])->name('two_factor.enable');
            Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two_factor.disable');
        });


        Route::middleware('filled-profile')->group(function () {
            Route::get('/', [CabinetHomeController::class, 'index'])->name('index');


        });
    });

    Route::prefix('admin')->name('admin.')->middleware('can:admin-panel')->group(function () {
        Route::get('/', [HomeController::class, 'dashboard'])->name('home');
        Route::resource('users', UserController::class);
        Route::resource('regions', RegionController::class);

        Route::post('regions/{region}/first', [RegionController::class, 'first'])->name('regions.first');
        Route::post('regions/{region}/up',    [RegionController::class, 'up'])->name('regions.up');
        Route::post('regions/{region}/down',  [RegionController::class, 'down'])->name('regions.down');
        Route::post('regions/{region}/last',  [RegionController::class, 'last'])->name('regions.last');

        Route::prefix('adverts')->name('adverts.')->group(function () {

            Route::resource('categories', Adverts\CategoryController::class)->names('categories');

            Route::prefix('categories/{category}')->name('categories.')->group(function () {
                Route::get('/attributes/create',           [Adverts\AttributeController::class, 'create'])->name('attributes.create');
                Route::post('/attributes',                 [Adverts\AttributeController::class, 'store'])->name('attributes.store');
                Route::get('/attributes/{attribute}/edit', [Adverts\AttributeController::class, 'edit'])->name('attributes.edit');
                Route::put('/attributes/{attribute}',      [Adverts\AttributeController::class, 'update'])->name('attributes.update');
                Route::delete('/attributes/{attribute}',   [Adverts\AttributeController::class, 'destroy'])->name('attributes.destroy');
            });
        });
    });
});

require __DIR__ . '/auth.php';
