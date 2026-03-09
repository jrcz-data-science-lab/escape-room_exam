**1) Korte introductie (10–12s)**
"Deze applicatie biedt een publieke leaderboard waar bezoekers scores kunnen indienen en een aparte admin-interface voor beheer. Scores worden toegevoegd via een veilige API en beheerd via een sessie-gevoede admin-login."

**2) Hoe voeg je een score toe? (15–20s)**
"De client stuurt een POST naar `/api/scores` met JSON `{ "game_slug": "<slug>", "player_name": "<naam>", "score": <getal> }` en een API-token. De API verwacht een geldige `game_slug` en valideert `player_name` als string en `score` als integer >= 0. Het token kan meegegeven worden als header `X-API-TOKEN` of `X-Token`, of als query parameter `api_token`. Bij succes wordt het record opgeslagen met `Score::create()` en de API geeft een 201-response terug."

Exact quote voor de examinator:
"POST `/api/scores` met `game_slug`, `player_name` en `score`; token via `X-API-TOKEN`/`X-Token` of `api_token`; server valideert en slaat op; response 201."

**3) Hoe wordt de API beveiligd? (10s)**
"De API gebruikt de middleware alias `leaderboard.token` (`App\Http\Middleware\CheckLeaderboardApiToken`). Deze middleware leest het token uit `X-API-TOKEN` of `X-Token` header (of `api_token` query param) en zoekt de bijbehorende `Game` op via `game_slug`. Het controleert het aangeleverde token tegen het `api_token`-veld van die `Game`. Zonder geldig token of zonder `game_slug` krijgt de client een 401-response."

Exact quote:
"Middleware `leaderboard.token` haalt token uit header/query, zoekt `Game` op `game_slug` en vergelijkt met `Game->api_token`; ongeldig = 401."

**4) Hoe werkt de admin-authenticatie? (10s)**
"Admin logt in via `/admin/login`. De controller vergelijkt het ingevoerde wachtwoord met `ADMIN_PASSWORD` uit de environment. Bij succes zet de server `session('is_admin') = true`. Admin-routes zijn beveiligd met `AdminAuth` middleware die deze sessie-flag controleert."

Exact quote:
"Admin login vergelijkt wachtwoord met `ADMIN_PASSWORD` in .env; op succes: `session('is_admin') = true`; middleware blokkeert anders."

**5) Waarom geen token in localStorage? (6–8s)**
"Het token blijft alleen in het invoerveld zodat het niet persistent opgeslagen wordt; dit vermindert risico op diefstal via XSS."

Exact quote:
"Token wordt alleen in het invoerveld gehouden (RAM), niet in localStorage/sessionStorage, om XSS-risico te verkleinen."

**6) Belangrijke security best practices (voor extra vraag) (10s)**

- "Gebruik gehashte gebruikersaccounts voor admin in plaats van plain `ADMIN_PASSWORD`."
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
