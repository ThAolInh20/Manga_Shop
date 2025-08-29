<?php

use App\Http\Controllers\Auth\AccountAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductSupplierController;
use App\Http\Controllers\VoucherController;

// User login
Route::get('login', [AccountAuthController::class, 'showUserLoginForm'])->name('login');
Route::post('login', [AccountAuthController::class, 'userLogin']);

// Admin login
Route::get('admin/login', [AccountAuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('admin/login', [AccountAuthController::class, 'adminLogin']);

Route::post('logout', [AccountAuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:0,1'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::prefix('admin')->group(function () {
        Route::resource('accounts', AccountController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('product_suppliers', ProductSupplierController::class);
        Route::resource('vouchers', VoucherController::class);

    });
    

});

route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');