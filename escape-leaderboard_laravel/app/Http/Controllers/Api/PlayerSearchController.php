<?php

// API-controller voor het ophalen van spelersnamen (typeahead/autocomplete).
// Doel: snelle, realtime zoekresultaten op naam-prefix, optioneel gefilterd op game.
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;

class PlayerSearchController extends Controller
{
    /**
     * Retourneer een lijst met spelersnamen die starten met de opgegeven query.
     * Optioneel: filter op game_slug zodat je alleen spelers binnen één escape room krijgt.
     * Wordt gebruikt door de frontend typeahead (while-you-type).
     */
    public function index(Request $request)
    {
        // Valideer minimale invoer; 1 teken is voldoende voor een prefix-zoek
        $data = $request->validate([
            'q' => 'required|string|min:1|max:255',
            'game_slug' => 'nullable|string|max:255',
        ]);

        $q = $data['q'];

        // Basiskwestie: selecteer distinct player_name die beginnen met de query
        $query = Score::query()
            ->select('player_name')
            ->where('player_name', 'like', $q . '%');

        // Optioneel filter op game_slug als meegegeven
        if (!empty($data['game_slug'])) {
            $query->whereHas('game', function ($sub) use ($data) {
                $sub->where('slug', $data['game_slug']);
            });
        }

        // Beperk aantal resultaten voor snelheid (typeahead)
        $names = $query
            ->distinct()
            ->orderBy('player_name')
            ->limit(10)
            ->pluck('player_name');

        return response()->json($names);
    }
}

