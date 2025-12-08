
**1) Korte introductie (10–12s)**
"Deze applicatie biedt een publieke leaderboard waar bezoekers scores kunnen indienen en een aparte admin-interface voor beheer. Scores worden toegevoegd via een veilige API en beheerd via een sessie-gevoede admin-login."

**2) Hoe voeg je een score toe? (15–20s)**
"De client stuurt een POST naar `/api/scores` met JSON `{ "player_name": "<naam>", "score": <getal> }` en een `X-Token` header. De server valideert dat `player_name` een string is en `score` een integer >= 0. Bij succes wordt het record opgeslagen met `Score::create()` en de API geeft een 201-response terug."

Exact quote voor de examinator:
"POST `/api/scores` met `player_name` en `score`; `X-Token` header; server valideert en slaat op; response 201."

**3) Hoe wordt de API beveiligd? (10s)**
"De API gebruikt een middleware alias `leaderboard.token` (`App\Http\Middleware\CheckLeaderboardApiToken`) die de `X-Token` header vergelijkt met de server-configuratie (`config('services.leaderboard.api_token')`). Zonder juiste token wordt een 401 teruggegeven."

Exact quote:
"Middleware `leaderboard.token` controleert `X-Token` tegen de server token en geeft 401 bij ongeldig token."

**4) Hoe werkt de admin-authenticatie? (10s)**
"Admin logt in via `/admin/login`. De controller vergelijkt het ingevoerde wachtwoord met `ADMIN_PASSWORD` uit de environment. Bij succes zet de server `session('is_admin') = true`. Admin-routes zijn beveiligd met `AdminAuth` middleware die deze sessie-flag controleert."

Exact quote:
"Admin login vergelijkt wachtwoord met `ADMIN_PASSWORD` in .env; op succes: `session('is_admin') = true`; middleware blokkeert anders."

**5) Waarom geen token in localStorage? (6–8s)**
"Het token blijft alleen in het invoerveld zodat het niet persistent opgeslagen wordt; dit vermindert risico op diefstal via XSS."

Exact quote:
"Token wordt alleen in het invoerveld gehouden (RAM), niet in localStorage/sessionStorage, om XSS-risico te verkleinen."

**6) Belangrijke security best practices (voor extra vraag) (10s)**

-   "Gebruik gehashte gebruikersaccounts voor admin in plaats van plain `ADMIN_PASSWORD`."
-   "Voeg rate-limiting toe op `/api/scores` om brute force en DoS te beperken."

Exact quote:
"Voor productie: gehashte admin-accounts + rate-limiting + secrets in een vault."

**7) Snelle voorbeelden / één-liners (leestip tijdens demo)**

-   Route: ` POST /api/scores` -> `App\Http\Controllers\Api\ScoreController@store` (middleware `leaderboard.token`).
-   Validatie (API): `player_name: required|string|max:255`, `score: required|integer|min:0`.
-   Admin logout: `POST /admin/logout` -> logout invalidates session and regenerates CSRF token.

**8) cURL voorbeeld (copy-voorlezen of tonen)**

```
curl -X POST http://localhost:8000/api/scores \
  -H "Content-Type: application/json" \
  -H "X-Token: YOUR_TOKEN" \
  -d '{"player_name":"Jan","score":123}'
```

(Je kunt dit voorlezen als: "Stuur een POST naar `/api/scores` met header `X-Token` en body `{ player_name, score }`.")
---

**Bestands-locaties (snelle referentie)**

-   `routes/api.php` — definieert `POST /api/scores`.
-   `app/Http/Controllers/Api/ScoreController.php` — `store()` implementeert validatie en `Score::create()`.
-   `app/Http/Middleware/CheckLeaderboardApiToken.php` — middleware logica (alias `leaderboard.token`).
-   `resources/views/leaderboard/index.blade.php` — front-end formulier en fetch()-logica.
-   `app/Http/Controllers/Admin/AuthController.php` — admin login/logout.
-   `app/Http/Middleware/AdminAuth.php` — blokkeert admin routes als `session('is_admin')` ontbreekt.
-   `app/Http/Controllers/Admin/ScoreAdminController.php` — admin update/delete logic.
-   `app/Models/Score.php` — `$fillable` en modeldefinitie.

---

**Waar gebeurt alles (actie → bestand)**

-   Score indienen (HTTP request ontvangen): `routes/api.php` → `Route::post('/scores', [ScoreController::class, 'store'])->middleware('leaderboard.token');`
-   Score validatie & opslaan: `app/Http/Controllers/Api/ScoreController.php` → `store()` (validatie rules + `Score::create($validated)`).
-   API token check: `app/Http/Middleware/CheckLeaderboardApiToken.php` (alias `leaderboard.token`) — controleert `X-Token` tegen `config('services.leaderboard.api_token')`.
-   Frontend / form & fetch(): `resources/views/leaderboard/index.blade.php` — token input, submit JS en fetch naar `/api/scores`.
-   Admin login (show + POST): `routes/web.php` routes `admin.login` & `admin.login.post` → `app/Http/Controllers/Admin/AuthController.php` (`showLogin`, `login`).
-   Admin session check middleware: `app/Http/Middleware/AdminAuth.php` — controleert `session('is_admin')`.
-   Admin dashboard & beheer (edit/update/delete): `app/Http/Controllers/Admin/ScoreAdminController.php` (`index`, `edit`, `update`, `destroy`).
-   Admin logout: `routes/web.php` `POST /admin/logout` → `App\\Http\\Controllers\\Admin\\AuthController::logout` (session invalidate + regenerateToken).
-   Model & mass-assignment: `app/Models/Score.php` (`$fillable` bevat `player_name`, `time_seconds`, `score`, `game_id`, `submitted_from_ip`).
-   Database schema: `database/migrations/2025_10_30_123625_create_scores_table.php` (definieert `score`, `time_seconds` als integer/nullable).

Gebruik deze cheatsheet als blad tijdens je examen; de korte quotes zijn gemaakt om direct voor te lezen.

(End of sheet)
