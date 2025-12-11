<?php

// API-controller voor het ontvangen en opslaan van nieuwe scores via een beveiligde POST-call.
namespace App\Http\Controllers\Api;

// Basis controller
use App\Http\Controllers\Controller;
// Model voor scores
use App\Models\Score;
// Request voor validatie/input
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Sla een nieuwe score op (aangeroepen door de game via /api/scores).
     * - Valideert invoer
     * - Koppelt score aan de juiste game (via slug of naam)
     * - Maakt de score aan en geeft JSON 201 terug
     */
    public function store(Request $request)
    {
        // Valideer verplichte velden: game_slug, score (min 0), spelernaam
        $validated = $request->validate([
            'game_slug' => 'required|string',
            'score' => 'required|integer|min:0',
            'player_name' => 'required|string|max:255'
        ]);

        // Zoek de game op slug of naam, zodat we het game_id kunnen invullen
        $game = \App\Models\Game::where('slug', $validated['game_slug'])
            ->orWhere('name', $validated['game_slug'])
            ->first();

        // Als de game niet bestaat: geef een foutmelding
        if (! $game) {
            return response()->json(['message' => 'Unknown game'], 422);
        }

        // Voeg het game_id toe aan de gevalideerde data
        $validated['game_id'] = $game->id;

        // Maak de score aan via mass-assignment
        $score = Score::create($validated);

        // Geef de nieuw aangemaakte score terug met HTTP status 201
        return response()->json($score, 201);
    }
}
