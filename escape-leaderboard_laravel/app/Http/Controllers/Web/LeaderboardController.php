<?php
// Deze controller verzorgt de publieke leaderboard (highscore)-pagina's op de website

namespace App\Http\Controllers\Web;
// Namespace definieert waar deze controller in de applicatiehiërarchie hoort.

use App\Http\Controllers\Controller;
// Importeer de basis Controller klasse zodat we die kunnen uitbreiden.

use App\Models\Score;   // Model voor scores
use App\Models\Game;    // Model voor escape rooms/games

use Illuminate\Http\Request; // Voor het ophalen van request-data (zoals zoektermen)

class LeaderboardController extends Controller
{
    /**
     * Publieke hoofdpagina voor de top 10 beste scores (alle escape rooms samen).
     * Dit is de eerste (index) pagina van je project.
     */
    public function index(Request $request)
    {
        // Haal de 10 hoogste individuele scores (ongeacht game of speler)
        // with('game') zorgt dat elk Score-object ook de bijbehorende Game laadt.
        $scores = Score::orderByDesc('score')
            ->with('game')
            ->limit(10)
            ->get();

        // Haal alle escape rooms games op voor het keuzemenu en links
        $games = Game::orderBy('name')->get();

        // Geef deze data aan de (Blade) view; 'topScores' en 'games' beschikbaar in leaderboard/index.blade.php
        return view('leaderboard.index', [
            'topScores' => $scores,
            'games' => $games,
        ]);
    }

    /**
     * Per-room leaderboard: laat voor één specifieke escape room het leaderboard zien.
     * Ook kun je hierin zoeken op spelernaam.
     */
    public function showGame(Request $request, $slug)
    {
        // Zoek de game op basis van slug in de url
        $game = \App\Models\Game::where('slug', $slug)->first();
        if (! $game) {
            // Als de game niet bestaat, laat een 404 fout zien
            abort(404);
        }

        // Zoekterm vanuit de query string (?q=...)
        $q = $request->query('q');

        // Bouw een query om per speler de beste score te vinden voor deze game
        $base = Score::where('game_id', $game->id)
            ->selectRaw('player_name, MAX(score) as best_score') // groepeer op speler
            ->groupBy('player_name')
            ->orderByDesc('best_score');

        // Pas eventueel filtering toe (zoekfunctie op naam)
        if ($q) {
            $base = $base->where('player_name', 'like', '%' . $q . '%');
        }

        // Laat maximaal 50 resultaten per pagina zien
        $perPage = 50;
        $scores = $base->paginate($perPage)->withQueryString();

        // Als gezocht werd, bereken (optioneel) de globale ranking van de speler
        $searchedRank = null;
        if ($q) {
            // Zoek de beste score van deze speler
            $playerBest = Score::where('game_id', $game->id)->where('player_name', $q)->max('score');
            if ($playerBest !== null) {
                // Tel hoeveel spelers er een hogere max score hebben
                $higherCount = Score::where('game_id', $game->id)
                    ->selectRaw('player_name, MAX(score) as best_score')
                    ->groupBy('player_name')
                    ->havingRaw('MAX(score) > ?', [$playerBest])
                    ->get()
                    ->count();

                // De rang is 1 positie NA alle hogere scores
                $searchedRank = $higherCount + 1;
            }
        }

        // Haal alle games op voor keuzemenu/links in de view
        $games = Game::orderBy('name')->get();
        // Geef alle benodigde data door aan de blade-view
        return view('leaderboard.index', compact('scores', 'q', 'searchedRank', 'game', 'games'));
    }
}
