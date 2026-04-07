<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check of user is ingelogd
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        // Check of ingelogde user admin is
        if (!Auth::user()->is_admin) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Geen admin rechten']);
        }

        return $next($request);
    }
}
