<?php

// De namespace van dit model: hoort bij de map App/Models
namespace App\Models;

// Importeert het Model uit de Laravel Eloquent ORM
use Illuminate\Database\Eloquent\Model;

// De Score klasse representeert 1 record (rij) uit de scores-tabel in de database
class Score extends Model
{
    // De velden die via mass-assignment (bijv. met create() of fill()) ingevuld mogen worden
    protected $fillable = [
        'player_name',          // Naam van de speler
        'time_seconds',        // (optioneel) Snelste tijd in seconden
        'score',               // Hoogst behaalde score (hoe hoger hoe beter)
        'game_id',             // ID van de escape room/game (foreign key)
        'submitted_from_ip',   // Het IP-adres vanwaar is ingediend
    ];

    // Relatie: elke Score behoort tot 1 Game
    public function game()
    {
        // Geeft de relatie aan naar het Game model, via het veld 'game_id' (foreign key)
        return $this->belongsTo(Game::class, 'game_id');
    }
}

// Model 'Score' representeert de tabel 'scores' in de database.
// De $fillable array definieert welke velden mass-assignment toestaan
// zodat we veilig Score::create($validated) kunnen gebruiken in controllers.
