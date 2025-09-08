<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


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
        return redirect()->route('home')->with('status', 'Đăng nhập thành công!');
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

    public function logout(Request $request)
    {
        $role = Auth::user()->role;
        Auth::logout();
       $request->session()->invalidate();
        $request->session()->regenerateToken();
            return redirect()->route('admin.login')->with('status', 'Đăng xuất thành công.');
       
    }
    public function userLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('status', 'Đăng xuất thành công.');
    }
    public function showChangePasswordForm()
    {
        return view('user.auth.changePassword');
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'Mật khẩu mới phải khác mật khẩu hiện tại.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('home')->with('status', 'Mật khẩu đã được cập nhật thành công.');
    }
    public function profile()
    {
        $user = Auth::user();
        return view('user.auth.profile', compact('user'));
    }
    public function checkLogin(){
        if (Auth::check()) {
            return response()->json(['status' => 'logged_in'], 200);
        }

        return response()->json(['status' => 'guest'], 200);
    }
    public function register()
    {
        return view('user.auth.register');
    }
    
    
    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:accounts',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new \App\Models\Account();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 2; // role 2 là user thường
        $user->save();

        if ($this->checkLogin($request->email, $request->password)) {
                $request->session()->regenerate();
                return redirect()->route('home')->with('status', 'Đăng ký thành công và đã đăng nhập!');
            }

            // Nếu login tự động thất bại (hiếm khi xảy ra)
            return redirect()->route('home')->with('status', 'Đăng ký thành công. Vui lòng đăng nhập.');
            }

            // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        return view('user.auth.forgot-password');
    }

    // Gửi email reset
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetPasswordForm($token)
    {
        return view('user.auth.reset-password', ['token' => $token, 'email' => request('email')]);
    }

    // Lưu mật khẩu mới
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

}

