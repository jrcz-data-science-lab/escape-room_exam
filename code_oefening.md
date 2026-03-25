# Code Reading Oefening - Bereid je voor op je examen!

## 🎯 Doel: Leer je code snel te vinden en uit te leggen

---

## Oefening 1: Vind de Knop

### Opdracht: Leg de "Verwijder" knop uit

**Scenario:** Examinator vraagt: "Kun je uitleggen hoe het verwijderen van een game werkt?"

**Stappenplan:**

1. 🗂️ **Vind de knop** in welke file?
2. 🔍 **Wat is de exacte code?**
3. 🛣️ **Welke route wordt aangeroepen?**
4. 🧠 **Welke controller methode?**
5. 💾 **Wat gebeurt er in de database?**
6. 🔄 **Wat gebeurt er daarna?**

**Antwoord (oefen zelf eerst):**

<details>
<summary>Klik voor het antwoord</summary>

1. **File:** `resources/views/admin/games/index.blade.php` (regel ~60-63)
2. **Code:**
   ```html
   <form method="POST" action="{{ route('admin.games.destroy', $game->id) }}">
     @csrf @method('DELETE')
     <button type="submit" class="delete-btn">Verwijderen</button>
   </form>
   ```
3. **Route:** `DELETE /admin/games/{game}` → `admin.games.destroy`
4. **Controller:** `GameAdminController@destroy(Game $game)`
5. **Database:**
   ```php
   $game->scores()->delete();  // Eerst alle scores
   $game->delete();           // Daarna de game
   ```
6. **Redirect:** Terug naar games overzicht met succesbericht

</details>

---

## Oefening 2: Trace de Data Flow

### Opdracht: Leg uit hoe een score wordt toegevoegd via "Snelle toevoeging"

**Scenario:** "Ik zie een formulier met 'Snelle toevoeging'. Hoe werkt dat?"

**Trace de stappen:**

1. 📝 **Formulier locatie en velden**
2. 🎯 **Route die wordt aangeroepen**
3. 🔐 **Security checks die plaatsvinden**
4. 💾 **Database operaties**
5. ✅ **Resultaat voor gebruiker**

**Antwoord (oefen eerst):**

<details>
<summary>Klik voor het antwoord</summary>

1. **Formulier:** `resources/views/admin/games/index.blade.php` (regels 51-58)
   - `player_name` (text, required)
   - `score` (number, required)
   - `api_token` (password, required)

2. **Route:** `POST /admin/games/{game}/scores` → `admin.games.addScore`

3. **Security:**
   - CSRF protection via `@csrf`
   - API token validation: `if ($validated['api_token'] !== $game->api_token)`

4. **Database:**

   ```php
   $score = Score::create([
       'player_name' => $validated['player_name'],
       'score' => $validated['score'],
       'game_id' => $game->id,
       'submitted_from_ip' => $request->ip(),
   ]);
   ```

5. **Resultaat:** Redirect terug met `->with('success', 'Score toegevoegd voor ' . $game->name)`

</details>

---

## Oefening 3: Leg de Relaties uit

### Opdracht: "Hoe weet de applicatie welke scores bij welke game horen?"

**Uitleg punten:**

1. 🗄️ **Database relaties**
2. 🔗 **Eloquent relaties in models**
3. 📊 **Hoe data wordt opgehaald**
4. 🎮 **Hoe dit wordt gebruikt in views**

**Antwoord (oefen eerst):**

<details>
<summary>Klik voor het antwoord</summary>

1. **Database:** `scores.game_id` foreign key naar `games.id`

2. **Eloquent relaties:**

   ```php
   // Game model
   public function scores() {
       return $this->hasMany(Score::class);
   }

   // Score model
   public function game() {
       return $this->belongsTo(Game::class);
   }
   ```

3. **Data ophalen:**

   ```php
   // Alle scores van een game
   $game->scores;

   // Game van een score
   $score->game;

   // Games met scores (eager loading)
   Game::with('scores')->get();
   ```

4. **In views:**
   ```php
   @foreach($game->scores as $score)
       {{ $score->player_name }} - {{ $score->score }}
   @endforeach
   ```

</details>

---

## Oefening 4: Security Uitleg

### Opdracht: "Hoe zorg je ervoor dat alleen admins bij het admin panel kunnen?"

**Security lagen om uit te leggen:**

1. 🚪 **Authentication check**
2. 👑 **Authorization check**
3. 🛡️ **Middleware implementation**
4. 🔗 **Route protection**
5. 🚫 **Fallback behavior**

**Antwoord (oefen eerst):**

<details>
<summary>Klik voor het antwoord</summary>

1. **Authentication:** `auth()->check()` - is gebruiker ingelogd?

2. **Authorization:** `auth()->user()->is_admin` - heeft gebruiker admin flag?

3. **Middleware:** `AdminAuth` class in `app/Http/Middleware/`

   ```php
   public function handle($request, Closure $next) {
       if (!auth()->check() || !auth()->user()->is_admin) {
           return redirect('/admin/login');
       }
       return $next($request);
   }
   ```

4. **Route protection:**

   ```php
   Route::middleware([AdminAuth::class])->prefix('admin')->group(function () {
       // Alle admin routes hierbinnen
   });
   ```

5. **Fallback:** Redirect naar login pagina als niet geautoriseerd

</details>

---

## Oefening 5: CSS Styling Uitleg

### Opdracht: "Hoe heb je het dark theme geïmplementeerd?"

**Styling aspecten:**

1. 🎨 **CSS variables**
2. 🌙 **Dark theme colors**
3. 📱 **Responsive design**
4. ✨ **Interactive elements**

**Antwoord (oefen eerst):**

<details>
<summary>Klik voor het antwoord</summary>

1. **CSS variables:**

   ```css
   :root {
     --primary-purple: #8b5cf6;
     --primary-blue: #3b82f6;
     --text-light: #ffffff;
     --bg-primary: #0a0a0f;
     --glass-bg: rgba(26, 26, 26, 0.85);
   }
   ```

2. **Dark theme implementation:**

   ```css
   body {
     background: var(--bg-primary);
     color: var(--text-light);
   }

   .admin-table {
     background: linear-gradient(
       180deg,
       rgba(26, 26, 26, 0.85),
       rgba(26, 26, 26, 0.75)
     );
   }
   ```

3. **Responsive design:**

   ```css
   @media (max-width: 768px) {
     .games-grid {
       grid-template-columns: 1fr;
     }
   }
   ```

4. **Interactive elements:**
   ```css
   .btn:hover {
     transform: translateY(-2px);
     box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
   }
   ```

</details>

---

## 🎮 Rapid Fire Oefeningen

### Snel vind de code:

**Vraag 1:** "Waar staat de code voor de admin login?"

<details>
<summary>Antwoord</summary>
`app/Http/Controllers/Admin/AuthController.php` - `showLogin()` en `login()` methodes
</details>

**Vraag 2:** "Welke file bevat de game creation form?"

<details>
<summary>Antwoord</summary>
`resources/views/admin/games/create.blade.php`
</details>

**Vraag 3:** "Waar wordt de API token gegenereerd?"

<details>
<summary>Antwoord</summary>
`app/Http/Controllers/Admin/GameAdminController.php` - `store()` methode: `Str::random(40)`
</details>

**Vraag 4:** "Welke CSS class styling de delete knoppen?"

<details>
<summary>Antwoord</summary>
`.delete-btn` in `resources/css/leaderboard.css`
</details>

**Vraag 5:** "Hoe heet de middleware die admin routes beschermt?"

<details>
<summary>Antwoord</summary>
`AdminAuth` in `app/Http/Middleware/AdminAuth.php`
</details>

---

## 🏆 Examen Simulatie

### Complete Scenario:

**Examinator:** "Kun je me door het hele proces leiden van het aanmaken van een nieuwe game tot het toevoegen van de eerste score?"

**Jouw antwoord zou moeten bevatten:**

1. 📝 **Game creation form** (file, fields, validation)
2. 🎯 **Store process** (controller, token generation, database)
3. 🔄 **Redirect with success** (flash messages)
4. 🎮 **Score addition form** (API token validation)
5. 💾 **Score creation** (database insertion)
6. 📊 **Result display** (leaderboard update)

**Oefen dit scenario hardop!**

---

## 📝 Tips voor je Examen

### Do's:

- ✅ **Begin bij de HTML** - "De knop staat in..."
- ✅ **Volg de flow** - "Dit roept aan..."
- ✅ **Noem de files** - "In de file X op regel Y..."
- ✅ **Leg de 'waarom' uit** - "Ik koos voor X omdat..."
- ✅ **Wees specifiek** - Gebruik exacte class names en method names

### Don'ts:

- ❌ **Niet te vaag** - "Het werkt wel..."
- ❌ **Niet raden** - "Ik denk dat..."
- ❌ **Niet springen** - Volg de logische flow
- ❌ **Niet vergeten** - Security, validation, error handling

### Magic Phrases:

- "De data flow is als volgt..."
- "Eerst gebeurt er X, daarna Y..."
- "Dit wordt beveiligd door..."
- "Ik heb gekozen voor deze aanpak omdat..."
- "De relatie tussen deze modellen is..."

Succes met oefenen! Je bent er helemaal klaar voor! 🚀
