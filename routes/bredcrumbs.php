<?php
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::register('home', function (BreadcrumbTrail $trail) {
    $trail->push('home');
});


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Cabinet\HomeController as CabinetHomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Bosh sahifa (Hamma uchun ochiq)
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Avtorizatsiya yo'llari (Breeze orqali keladi)
// Bu qator auth.php faylini ulaydi, shunda login/register ishlaydi
require __DIR__ . '/auth.php';

// 3. Shaxsiy kabinet (Faqat login qilgan mijozlar uchun)
Route::middleware(['auth', 'verified'])->prefix('cabinet')->name('cabinet.')->group(function () {
    Route::get('/', [CabinetHomeController::class, 'index'])->name('index');
});

// 4. Admin paneli (Faqat adminlar uchun)
// Kelajakda bu yerga 'admin' middleware qo'shishingiz mumkin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Admin bosh sahifasi: localhost:8081/admin
    Route::get('/', [AdminHomeController::class, 'index'])->name('home');

    // Foydalanuvchilarni boshqarish (CRUD): localhost:8081/admin/users
    Route::resource('users', UsersController::class);

});
