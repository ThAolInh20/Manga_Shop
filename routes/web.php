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
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Models\Category;
use App\Models\Wishlist;    
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\PayOSController;


// User login
Route::get('login', [AccountAuthController::class, 'showUserLoginForm'])->name('login');
Route::post('login', [AccountAuthController::class, 'userLogin']);

Route::get('register', [AccountAuthController::class, 'register'])->name('register');
Route::post('register', [AccountAuthController::class, 'storeRegister']);

Route::get('change-password', [AccountAuthController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('change-password', [AccountAuthController::class, 'changePassword'])->name('password.update');


Route::get('forgot-password', [AccountAuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AccountAuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [AccountAuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AccountAuthController::class, 'resetPassword'])->name('password.store');




Route::post('logout', [AccountAuthController::class, 'userLogout'])->name('user.logout');


// Admin login
Route::get('admin/login', [AccountAuthController::class, 'showAdminLoginForm'])->name('admin.login');
 Route::post('admin/login', [AccountAuthController::class, 'adminLogin']);

Route::middleware(['auth', 'role:0,1'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::prefix('admin')->group(function () {
        
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
        Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancelAdminOrder']);
        Route::post('/orders/{orderId}/status', [OrderController::class, 'updateAdminStatus']);
    });
    

});
Route::prefix('api')->group(function () {
     Route::get('/user', [AccountAuthController::class, 'checkLogin']);
    Route::get('/suggest-products', [WishlistController::class, 'suggestProducts']);

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);;
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);;
    
    Route::get('/boloc', [ProductController::class, 'filterField']);
    route::get('/products', [ProductController::class, 'getAllProducts']);
    route::get('/categories', [CategoryController::class, 'listCategories']);

    Route::post('/cart', [CartController::class, 'add']);
    Route::put('/cart/{productId}', [CartController::class, 'update']);
    Route::delete('/cart/{productId}', [CartController::class, 'remove']);
    Route::get('/cart', [CartController::class, 'list']);

    route::get('/vouchers/active', [VoucherController::class, 'listActiveVouchers']);

    route::post('/order', [OrderController::class, 'add']);
    route::get('/user/orders', [OrderController::class, 'listUserOrders']);
    Route::post('/order/{orderId}/cancel', [OrderController::class, 'cancelOrder']);
    Route::post('/order/{orderId}/status', [OrderController::class, 'updateStatus']);
    Route::post('/order/{orderId}/recall', [OrderController::class, 'recallOrder']);
    route::get('/order/{orderId}', [OrderController::class, 'userShow2']);
    Route::post('/order/momo-dev', [OrderController::class, 'momoDevPay']);
    Route::post('/order/momo-dev/confirm', [OrderController::class, 'momoDevConfirm']);
    Route::post('/order/{order}/apply-voucher', [OrderController::class, 'applyVoucher'])->name('order.apply-voucher');

    Route::post('/order/payos/create', [PayOSController::class, 'create']);
    Route::post('/order/payos/webhook', [PayOSController::class, 'webhook']);
    // route::get('/order/{orderId}', [OrderController::class, 'showOrder']);
    Route::post('/order/cod-confirm', [OrderController::class, 'codConfirm']);
    Route::put('/order/{order}/update-shipping', [OrderController::class, 'updateShipping']);

    Route::get('/shippings', [ShippingController::class, 'index']);
    Route::post('/shippings', [ShippingController::class, 'store']);
    Route::put('/shippings/{id}', [ShippingController::class, 'update']);

    Route::get('/provinces', function () {
        $response = Http::get("https://provinces.open-api.vn/api/?depth=1");
        return $response->json();
    });
    Route::get('/provinces/{code}', function ($code) {
         return Http::get("https://provinces.open-api.vn/api/p/{$code}?depth=2")->json();
    });

    Route::get('/districts/{code}', function ($code) {
        return Http::get("https://provinces.open-api.vn/api/d/{$code}?depth=2")->json();
    });


});

Route::middleware(['auth', 'role:2'])->group(function () {
    

});

route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

route::get('/products/{product}', [ProductController::class, 'showProductForUser'])->name('user.products.show');
route::get('/products', [ProductController::class, 'indexForUser'])->name('user.products.list');
route::get('/wishlist', [WishlistController::class, 'showWishlist'])->name('user.wishlist.list');
route::get('/cart', [CartController::class, 'index'])->name('user.cart.list');
route::get('/order', [OrderController::class, 'userOrdersPage'])->name('user.order.list');
route::get('/order/{orderId}', [OrderController::class, 'userShow'])->name('user.order.show');
route::get('/order/checkout/{orderId}', [OrderController::class, 'userOrderUpdateForm'])->name('user.order.checkout');
Route::put('/order/checkout/{orderId}', [OrderController::class, 'updateUserOrder'])->name('user.order.update.post');


Route::get('/order/{order}/payos-return', [PayOSController::class, 'return'])->name('payos.return');
route::get('/order/{order}/payos-cancel', [PayOSController::class, 'cancel'])->name('payos.cancel');



