<?php

namespace App\Http\Controllers\Api;
// Namespace: waar deze controller zich bevindt binnen de applicatie.

use App\Http\Controllers\Controller;
// Import van de basis Controller zodat we er van kunnen erven.

use App\Models\Score;
// Import van het Score model om records aan te maken.

use Illuminate\Http\Request;
// Import van Request voor validatie en toegang tot input.

class ScoreController extends Controller
{
    /**
     * API-controller voor het ontvangen en opslaan van nieuwe scores.
     *
     * Kort overzicht (NL):
     * - Validateer inkomende data
     * - Maak nieuw Score record met mass-assignment
     * - Retourneer JSON met HTTP 201
     */
    public function store(Request $request)
    {
        // Server-side validatie: altijd afdwingen (onbetrouwbaar om dit alleen client-side te doen)
        $validated = $request->validate([
            'game_slug' => 'required|string',
            'score' => 'required|integer|min:0', // score moet een integer zijn en minimaal 0
            'player_name' => 'required|string|max:255' // speler naam is verplicht, max 255 tekens
        ]);

        // Zoek het spel op basis van slug of naam
        $game = \App\Models\Game::where('slug', $validated['game_slug'])->orWhere('name', $validated['game_slug'])->first();
        if ($game) {
            $validated['game_id'] = $game->id;
        } else {
            // Als game niet bestaat, weiger of maak geen nieuwe aan zonder admin
            return response()->json(['message' => 'Unknown game'], 422);
        }

        // Mass-assignment: zorg dat $fillable correct is ingesteld in het model
        $score = Score::create($validated);

        // Return de nieuw aangemaakte resource met status 201 (Created)
        return response()->json($score, 201);
    }
}
