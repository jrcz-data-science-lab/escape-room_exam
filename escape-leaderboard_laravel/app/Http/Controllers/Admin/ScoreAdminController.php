<?php

// Admin-controller voor beheer van individuele scores (overzicht, aanpassen, verwijderen)
namespace App\Http\Controllers\Admin;

// Basis controller
use App\Http\Controllers\Controller;
// Model voor scores
use App\Models\Score;
// Request voor validatie en input
use Illuminate\Http\Request;

class ScoreAdminController extends Controller
{
    // Toon een paginated lijst van alle scores (voor admins)
    public function index()
    {
        // Haal scores aflopend op score, 25 per pagina
        $scores = Score::orderByDesc('score')->paginate(25);
        // Render de admin.index view met de scores
        return view('admin.index', compact('scores'));
    }

    // Toon het formulier om één score aan te passen
    public function edit(Score $score)
    {
        // Geef de geselecteerde score door aan de edit-view
        return view('admin.edit', compact('score'));
    }

    // Verwerk updates van een score
    public function update(Request $request, Score $score)
    {
        // Valideer de nieuwe invoer
        $data = $request->validate([
            'player_name' => 'required|string|max:255',
            'score' => 'required|integer|min:0',
        ]);

        // Pas de score aan met gevalideerde data
        $score->update($data);

        // Terug naar het overzicht met een succesbericht
        return redirect()->route('admin.index')->with('success', 'Score bijgewerkt');
    }

    // Verwijder een score
    public function destroy(Score $score)
    {
        // Verwijder het record uit de database
        $score->delete();
        // Redirect met bevestiging
        return redirect()->route('admin.index')->with('success', 'Score verwijderd');
    }
}
