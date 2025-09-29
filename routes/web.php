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
use App\Http\Controllers\WebsiteCustomController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Models\Category;
use App\Models\Wishlist;    
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\PayOSController;
use App\Http\Controllers\ChartController;
use App\Services\GHNService;
use App\Http\Controllers\NotificationController;
use App\Exports\OrdersExport;
use App\Models\Account;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Chart\Chart;

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

Route::middleware(['role:0,1'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::prefix('admin')->group(function () {
        Route::resource('accounts', AccountController::class)->middleware(['role:0']);
        // Route::get('accounts/my_edit/{id}', [AccountController::class, 'edit'])->name('accounts.myedit');
   
        
        Route::resource('categories', CategoryController::class)->middleware(['role:0']);
       
        Route::resource('products', ProductController::class)->except('show');
        // Route::get('products/import',[ProductController::class,'import'])->name('products.import');
        Route::get('products/{product}/import', [ProductController::class, 'import'])->name('products.import');
      
        //này import nhiều sản phẩm
        Route::post('/products/import', [ProductController::class, 'importFile'])->name('products.importFile');
        Route::get('/products/sample-file', [ProductController::class, 'sample'])->name('products.sample');
       
        // Lưu thông tin nhập kho
        Route::post('products/{product}/import', [ProductController::class, 'importStore'])->name('products.import.store');
        Route::get('/suppliers/chart', [ChartController::class, 'chartForSuppliers'])->name('suppliers.chart');
         
        Route::resource('suppliers', SupplierController::class);
        Route::get('/suppliers/{supplier}/filter-products', [SupplierController::class, 'filterProducts'])->name('suppliers.filterProducts');
        Route::post('/{supplier}/active', [SupplierController::class, 'active'])->name('suppliers.active');
        
        Route::resource('product_suppliers', ProductSupplierController::class);
        Route::resource('vouchers', VoucherController::class);
        Route::resource('orders', OrderController::class)->only([
            'index', 'show', 'edit', 'update'
        ]);
        Route::get('/orders/chart', [OrderController::class, 'showChart'])->name('orders.chart');

        Route::post('logout', [AccountAuthController::class, 'logout'])->name('admin.logout');
        Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancelAdminOrder']);
        Route::post('/orders/{orderId}/status', [OrderController::class, 'updateAdminStatus']);
       
        Route::prefix('/api/chart')->group(function () {
            Route::get('/orders', [ChartController::class, 'chartForOrder'])->name('admin.chart.orders');
            Route::get('/productbuy', [ChartController::class, 'productPieChart'])->name('admin.chart.products.pie');
        });

        Route::get('/website-custom/edit', [WebsiteCustomController::class, 'edit'])->name('website_custom.edit')->middleware(['role:0']);;
        Route::post('/website-custom/update', [WebsiteCustomController::class, 'update'])->name('website_custom.update')->middleware(['role:0']);;
    
        Route::get('/orders/export/{filter?}', function($filter = null){
            return Excel::download(new OrdersExport($filter), 'orders-'.$filter.'.xlsx');
        })->name('admin.orders.export');
        Route::get('/products/export/{category_id?}', [ProductController::class, 'export'])
            ->name('products.export');
    });
    
    

});

Route::prefix('api')->group(function () {
     Route::get('/user', [AccountAuthController::class, 'checkLogin']);
     Route::get('/user/profi', [AccountController::class, 'show2']);
    Route::put('/user/profi/{id}', [AccountController::class, 'update2']);
    Route::put('/user/deactivate', [AccountController::class, 'deactivate']);

    Route::get('/notifications', [NotificationController::class, 'index']);


    

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);;
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);;
    
    Route::get('/boloc', [ProductController::class, 'filterField']);
    route::get('/products', [ProductController::class, 'getAllProducts']);
    Route::get('/products/{id}/related', [ProductController::class, 'related'])->name('products.related');
    Route::get('/suggest-products', [WishlistController::class, 'suggestProducts']);
    
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
   
   
    Route::get('/orders/stats', [OrderController::class, 'countStatus']);


    Route::post('/order/payos/create', [PayOSController::class, 'create']);
    Route::post('/order/payos/webhook', [PayOSController::class, 'webhook']);
    // route::get('/order/{orderId}', [OrderController::class, 'showOrder']);
    Route::post('/order/cod-confirm', [OrderController::class, 'codConfirm']);
    Route::put('/order/{order}/update-shipping', [OrderController::class, 'updateShipping']);

    Route::get('/shippings', [ShippingController::class, 'index']);
    Route::post('/shippings', [ShippingController::class, 'store']);
    Route::put('/shippings/{id}', [ShippingController::class, 'update']);
    Route::delete('/shippings/{id}', [ShippingController::class, 'delete']);

    // Route::get('/provinces', function () {
    //     $response = Http::get("https://provinces.open-api.vn/api/?depth=1");
    //     return $response->json();
    // });
    // Route::get('/provinces/{code}', function ($code) {
    //      return Http::get("https://provinces.open-api.vn/api/p/{$code}?depth=2")->json();
    // });

    // Route::get('/districts/{code}', function ($code) {
    //     return Http::get("https://provinces.open-api.vn/api/d/{$code}?depth=2")->json();
    // });
    // Lấy danh sách Tỉnh/Thành
    Route::get('/provinces', function (GHNService $ghn) {
        return response()->json($ghn->getProvinces());
    });

    // Lấy danh sách Quận/Huyện theo province_id
    Route::get('/districts/{provinceId}', function ($provinceId, GHNService $ghn) {
        return response()->json($ghn->getDistricts((int)$provinceId));
    });

    // Lấy danh sách Phường/Xã theo district_id
    Route::get('/wards/{districtId}', function ($districtId, GHNService $ghn) {
        return response()->json($ghn->getWards((int)$districtId));
    });


});


route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
route::get('/user/profi', [AccountController::class, 'showBlade'])->name('user.profi');


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



