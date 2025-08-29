<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
   
    protected function redirectTo($request): ?string
    {
        // Nếu request không phải API thì redirect về trang login
        return $request->expectsJson() ? null : route('admin.login');
    }
}
