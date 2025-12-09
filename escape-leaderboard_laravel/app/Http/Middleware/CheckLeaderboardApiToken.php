<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Game;


class CheckLeaderboardApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // Probeer het token uit verschillende plaatsen: header X-API-TOKEN, header X-Token, of query param api_token
        $token = $request->header('X-API-TOKEN') ?? $request->header('X-Token') ?? $request->query('api_token');

        // Kijk of er een game identifier is (slug of name) in body or query
        $gameIdentifier = $request->input('game_slug') ?? $request->input('game') ?? $request->query('game_slug') ?? $request->query('game');

        if ($gameIdentifier) {
            // Probeer game te vinden op slug of name
            $game = Game::where('slug', $gameIdentifier)->orWhere('name', $gameIdentifier)->first();
            if (!$game || !$game->api_token) {
                return response()->json(['message' => 'Unauthorized - unknown game or missing game token'], 401);
            }

            if ($token !== $game->api_token) {
                return response()->json(['message' => 'Unauthorized - invalid game token'], 401);
            }

            // Token klopt voor de opgegeven game
            return $next($request);
        }

        // Fallback: valideer tegen globale token in config
        if (!$token || $token !== config('services.leaderboard.api_token')) {
            return response()->json(['message' => 'Unauthorized - invalid API token'], 401);
        }

        // Token is geldig — laat de request door naar de volgende middleware/controller
        return $next($request);
    }
}
