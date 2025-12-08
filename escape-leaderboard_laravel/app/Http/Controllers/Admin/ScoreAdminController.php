<?php

namespace App\Http\Controllers\Admin;
// Namespace voor admin controllers

use App\Http\Controllers\Controller;
// Basis controller

use App\Models\Score;
// Score model import

use Illuminate\Http\Request;
// Request import

class ScoreAdminController extends Controller
{
    // Controller voor admin-beheer van scores
    // De methodes hieronder bieden: index, edit, update en destroy
    // Inline NL-uitleg staat bij elke actie
    // Toon een paginated lijst van scores voor beheer
    public function index()
    {
        $scores = Score::orderByDesc('score')->paginate(25); // haal scores, 25 per pagina
        return view('admin.index', compact('scores')); // render admin.index view
    }

    // Toon het formulier om één score aan te passen
    public function edit(Score $score)
    {
        return view('admin.edit', compact('score')); // render admin.edit met het geselecteerde score model
    }

    // Verwerk updates van een score
    public function update(Request $request, Score $score)
    {
        // Valideer input
        $data = $request->validate([
            'player_name' => 'required|string|max:255',
            'score' => 'required|integer|min:0',
        ]);

        // Update het model met de gevalideerde data
        $score->update($data);

        // Redirect terug naar admin index met succesbericht
        return redirect()->route('admin.index')->with('success', 'Score bijgewerkt');
    }

    // Verwijder een score
    public function destroy(Score $score)
    {
        $score->delete(); // verwijder record uit DB
        return redirect()->route('admin.index')->with('success', 'Score verwijderd');
    }
}
