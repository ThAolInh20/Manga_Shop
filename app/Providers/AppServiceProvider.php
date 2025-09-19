<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Cart;
use App\Models\WebsiteCustom;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = 0;
            $notificationCount = 0;

            if (Auth::check()) {
                $cartCount = Cart::where('account_id', Auth::id())->count();

                $notificationCount = Notification::where('account_id', Auth::id())
                    ->where('is_read',0) // chỉ đếm thông báo chưa đọc
                    ->count();
            }

            $view->with([
                'cartCount' => $cartCount,
                'notificationCount' => $notificationCount,
            ]);
        });
    //     $siteConfig = WebsiteCustom::first();
    //   View::share('siteConfig', $siteConfig);
     view()->composer('*', function ($view) {
        $websiteConfig = WebsiteCustom::first(); // lấy bản ghi đầu tiên
        $view->with('websiteConfig', $websiteConfig);
    });
    }
}
