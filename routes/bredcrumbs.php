<?php

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Cabinet\HomeController as CabinetHomeController;
use App\Http\Controllers\Home\HomeController;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;
use page\HomeController as AdminHomeController;

Breadcrumbs::register('home', function (BreadcrumbTrail $trail) {
    $trail->push('home');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->prefix('cabinet')->name('cabinet.')->group(function () {
    Route::get('/', [CabinetHomeController::class, 'index'])->name('index');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminHomeController::class, 'index'])->name('home');

    Route::resource('users', UsersController::class);

});
