<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Account;
use App\Models\Product;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
         return view('admin.dashboard', [
            'pendingOrders'   => Order::countPending(),
            'inactiveAccounts'=> Account::countInactive(),
            'newAccounts'     => Account::countNewThisWeek(),
            'lowStockProducts'        => Product::countLowStock(),

            'topCategory'     => Product::topCategoryByOrders(),
            'topAccountByOrders'=> Order::topAccountByOrders(),
            'topAccountByRevenue' => Order::topAccountByRevenue(),
        ]);
        // Trả về view dashboard
        // return view('admin.dashboard');
    }
}
