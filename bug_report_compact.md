
## **Bug #1: Zoekbalk Exact Match**

### **Probleem**
Zoekbalk vond alleen exacte matches, spelers met lange namen werden niet gevonden.

### **Analyse**
- **Root Cause:** JavaScript `filter()` vs `includes()` method
- **Impact:** Gebruikservaring voor spelers met lange namen
- **Prioriteit:** Medium

### **Oplossing**
```javascript
function searchScores() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const scores = document.querySelectorAll('.score-item');
    
    scores.forEach(score => {
        const playerName = score.querySelector('.player-name').textContent.toLowerCase();
        if (playerName.includes(searchTerm)) {
            score.style.display = 'block';
        } else {
            score.style.display = 'none';
        }
    });
}
```

### **Resultaat**
-  **Partial matching** geïmplementeerd
-  **Real-time filtering** zonder server calls
-  **Gebruiksvriendelijker** zoekervaring

---

##  **Bug #2: Per-Game API Tokens**

### **Probleem**
Systeem gebruikte één globale API token voor alle escape rooms.

### **Analyse**
- **Root Cause:** Architectuur niet schaalbaar voor meerdere games
- **Impact:** Security risico en beperkingen
- **Prioriteit:** Hoog

### **Oplossing**
```php
// Game model met automatische token generatie
class Game extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'api_token'];
    
    protected static function boot()
    {
        static::creating(function ($game) {
            $game->api_token = Str::random(40);
        });
    }
}

// Controller met per-game validatie
public function addScore(Request $request, Game $game)
{
    if ($validated['api_token'] !== $game->api_token) {
        return back()->withErrors(['api_token' => 'Ongeldig API token']);
    }
    // Score toevoegen logic
}
```

### **Resultaat**
-  **Unieke tokens** per game
-  **Automatische generatie** bij nieuwe games
-  **Database storage** voor tokens
-  **Schaalbare architectuur**

---

##  **Bug #3: Dubbele Middleware**

### **Probleem**
Twee verschillende middlewares voor zelfde functionaliteit - onduidelijkheid.

### **Analyse**
- **Root Cause:** Code duplicatie en gebrekkende documentatie
- **Impact:** Verwarring en onderhoudsproblemen
- **Prioriteit:** Medium

### **Oplossing**
```php
// Verwijderde: LeaderboardTokenMiddleware.php
// Behouden: CheckLeaderboardApiToken.php
public function handle($request, Closure $next)
{
    $token = $request->header('Authorization');
    $game = Game::where('api_token', $token)->first();
    
    if (!$game) {
        return response()->json(['error' => 'Invalid token'], 401);
    }
    
    return $next($request);
}
```

### **Resultaat**
-  **Duidelijke middleware** structuur
-  **Verwijderde overbodige code**
-  **Betere onderhoudbaarheid**
-  **Documentatie** van keuzes

---

##  **Bug #4: Token Validatie**

### **Probleem**
Token validatie accepteerde elk willekeurig token.

### **Analyse**
- **Root Cause:** Onvoldoende security implementatie
- **Impact:** Kritieke vulnerability - API volledig open
- **Prioriteit:** Kritiek

### **Oplossing**
```php
public function handle($request, Closure $next)
{
    $token = $request->header('Authorization');
    
    // Haal game op basis van token
    $game = Game::where('api_token', $token)->first();
    
    if (!$game) {
        return response()->json([
            'error' => 'Invalid API token',
            'message' => 'The provided token is not valid'
        ], 401);
    }
    
    // Voeg game toe aan request
    $request->merge(['game' => $game]);
    
    return $next($request);
}
```

### **Resultaat**
- **Echte token validatie** met database lookup
- **401 responses** voor ongeldige tokens
- **Proper error handling** met duidelijke messages
- **Security audit trail** via logging

---

---
