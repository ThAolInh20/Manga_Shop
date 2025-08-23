<?php

use App\Http\Controllers\Auth\AccountAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;

Route::get('login', [AccountAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AccountAuthController::class, 'login']);
Route::post('logout', [AccountAuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:0,1'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::prefix('admin')->group(function () {
        Route::resource('accounts', AccountController::class);
    });
    Route::prefix('admin')->group(function () {
        Route::resource('categories', CategoryController::class);
    });
});
