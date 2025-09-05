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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Models\Wishlist;    

// User login
Route::get('login', [AccountAuthController::class, 'showUserLoginForm'])->name('login');
Route::post('login', [AccountAuthController::class, 'userLogin']);

Route::get('register', [AccountAuthController::class, 'register'])->name('register');
Route::post('register', [AccountAuthController::class, 'storeRegister']);

Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->name('password.update');

Route::post('logout', [AccountAuthController::class, 'userLogout'])->name('user.logout');

// Admin login

Route::middleware(['auth', 'role:0,1'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::prefix('admin')->group(function () {
        Route::get('login', [AccountAuthController::class, 'showAdminLoginForm'])->name('admin.login');
        Route::post('login', [AccountAuthController::class, 'adminLogin']);
        Route::resource('accounts', AccountController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('product_suppliers', ProductSupplierController::class);
        Route::resource('vouchers', VoucherController::class);
        Route::resource('orders', OrderController::class)->only([
            'index', 'show', 'edit', 'update'
        ]);
        Route::post('logout', [AccountAuthController::class, 'logout'])->name('admin.logout');
    });
    

});
Route::prefix('api')->group(function () {
    Route::get('/suggest-products', [WishlistController::class, 'suggestProducts']);
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);;
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);;
});

route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');