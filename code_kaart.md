# Code Kaart - Snelle Referentie voor Examen

## Knoppen Locaties & Functionaliteiten

### Admin Dashboard (/admin)

**Bestand:** `resources/views/admin/index.blade.php`

| Knop          | Locatie (regel) | Route                  | Controller Methode             | Functie             |
| ------------- | --------------- | ---------------------- | ------------------------------ | ------------------- |
| **Aanpassen** | ~60             | `admin.scores.edit`    | `ScoreAdminController@edit`    | Toon edit formulier |
| **Verwijder** | ~62             | `admin.scores.destroy` | `ScoreAdminController@destroy` | Verwijder score     |

**Code Example:**

```html
<!-- Verwijder knop -->
<form method="POST" action="{{ route('admin.scores.destroy', $s->id) }}">
  @csrf @method('DELETE')
  <button type="submit" class="delete-btn">Verwijder</button>
</form>
```

---

### Games Beheer (/admin/games)

**Bestand:** `resources/views/admin/games/index.blade.php`

| Knop                  | Locatie (regel) | Route                  | Controller Methode               | Functie                 |
| --------------------- | --------------- | ---------------------- | -------------------------------- | ----------------------- |
| **Nieuwe game**       | ~31             | `admin.games.create`   | `GameAdminController@create`     | Toon create formulier   |
| **Bekijk public**     | ~49             | `leaderboard.game`     | `LeaderboardController@showGame` | Toon publieke pagina    |
| **Snelle toevoeging** | ~57             | `admin.games.addScore` | `GameAdminController@addScore`   | Voeg score toe          |
| **Verwijderen**       | ~62             | `admin.games.destroy`  | `GameAdminController@destroy`    | Verwijder game + scores |

**Code Example:**

```html
<!-- Game verwijderen -->
<form method="POST" action="{{ route('admin.games.destroy', $game->id) }}">
  @csrf @method('DELETE')
  <button
    type="submit"
    class="delete-btn"
    onclick="return confirm('Weet je het zeker?')"
  >
    Verwijderen
  </button>
</form>
```

---

## 🧠 Controllers Overzicht

### ScoreAdminController

**Bestand:** `app/Http/Controllers/Admin/ScoreAdminController.php`

| Methode     | Route                         | Functie             | Key Code                                            |
| ----------- | ----------------------------- | ------------------- | --------------------------------------------------- |
| `index()`   | `GET /admin`                  | Toon alle scores    | `$scores = Score::orderBy('score', 'desc')->get();` |
| `edit()`    | `GET /admin/scores/{id}/edit` | Toon edit formulier | `return view('admin.edit', compact('score'));`      |
| `update()`  | `PUT /admin/scores/{id}`      | Update score        | `$score->update($validated);`                       |
| `destroy()` | `DELETE /admin/scores/{id}`   | Verwijder score     | `$score->delete();`                                 |

### GameAdminController

**Bestand:** `app/Http/Controllers/Admin/GameAdminController.php`

| Methode      | Route                           | Functie                 | Key Code                                      |
| ------------ | ------------------------------- | ----------------------- | --------------------------------------------- |
| `index()`    | `GET /admin/games`              | Toon alle games         | `$games = Game::orderBy('name')->get();`      |
| `create()`   | `GET /admin/games/create`       | Toon create formulier   | `return view('admin.games.create');`          |
| `store()`    | `POST /admin/games`             | Maak nieuwe game        | `$game = Game::create($validated);`           |
| `addScore()` | `POST /admin/games/{id}/scores` | Voeg score toe          | `$score = Score::create([...]);`              |
| `destroy()`  | `DELETE /admin/games/{id}`      | Verwijder game + scores | `$game->scores()->delete(); $game->delete();` |

---

## 🗄️ Database Relaties

### Game Model

**Bestand:** `app/Models/Game.php`

```php
class Game extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'api_token'];

    // Relatie: Game → Scores (1:N)
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
```

### Score Model

**Bestand:** `app/Models/Score.php`

```php
class Score extends Model
{
    protected $fillable = ['player_name', 'score', 'game_id', 'submitted_from_ip'];

    // Relatie: Score → Game (N:1)
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
```

**Database Schema:**

```
games table: id, name, slug, description, api_token
scores table: id, player_name, score, game_id, created_at
```

---

## 🛣️ Routes Overzicht

### Publieke Routes

**Bestand:** `routes/web.php`

```php
Route::get('/', [LeaderboardController::class, 'index']);           // Homepage
Route::get('/games/{slug}', [LeaderboardController::class, 'showGame']); // Game pagina
```

### Admin Routes (Beveiligd)

```php
Route::middleware([AdminAuth::class])->prefix('admin')->group(function () {
    // Score management
    Route::get('/', [ScoreAdminController::class, 'index']);
    Route::get('/scores/{score}/edit', [ScoreAdminController::class, 'edit']);
    Route::put('/scores/{score}', [ScoreAdminController::class, 'update']);
    Route::delete('/scores/{score}', [ScoreAdminController::class, 'destroy']);

    // Game management
    Route::get('/games', [GameAdminController::class, 'index']);
    Route::get('/games/create', [GameAdminController::class, 'create']);
    Route::post('/games', [GameAdminController::class, 'store']);
    Route::delete('/games/{game}', [GameAdminController::class, 'destroy']);
    Route::post('/games/{game}/scores', [GameAdminController::class, 'addScore']);
});
```

---

## 🔒 Security Implementatie

### AdminAuth Middleware

**Bestand:** `app/Http/Middleware/AdminAuth.php`

```php
public function handle($request, Closure $next)
{
    // Check: Is gebruiker ingelogd?
    if (!auth()->check()) {
        return redirect('/admin/login');
    }

    // Check: Is gebruiker admin?
    if (!auth()->user()->is_admin) {
        return redirect('/admin/login');
    }

    return $next($request);
}
```

### CSRF Protection

**In elke form:**

```html
<form method="POST">
  @csrf
  <!-- Genereert verborgen token -->
  <!-- Form content -->
</form>
```

---

## 🎨 CSS Classes & Styling

### Belangrijkste Classes

**Bestand:** `resources/css/leaderboard.css`

| Class                | Gebruik                    | Locatie          |
| -------------------- | -------------------------- | ---------------- |
| `.leaderboard-table` | Styling voor tabellen      | Admin dashboard  |
| `.game-card`         | Styling voor game kaarten  | Games beheer     |
| `.btn`               | Standaard knop styling     | Alle knoppen     |
| `.delete-btn`        | Rode delete knop           | Verwijder acties |
| `.admin-wrapper`     | Container voor admin pages | Admin layouts    |

### Dark Theme Variables

```css
:root {
  --primary-purple: #8b5cf6;
  --primary-blue: #3b82f6;
  --text-light: #ffffff;
  --text-muted: rgba(255, 255, 255, 0.7);
  --bg-primary: #0a0a0f;
  --glass-bg: rgba(26, 26, 26, 0.85);
}
```

---

## 🔍 Code Reading Checklist

### Als je een knop moet uitleggen:

1. **Vind de knop** in de view file
2. **Identificeer de form action** of href
3. **Zoek de route** in web.php
4. **Vind de controller methode**
5. **Begrijp de business logic**
6. **Leg de database interactie uit**

### Voorbeeld: Score Verwijderen

**Stap 1:** Knop in `admin/index.blade.php`

```html
<form method="POST" action="{{ route('admin.scores.destroy', $s->id) }}"></form>
```

**Stap 2:** Route in `web.php`

```php
Route::delete('/scores/{score}', [ScoreAdminController::class, 'destroy']);
```

**Stap 3:** Controller in `ScoreAdminController.php`

```php
public function destroy(Score $score)
{
    $score->delete();
    return redirect()->route('admin.index');
}
```

**Stap 4:** Database actie

- Eloquent vindt score via ID
- `$score->delete()` verwijdert record
- Redirect terug naar dashboard

---

## 🚀 Examen Tips

### Veel Gestelde Vragen:

**Q: "Waarom gebruik je @method('DELETE')?"**
A: HTML forms ondersteunen alleen GET/POST. Laravel simuleert DELETE via hidden input.

**Q: "Wat doet @csrf?"**
A: Genereert CSRF token voor security. Voorkomt cross-site request forgery attacks.

**Q: "Hoe werkt route model binding?"**
A: Laravel automatiseert model vinden: `destroy(Score $score)` vindt automatisch Score met ID uit URL.

**Q: "Waarom cascade delete bij games?"**
A: Data integriteit. Verwijder game → verwijder ook alle scores om orphaned records te voorkomen.

### Belangrijke Laravel Concepten:

1. **MVC Pattern:** Model-View-Controller architectuur
2. **Eloquent ORM:** Database interactie met objects
3. **Blade Templating:** PHP templating engine
4. **Middleware:** HTTP request filtering
5. **Route Model Binding:** Automatische model resolution
6. **RESTful Routing:** HTTP methods voor CRUD operations

### Code Flow Diagram:

```
User Click → Browser Request → Route → Middleware → Controller → Model → Database
```

Met deze kaart kun je snel elke functionaliteit vinden en uitleggen tijdens je examen! 🎯
