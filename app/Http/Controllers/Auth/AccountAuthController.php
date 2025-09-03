<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountAuthController extends Controller
{
    public function showUserLoginForm()
{
    return view('user.login');
}

public function showAdminLoginForm()
{
    return view('admin.login');
}

public function userLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('home');
    }

    return back()->withErrors(['email' => 'Thông tin đăng nhập không đúng.']);
}

public function adminLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // chỉ cho role == 1 (admin)
        if (Auth::user()->role == 1|| Auth::user()->role == 0) {
            return redirect()->route('admin.dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'Bạn không có quyền admin.']);
    }

    return back()->withErrors(['email' => 'Thông tin đăng nhập không đúng.']);
}

    public function logout()
    {
        $role = Auth::user()->role;
        Auth::logout();
        if($role == 1 || $role == 0){
            return redirect()->route('admin.login')->with('status', 'Đăng xuất thành công.');
        }
        return redirect()->route('home')->with('status', 'Đăng xuất thành công.');
    }
}
