**1) Korte introductie (10–12s)**
"Deze applicatie biedt een publieke leaderboard waar bezoekers scores kunnen bekijken en een aparte admin-interface voor beheer. Games en scores worden beheerd via een beveiligd admin panel met per-game API tokens voor veilige score submissions."

**2) Hoe voeg je een score toe? (15–20s)**
"De admin kan via het admin panel scores toevoegen aan een game. Dit gebeurt via POST naar `admin.games.addScore` met `player_name`, `score` en `api_token`. De API valideert het token tegen de specifieke game en slaat de score op met `Score::create()`. Het IP-adres wordt gelogd voor security."

Exact quote voor de examinator:
"POST naar `admin.games.addScore` met `player_name`, `score` en `api_token`; token validatie per game; response met success message."

**3) Hoe wordt de API beveiligd? (10s)**
"Elke game heeft een unieke API token in de database. De middleware `AdminAuth` beschermt admin routes. Token validatie gebeurt per-game via `GameAdminController@addScore` die het token vergelijkt met `game->api_token`."

Exact quote:
"Per-game API tokens in database; AdminAuth middleware voor admin routes; token validatie per game; ongeldig = error message."

**4) Hoe werkt de admin-authenticatie? (10s)**
"Admin logt in via `/admin/login`. De controller gebruikt Laravel's built-in auth. Admin-routes zijn beveiligd met `AdminAuth` middleware die `auth()->check()` en `auth()->user()->is_admin` controleert."

Exact quote:
"Admin login via Laravel auth; middleware `AdminAuth` controleert `auth()->check()` en `is_admin`; unauthorized = redirect."

**5) Waarom geen token in localStorage? (6–8s)**
"API tokens worden alleen in het admin formulier gehouden met `type="password"` en `autocomplete="new-password"` om XSS-risico te minimaliseren en te voorkomen dat browsers tokens opslaan."

Exact quote:
"Tokens alleen in password field met autocomplete="new-password"; geen persistent storage om XSS risico te verkleinen."

**6) Belangrijke security best practices (voor extra vraag) (10s)**

- "Per-game API tokens voor isolatie"
- "Input validation met Laravel rules"
- "CSRF protection op alle formulieren"
- "Cascade delete voor data integriteit"
- "IP logging voor audit trails"
- "Voeg rate-limiting toe op `/api/scores` om brute force en DoS te beperken."

Exact quote:
"Voor productie: gehashte admin-accounts + rate-limiting + secrets in een vault."

Opmerking: de API-route `/api/scores` heeft nu een throttle van `30` requests per minuut per IP (HTTP 429 bij overschrijding).

**7) Snelle voorbeelden / één-liners (leestip tijdens demo)**

- Route: ` POST /api/scores` -> `App\Http\Controllers\Api\ScoreController@store` (middleware `leaderboard.token`, `throttle:30,1`).
- Validatie (API): `player_name: required|string|max:255`, `score: required|integer|min:0`.
- Admin logout: `POST /admin/logout` -> logout invalidates session and regenerates CSRF token.

**8) cURL voorbeeld (copy-voorlezen of tonen)**

```
curl -X POST http://localhost:8000/api/scores \
  -H "Content-Type: application/json" \
  -H "X-API-TOKEN: YOUR_GAME_TOKEN" \
  -d '{"game_slug":"escape-room-1","player_name":"Jan","score":123}'
```

## (Je kunt dit voorlezen als: "Stuur een POST naar `/api/scores` met header `X-Token` en body `{ player_name, score }`.")

**Bestands-locaties (snelle referentie)**

- `routes/api.php` — definieert `POST /api/scores`.
- `app/Http/Controllers/Api/ScoreController.php` — `store()` implementeert validatie en `Score::create()`.
- `app/Http/Middleware/CheckLeaderboardApiToken.php` — middleware logica (alias `leaderboard.token`) (controleert token tegen `Game->api_token`, vereist `game_slug`).
- `app/Models/Game.php` — bevat `api_token`, `slug`, `name` en bijbehorende helpers.
- `resources/views/leaderboard/index.blade.php` — front-end formulier en fetch()-logica.
- `app/Http/Controllers/Admin/AuthController.php` — admin login/logout.
- `app/Http/Middleware/AdminAuth.php` — blokkeert admin routes als `session('is_admin')` ontbreekt.
- `app/Http/Controllers/Admin/ScoreAdminController.php` — admin update/delete logic.
- `app/Models/Score.php` — `$fillable` en modeldefinitie.

---

**Waar gebeurt alles (actie → bestand)**

- Score indienen (HTTP request ontvangen): `routes/api.php` → `Route::post('/scores', [ScoreController::class, 'store'])->middleware('leaderboard.token', 'throttle:30,1');`
- Score validatie & opslaan: `app/Http/Controllers/Api/ScoreController.php` → `store()` (validatie rules + `Score::create($validated)`).
- API token check: `app/Http/Middleware/CheckLeaderboardApiToken.php` (alias `leaderboard.token`) — leest token uit header/query, vereist `game_slug` en vergelijkt met `Game->api_token`.
- Frontend / form & fetch(): `resources/views/leaderboard/index.blade.php` — token input, submit JS en fetch naar `/api/scores`.
- Admin login (show + POST): `routes/web.php` routes `admin.login` & `admin.login.post` → `app/Http/Controllers/Admin/AuthController.php` (`showLogin`, `login`).
- Admin session check middleware: `app/Http/Middleware/AdminAuth.php` — controleert `session('is_admin')`.
- Admin dashboard & beheer (edit/update/delete): `app/Http/Controllers/Admin/ScoreAdminController.php` (`index`, `edit`, `update`, `destroy`).
- Admin logout: `routes/web.php` `POST /admin/logout` → `App\\Http\\Controllers\\Admin\\AuthController::logout` (session invalidate + regenerateToken).
- Model & mass-assignment: `app/Models/Score.php` (`$fillable` bevat `player_name`, `time_seconds`, `score`, `game_id`, `submitted_from_ip`).
- Database schema: `database/migrations/2025_10_30_123625_create_scores_table.php` (definieert `score`, `time_seconds` als integer/nullable).

Gebruik deze cheatsheet als blad tijdens je examen; de korte quotes zijn gemaakt om direct voor te lezen.

**Aanvullende examenklaar info (kort)**

- **Run / verify (snel lokaal):**

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8000
php artisan test
```

- **Waar komen game-tokens vandaan:** tokens zijn opgeslagen per game in het `api_token` veld van `app/Models/Game.php`. Seed-data / demo-tokens kunnen gevonden worden in de seeders (bijv. `database/seeders/DatabaseSeeder.php` of gerelateerde game-seeders).

- **Belangrijke HTTP-responses (snel memoriseren):**
    - **201:** aangemaakt (succesvolle POST `/api/scores`).
    - **401:** unauthorized (bv. `{"message":"Unauthorized - invalid game token"}` of `{"message":"Unauthorized - game_slug is required"}`).
    - **422:** validation error (bv. unknown game of ontbrekende velden).
    - **429:** rate limit exceeded (throttle: `30` requests/min per IP).

- **Tests & migrations referenties:**
    - Feature tests: `tests/Feature/LeaderboardSearchTest.php`, `tests/Feature/ScoreApiGameTokenTest.php`.
    - Model tests: `tests/Unit/ScoreModelTest.php`, `tests/Unit/UserModelTest.php`.
    - Relevante migration: `database/migrations/2025_10_30_123625_create_scores_table.php`.

- **Admin details (kort):**
    - `ADMIN_PASSWORD` staat in `.env` (gebruik alleen in dev/demo). Zie `app/Http/Controllers/Admin/AuthController.php` voor loginflow.
    - Admin-auth gebruikt een sessie-flag `session('is_admin')` en `app/Http/Middleware/AdminAuth.php` controleert deze.

Gebruik bovenstaande blokjes om snel te antwoorden tijdens je examen (run-commands, waar tokens zitten, voorbeeld fout-JSON en waar tests/migrations te vinden zijn).

**Database Relaties (kort)**

- `Game` ⇄ `Score`:
    - `Game` heeft veel `Score` records (`App\Models\Game::scores()` — `hasMany`).
    - `Score` behoort tot één `Game` (`App\Models\Score::game()` — `belongsTo` via `game_id`).
    - In de praktijk: `game_slug` wordt gebruikt in de API om een `Game` te vinden; controller/middleware vullen `game_id` in bij aanmaak van `Score`.
    - Let op: in de migrations staat `scores.game_id` als string (nullable) maar het model gebruikt `game_id` als foreign key — dit is de plek om vragen over type/foreign-key te beantwoorden.

- `User` en `Score`:
    - Er is geen directe `user_id` foreign key op `scores` in de huidige schema/migrations, dus scores zijn losgekoppeld van `User` in deze versie.
    - Admin-accounts worden opgeslagen in `users` (model: `App\Models\User`) en gebruiken gehashte wachtwoorden (zie tests/factories voor voorbeelden).

- Overige notes:
    - Kijk naar `database/migrations/2025_10_30_123625_create_scores_table.php` voor exacte kolommen (`player_name`, `time_seconds`, `score`, `game_id`, `submitted_from_ip`).
    - Voor vragen over seeds: `database/seeders/DatabaseSeeder.php` kan demo `Game` records met `api_token` bevatten.

(End of sheet)
