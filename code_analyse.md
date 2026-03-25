# Code Analyse - Escape Room Leaderboard

## 📚 Inhoudsopgave

1. [Project Structuur](#project-structuur)
2. [Routes - De Routekaart van je Applicatie](#routes---de-routekaart-van-je-applicatie)
3. [Controllers - De Hersenen van je Applicatie](#controllers---de-hersenen-van-je-applicatie)
4. [Models - De Database Laag](#models---de-database-laag)
5. [Views - De Visuele Laag](#views---de-visuele-laag)
6. [Security - De Beveiliging](#security---de-beveiliging)
7. [Knoppen en Functionaliteiten](#knoppen-en-functionaliteiten)

---

## 🏗️ Project Structuur

### Overall Architectuur

```
escape-leaderboard_laravel/
├── app/
│   ├── Http/Controllers/          # Business logic
│   │   ├── Admin/                # Admin specifieke controllers
│   │   └── Web/                  # Publieke controllers
│   ├── Models/                   # Database modellen
│   └── Middleware/               # Security middleware
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── admin/                # Admin views
│   │   └── leaderboard/          # Publieke views
│   └── css/                      # Stylesheets
├── routes/
│   └── web.php                   # Route definities
└── database/
    └── migrations/               # Database schema
```

**Key Concept:** MVC (Model-View-Controller) pattern

- **Model:** Database interactie (Game, Score, User)
- **View:** HTML/CSS templates wat gebruiker ziet
- **Controller:** Business logic tussen Model en View

---

## 🛣️ Routes - De Routekaart van je Applicatie

### Belangrijkste Routes (web.php)

```php
// Publieke routes
Route::get('/', [LeaderboardController::class, 'index'])           // Homepage
Route::get('/games/{slug}', [LeaderboardController::class, 'showGame']) // Per-game pagina

// Admin routes (beveiligd)
Route::middleware([AdminAuth::class])->prefix('admin')->group(function () {
    Route::get('/', [ScoreAdminController::class, 'index']);       // Admin dashboard
    Route::get('/games', [GameAdminController::class, 'index']);  // Games beheer
    Route::delete('/games/{game}', [GameAdminController::class, 'destroy']); // Game verwijderen
});
```

**Hoe het werkt:**

1. **URL** → **Route** → **Controller** → **View**
2. Routes bepalen welke controller methode wordt aangeroepen
3. Middleware beschermt admin routes

---

## Controllers - De Hersenen van je Applicatie

### 1. ScoreAdminController (admin/score beheer)

```php
class ScoreAdminController extends Controller
{
    // Toon alle scores (admin dashboard)
    public function index()
    {
        $scores = Score::orderBy('score', 'desc')->get();
        return view('admin.index', compact('scores'));
    }

    // Verwijder een score
    public function destroy(Score $score)
    {
        $score->delete();
        return redirect()->route('admin.index')->with('success', 'Score verwijderd');
    }
}
```

**Wat doet deze controller?**

- `index()`: Haalt alle scores op, toont in admin dashboard
- `destroy()`: Verwijdert specifieke score, redirect met succesbericht

### 2. GameAdminController (games beheer)

```php
class GameAdminController extends Controller
{
    // Toon alle games
    public function index()
    {
        $games = Game::orderBy('name')->get();
        return view('admin.games.index', compact('games'));
    }

    // Verwijder game + scores
    public function destroy(Game $game)
    {
        $game->scores()->delete();  // Eerst scores verwijderen
        $game->delete();            // Daarna game verwijderen
        return redirect()->route('admin.games.index');
    }
}
```

**Key Feature:** Cascade delete - verwijdert game én alle bijbehorende scores

---

## Models - De Database Laag

### 1. Game Model

```php
class Game extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'api_token'];

    // Relatie: een game heeft meerdere scores
    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
```

**Wat doet dit model?**

- Definieert welke velden in database tabel
- `scores()` relatie: $game->scores() geeft alle scores van deze game
- Eloquent maakt database queries makkelijk

### 2. Score Model

```php
class Score extends Model
{
    protected $fillable = ['player_name', 'score', 'game_id'];

    // Relatie: elke score behoort tot één game
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
```

**Database Relaties:**

```
Games (1) ←→ (N) Scores
```

---

## Views - De Visuele Laag

### 1. Admin Dashboard (admin/index.blade.php)

```html
<!-- Tabel met scores -->
<table class="leaderboard-table">
  <thead>
    <tr>
      <th>#</th>
      <th>Speler</th>
      <th>Score</th>
      <th>Acties</th>
    </tr>
  </thead>
  <tbody>
    @foreach($scores as $index => $s)
    <tr>
      <td>{{ $s->id }}</td>
      <td>{{ $s->player_name }}</td>
      <td>{{ $s->score }}</td>
      <td>
        <!-- Edit knop -->
        <a href="{{ route('admin.scores.edit', $s->id) }}" class="btn"
          >Aanpassen</a
        >

        <!-- Delete knop -->
        <form
          method="POST"
          action="{{ route('admin.scores.destroy', $s->id) }}"
        >
          @csrf @method('DELETE')
          <button type="submit" class="delete-btn">Verwijder</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
```

### 2. Games Beheer (admin/games/index.blade.php)

```html
@foreach($games as $game)
<div class="game-card">
  <h3>{{ $game->name }}</h3>
  <p>Slug: {{ $game->slug }}</p>

  <!-- Delete knop -->
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
</div>
@endforeach
```

---

## Security - De Beveiliging

### 1. AdminAuth Middleware

```php
class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect('/admin/login');
        }
        return $next($request);
    }
}
```

**Wat doet dit?**

- Checkt of gebruiker ingelogd is
- Checkt of gebruiker admin is
- Blokkeert toegang voor niet-admins

### 2. CSRF Protection

```html
<form method="POST">
  @csrf
  <!-- Genereert CSRF token -->
  <!-- Form content -->
</form>
```

**Waarom nodig?** Voorkomt CSRF attacks

---

## Knoppen en Functionaliteiten

### 1. Score Verwijderen Knop

**Locatie:** `admin/index.blade.php`

```html
<form method="POST" action="{{ route('admin.scores.destroy', $s->id) }}">
  @csrf @method('DELETE')
  <button type="submit" class="delete-btn">Verwijder</button>
</form>
```

**Hoe het werkt:**

1. **Klik op knop** → Form submit
2. **Route:** `DELETE /admin/scores/{id}`
3. **Controller:** `ScoreAdminController@destroy`
4. **Database:** Verwijdert score record
5. **Redirect:** Terug naar admin dashboard

### 2. Game Verwijderen Knop

**Locatie:** `admin/games/index.blade.php`

```html
<form method="POST" action="{{ route('admin.games.destroy', $game->id) }}">
  @csrf @method('DELETE')
  <button
    type="submit"
    class="delete-btn"
    onclick="return confirm('Game en alle scores verwijderen?')"
  >
    Verwijderen
  </button>
</form>
```

**Hoe het werkt:**

1. **Klik op knop** → JavaScript confirm
2. **Route:** `DELETE /admin/games/{id}`
3. **Controller:** `GameAdminController@destroy`
4. **Database:** Verwijdert scores + game (cascade)
5. **Redirect:** Terug naar games overzicht

### 3. Score Aanpassen Knop

**Locatie:** `admin/index.blade.php`

```html
<a href="{{ route('admin.scores.edit', $s->id) }}" class="btn">Aanpassen</a>
```

**Hoe het werkt:**

1. **Klik op link** → Navigatie
2. **Route:** `GET /admin/scores/{id}/edit`
3. **Controller:** `ScoreAdminController@edit`
4. **View:** Toont edit formulier

### 4. Nieuwe Game Toevoegen Knop

**Locatie:** `admin/games/index.blade.php`

```html
<a href="{{ route('admin.games.create') }}" class="btn">Nieuwe game</a>
```

**Hoe het werkt:**

1. **Klik op link** → Navigatie
2. **Route:** `GET /admin/games/create`
3. **Controller:** `GameAdminController@create`
4. **View:** Toont create formulier

---

## Code Flow Voorbeelden

### Flow 1: Score Verwijderen

```
1. Gebruiker klikt "Verwijder" knop
   ↓
2. Browser stuurt DELETE request naar /admin/scores/123
   ↓
3. Route matched → ScoreAdminController@destroy(123)
   ↓
4. Controller vindt Score model met ID 123
   ↓
5. $score->delete() verwijdert uit database
   ↓
6. Redirect naar /admin met succesbericht
   ↓
7. Browser toont admin dashboard zonder score
```

### Flow 2: Game Verwijderen (Cascade)

```
1. Gebruiker klikt "Verwijder" op game
   ↓
2. JavaScript confirm: "Weet je het zeker?"
   ↓
3. DELETE request naar /admin/games/456
   ↓
4. GameAdminController@destroy(456)
   ↓
5. $game->scores()->delete() → verwijdert alle scores
   ↓
6. $game->delete() → verwijdert game
   ↓
7. Redirect met succesbericht
```

---

## 📋 Examen Uitleg Tips

### Hoe leg je een knop uit?

**Stap 1: Locatie**
"De verwijder knop voor scores staat in `resources/views/admin/index.blade.php` op regel X"

**Stap 2: HTML Structuur**
"Het is een form met POST method, maar met @method('DELETE') voor RESTful routing"

**Stap 3: Route**
"De form action roept de route `admin.scores.destroy` aan"

**Stap 4: Controller**
"Die route gaat naar `ScoreAdminController@destroy` methode"

**Stap 5: Business Logic**
"In de controller wordt het Score model gevonden en verwijderd met `$score->delete()`"

**Stap 6: Resultaat**
"Daarna wordt de gebruiker teruggeleid naar het admin dashboard met een succesbericht"

### Belangrijke Laravel Concepten om te noemen:

- **Eloquent ORM:** Database interactie met PHP objects
- **Route Model Binding:** Automatisch model vinden via ID
- **Blade Templating:** PHP templating engine
- **Middleware:** Security laag voor routes
- **CSRF Protection:** Security feature voor forms
- **RESTful Routing:** HTTP methods (GET, POST, DELETE) voor verschillende acties

---

## 🔍 Code Reading Tips

### 1. Volg de Data Flow

- User Action → Route → Controller → Model → Database
- Altijd beginnen bij de HTML knop/link

### 2. Herken Laravel Patterns

- `@csrf` → CSRF protection
- `@method('DELETE')` → RESTful routing
- `{{ route('name') }}` → Named routes
- `@foreach($items as $item)` → Blade loops

### 3. Database Relaties

- `hasMany()` → Eén-naar-veel relatie
- `belongsTo()` → Veel-naar-één relatie
- `$model->relation()` → Access related data

### 4. Security Features

- Middleware voor route protection
- CSRF tokens in forms
- Input validation in controllers
- Authentication checks

Met deze analyse kun je elke knop en functionaliteit in je code uitleggen tijdens je examen! 🚀
