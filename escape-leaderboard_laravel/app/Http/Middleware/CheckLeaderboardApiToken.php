<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class CheckLeaderboardApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // Probeer het token uit verschillende plaatsen: header X-API-TOKEN, header X-Token, of query param api_token
        $token = $request->header('X-API-TOKEN') ?? $request->header('X-Token') ?? $request->query('api_token');

        // Als er geen token is of het komt niet overeen met de token in config, weiger de request
        if (!$token || $token !== config('services.leaderboard.api_token')) {
            return response()->json(['message' => 'Unauthorized - invalid API token'], 401);
        }

        // Token is geldig — laat de request door naar de volgende middleware/controller
        return $next($request);
    }
}
