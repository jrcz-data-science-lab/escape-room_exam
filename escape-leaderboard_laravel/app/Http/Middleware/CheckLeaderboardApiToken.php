<?php

// Middleware: controleert of een API-token geldig is voor het indienen van scores.
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Game;

class CheckLeaderboardApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // Lees token uit headers (X-API-TOKEN of X-Token) of query param api_token
        $token = $request->header('X-API-TOKEN') ?? $request->header('X-Token') ?? $request->query('api_token');

        // Bepaal voor welke game de score wordt ingestuurd (slug of naam)
        $gameIdentifier = $request->input('game_slug') ?? $request->input('game') ?? $request->query('game_slug') ?? $request->query('game');

        if ($gameIdentifier) {
            // Zoek de game en controleer of er een token is
            $game = Game::where('slug', $gameIdentifier)->orWhere('name', $gameIdentifier)->first();
            if (! $game || ! $game->api_token) {
                return response()->json(['message' => 'Unauthorized - unknown game or missing game token'], 401);
            }

            // Per-game token is geldig; ook globale token (config) accepteren als fallback
            $globalToken = config('services.leaderboard.api_token');
            if ($token === $game->api_token || ($globalToken && $token === $globalToken)) {
                return $next($request);
            }

            return response()->json(['message' => 'Unauthorized - invalid game token'], 401);
        }

        // Geen game meegegeven: valideer tegen globaal token in config
        if (! $token || $token !== config('services.leaderboard.api_token')) {
            return response()->json(['message' => 'Unauthorized - invalid API token'], 401);
        }

        // Token is geldig: laat de request door
        return $next($request);
    }
}
