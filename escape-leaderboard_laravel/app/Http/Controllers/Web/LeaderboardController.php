<?php

namespace App\Http\Controllers\Web;
// Namespace definieert waar deze controller in de applicatiehiërarchie hoort.

use App\Http\Controllers\Controller;
// Importeer de basis Controller klasse zodat we die kunnen uitbreiden.

use App\Models\Score;
// Importeer het Eloquent model 'Score' om database-queries op de scores uit te voeren.

use Illuminate\Http\Request;
// Importeer de Request klasse (niet gebruikt in deze korte actie, maar meestal beschikbaar voor typehinting).

class LeaderboardController extends Controller
{
    /**
     * Controller voor de publieke leaderboard-pagina's.
     *
     * Acties:
     * - Haal top scores op
     * - Return view met data
     */
    public function index(Request $request)
    {
        // If a search query is provided, we want to show aggregated leaderboard by player
        // using each player's best score and allow searching by player name.
        $q = $request->query('q');

        // Build base query: aggregate best score per player
        $base = Score::selectRaw('player_name, MAX(score) as best_score')
            ->groupBy('player_name')
            ->orderByDesc('best_score');

        if ($q) {
            $base = $base->where('player_name', 'like', '%' . $q . '%');
        }

        // Paginate results so the view can scale to hundreds of players
        $perPage = 50;
        $scores = $base->paginate($perPage)->withQueryString();

        // If a specific player was searched (exact match), compute their global rank
        $searchedRank = null;
        if ($q) {
            // Try to find exact best score for the queried player
            $playerBest = Score::where('player_name', $q)->max('score');
            if ($playerBest !== null) {
                $higherCount = Score::selectRaw('player_name, MAX(score) as best_score')
                    ->groupBy('player_name')
                    ->havingRaw('MAX(score) > ?', [$playerBest])
                    ->get()
                    ->count();

                $searchedRank = $higherCount + 1;
            }
        }

        return view('leaderboard.index', compact('scores', 'q', 'searchedRank'));
    }
}
