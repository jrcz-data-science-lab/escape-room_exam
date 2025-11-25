<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'player_name',
        'time_seconds',
        'score',
        'game_id',
        'submitted_from_ip',
    ];
}

// Model 'Score' representeert de tabel 'scores' in de database.
// De $fillable array definieert welke velden mass-assignment toestaan
// zodat we veilig Score::create($validated) kunnen gebruiken in controllers.
