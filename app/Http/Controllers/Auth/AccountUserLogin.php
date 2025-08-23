<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountUserLogin extends Controller
{
    public function showLoginForm()
    {
        return view('user.login'); // Assuming you have a login view for users
    }
    public function login(Request $request)
    {
        // Logic for user login
        // This would typically involve validating the request and authenticating the user
    }
    public function logout()
    {
        // Logic for user logout
        // This would typically involve invalidating the user's session
    }
}
