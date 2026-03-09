<?php

// Controller voor admin-beheer van escape rooms (games): aanmaken, tonen en snelle score toevoegen.
namespace App\Http\Controllers\Admin;

// Basis controller van Laravel
use App\Http\Controllers\Controller;
// Voor het ophalen/valideren van request data
use Illuminate\Http\Request;
// Eloquent modellen voor games en scores
use App\Models\Game;
use App\Models\Score;
// Helper om nette slugs en tokens te maken
use Illuminate\Support\Str;

class GameAdminController extends Controller
{
    // Toon een lijst met alle games (voor admins)
    public function index()
    {
        // Haal alle games alfabetisch op
        $games = Game::orderBy('name')->get();
        // Render de admin-view met het overzicht
        return view('admin.games.index', compact('games'));
    }

    // Toon het formulier om een nieuwe game toe te voegen
    public function create()
    {
        // Render create-formulier
        return view('admin.games.create');
    }

    // Sla een nieuwe game op: validatie, slug genereren, token aanmaken
    public function store(Request $request)
    {
        // Valideer naam/slug/omschrijving
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|alpha_dash|unique:games,slug',
            'description' => 'nullable|string|max:1000',
        ]);

        // Genereer een slug als de admin die niet invult
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            // Als slug al bestaat, voeg een teller-suffix toe
            $base = $validated['slug'];
            $i = 1;
            while (Game::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $base . '-' . $i++;
            }
        }

        // Genereer een API token (per-game) voor beveiligde score submissions
        $apiToken = Str::random(40);

        // Maak de game aan met de gegenereerde token
        $game = Game::create(array_merge($validated, [
            'api_token' => $apiToken,
        ]));

        // Stuur admin terug met succesmelding en toon token/slug éénmalig
        return redirect()->route('admin.games.index')
            ->with('success', 'Game aangemaakt')
            ->with('new_game_token', $apiToken)
            ->with('new_game_slug', $game->slug);
    }

    // Admin-only: snelle score toevoegen aan een game (met API-token verificatie)
    public function addScore(Request $request, Game $game)
    {
        // Valideer spelernaam, score en API-token
        $validated = $request->validate([
            'player_name' => 'required|string|max:255',
            'score' => 'required|integer|min:0',
            'api_token' => 'required|string',
        ]);

        // Controleer of de API-token overeenkomt met die van de game
        if ($validated['api_token'] !== $game->api_token) {
            return back()->withErrors(['api_token' => 'Het token is onjuist.']);
        }

        // Maak de score aan, koppel hem direct aan de juiste game
        $score = Score::create([
            'player_name' => $validated['player_name'],
            'score' => $validated['score'],
            'game_id' => $game->id,
            'submitted_from_ip' => $request->ip(),
        ]);

        // Terug naar dezelfde pagina met bevestiging
        return back()->with('success', 'Score toegevoegd voor ' . $game->name);
    }

    // Verwijder een game en alle bijbehorende scores
    public function destroy(Game $game)
    {
        try {
            // Tel het aantal scores dat wordt verwijderd voor feedback
            $scoreCount = $game->scores()->count();

            // Verwijder eerst alle scores die bij deze game horen
            $game->scores()->delete();

            // Verwijder de game zelf
            $game->delete();

            // Terug naar de games lijst met gedetailleerde succesmelding
            $message = 'Game "' . $game->name . '" is succesvol verwijderd.';
            if ($scoreCount > 0) {
                $message .= ' Er zijn ' . $scoreCount . ' bijbehorende scores verwijderd.';
            } else {
                $message .= ' Er waren geen scores voor deze game.';
            }

            return redirect()->route('admin.games.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            // Foutafhandeling als er iets misgaat
            return redirect()->route('admin.games.index')
                ->with('error', 'Er is een fout opgetreden bij het verwijderen van de game: ' . $e->getMessage());
        }
    }
}
