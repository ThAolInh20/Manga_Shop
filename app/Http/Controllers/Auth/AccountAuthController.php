<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = Auth::attempt($credentials);

        if ($user) {
            return redirect()->route('admin.dashboard'); // sau khi login thì vào dashboard
        }
        

        // Sai tài khoản hoặc mật khẩu
        return back()->with([
            'error' => 'Sai tài khoản hoặc mật khẩu.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('status', 'Đăng xuất thành công.');
    }
}
