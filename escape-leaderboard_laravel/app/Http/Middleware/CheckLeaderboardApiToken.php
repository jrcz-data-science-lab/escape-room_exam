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

        // Bepaal voor welke game de score wordt ingestuurd (slug of naam) - dit is nu verplicht
        $gameIdentifier = $request->input('game_slug') ?? $request->input('game') ?? $request->query('game_slug') ?? $request->query('game');

        if (! $gameIdentifier) {
            return response()->json(['message' => 'Unauthorized - game_slug is required'], 401);
        }

        // Zoek de game en controleer of er een token is
        $game = Game::where('slug', $gameIdentifier)->orWhere('name', $gameIdentifier)->first();
        if (! $game || ! $game->api_token) {
            return response()->json(['message' => 'Unauthorized - unknown game or missing game token'], 401);
        }

        // Controleer of de token overeenkomt met de game-token
        if ($token !== $game->api_token) {
            return response()->json(['message' => 'Unauthorized - invalid game token'], 401);
        }

        // Token is geldig: laat de request door
        return $next($request);
    }
}
