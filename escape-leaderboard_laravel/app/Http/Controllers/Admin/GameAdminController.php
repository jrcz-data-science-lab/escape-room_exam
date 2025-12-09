<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Str;

class GameAdminController extends Controller
{
    public function index()
    {
        $games = Game::orderBy('created_at', 'desc')->get();
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $data['slug'] = Str::slug($data['name']);

        // Ensure unique slug
        $original = $data['slug'];
        $i = 1;
        while (Game::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $original . '-' . $i;
            $i++;
        }

        Game::create($data);

        return redirect()->route('admin.games.index')->with('success', 'Spel toegevoegd.');
    }
}
