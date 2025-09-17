<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$role): Response
    {
       
        if (!Auth::check()) {
            return redirect('/');  
        }

        // Lấy role user hiện tại
        $userRole = Auth::user()->role;

        // Kiểm tra role
        if (!in_array($userRole, $role)) {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}
