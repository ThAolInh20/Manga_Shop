<?php

use App\Http\Controllers\Auth\AccountAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('login', [AccountAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AccountAuthController::class, 'login']);
Route::post('logout', [AccountAuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:0,1'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});
Route::middleware(['auth', 'role:0,1'])->group(function () {
    Route::get('/user/webhome', [DashboardController::class, 'index'])->name('admin.dashboard');
});