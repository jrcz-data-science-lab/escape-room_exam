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
    public function index()
    {
        // Haal de top 10 scores uit de database, gesorteerd van hoog naar laag.
        $scores = Score::orderByDesc('score')->limit(10)->get();

        // Render de Blade view 'leaderboard.index' en geef de opgehaalde scores door
        return view('leaderboard.index', compact('scores'));
    }
}
