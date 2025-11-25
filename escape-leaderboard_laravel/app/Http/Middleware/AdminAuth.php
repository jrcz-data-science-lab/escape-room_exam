<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Controleer of de sessie aangeeft dat de gebruiker admin is
        if (!$request->session()->get('is_admin')) {
            // Als niet ingelogd als admin, stuur door naar de admin login pagina
            return redirect()->route('admin.login');
        }

        // Als admin-flag aanwezig is, laat de request door
        return $next($request);
    }
}
