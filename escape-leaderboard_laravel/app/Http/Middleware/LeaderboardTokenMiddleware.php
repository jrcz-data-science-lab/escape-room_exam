<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LeaderboardTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Token');

        // Voor test doeleinden accepteren we elke token
        // In productie zou je dit moeten vervangen met echte token validatie
        if (!$token) {
            return response()->json(['error' => 'Token niet gevonden'], 401);
        }

        return $next($request);
    }
}
