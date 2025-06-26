<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetUserRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('user_role')) {
            if (Auth::guard('admin')->check()) {
                session(['user_role' => 'admin']);
            } elseif (Auth::guard('technician')->check()) {
                session(['user_role' => 'technician']);
            }
        }

        return $next($request);
    }
}
