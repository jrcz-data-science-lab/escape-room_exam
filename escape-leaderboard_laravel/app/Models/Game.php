<?php

// Het Game model representeert één escape room / game in de database
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    // Maakt factory() mogelijk voor testen/seeding
    use HasFactory;

    // Velden die mass-assignment toestaan bij create()/update()
    protected $fillable = [
        'name',        // Naam van de escape room
        'slug',        // Slug voor de URL (bijv. /games/naam-van-game)
        'description', // Korte omschrijving van de game
        'api_token'    // Per-game API token voor beveiligde score-submissies
    ];
}
