# Examennotities — Leaderboard Applicatie (Nederlands)

Dit document bevat een beknopte uitleg van de belangrijkste onderdelen, logica en beveiliging van de leaderboard-applicatie. Gebruik dit als leidraad bij het uitleggen van je code tijdens je examen.

## Overzicht

-   Framework: Laravel (PHP) — MVC-architectuur
-   Database: SQLite (migraties in database/migrations)
-   Templating: Blade (resources/views)
-   Styling: Tailwind CSS (Vite / CDN fallback)

## Routes (belangrijkste)

-   `GET /` -> `LeaderboardController@index` (publieke leaderboard)
-   `POST /api/scores` -> API endpoint om scores te posten (gevalideerd en beschermd met token)
-   `GET /admin/login`, `POST /admin/login` -> admin login flow
-   Admin CRUD routes onder `/admin/*` zijn beschermd door `AdminAuth` middleware

## Models

-   `App\Models\Score`
    -   Velden via `$fillable`: `player_name`, `time_seconds`, `score`, `game_id`, `submitted_from_ip`
    -   Relevant voor Eloquent operaties (create, update, query)

## Controllers & Logica

1. `LeaderboardController@index`

    - Haalt top 10 scores op: `Score::orderByDesc('score')->limit(10)->get()`
    - Renderen van `leaderboard.index` view met data

2. `Api\ScoreController@store`

    - Valideert input (`score` integer >= 0, `player_name` string)
    - Maakt `Score::create($validated)` aan
    - Returnt JSON met HTTP 201
    - Tip voor examen: noem waarom server-side validatie belangrijk is (security, integriteit)

3. `Admin\ScoreAdminController` (index / edit / update / destroy)

    - Paginatie voor admin-lijst (25 per pagina)
    - Validatie bij update
    - Redirects met flash-berichten voor UX

4. `Admin\AuthController`
    - Eenvoudige admin-authenticatie: vergelijkt ingevoerd wachtwoord met `ADMIN_PASSWORD` uit `.env`
    - Bij succes: `session()->put('is_admin', true)`
    - Bij logout: `session()->forget('is_admin')`

## Middleware

-   `AdminAuth`:
    -   Controleert `session('is_admin')` en redirect naar admin login wanneer niet gezet
-   API-token middleware (conceptueel):
    -   Verwacht `X-Token` header en vergelijkt met `LEADERBOARD_API_TOKEN` in `.env`
    -   Wees voorbereid uit te leggen dat deze token-based aanpak eenvoudig is maar dat voor productie vaker HMAC, OAuth of API keys met rotations gewenst zijn

## Views (Blade templates)

-   `resources/views/leaderboard/index.blade.php`:

    -   Toont tabel met scores
    -   Heeft een formulier voor het indienen van scores (player_name, score, token input)
    -   JavaScript in de view doet client-side validatie en POST naar `/api/scores` met header `X-Token`
    -   Auto-refresh elke 30s, maar niet terwijl gebruiker een input veld actief heeft

-   `resources/views/admin/*.blade.php`:
    -   Admin UI: login, index, edit
    -   Navigatiebalk is aangepast naar zwart met witte tekst (consistentie)

## Frontend JavaScript (belangrijk om uit te leggen)

1. Validatie:
    - Controleert dat `token`, `player_name` en `score` aanwezig zijn
    - Score moet een nummer zijn
2. Token handling:
    - Token wordt NIET opgeslagen in localStorage/sessionStorage
    - Alleen in het invoerveld terwijl de pagina open is
    - Toggle knop voor tijdelijk tonen (`Toon` / `Verberg`)
3. Submissie:
    - `fetch('/api/scores', { method: 'POST', headers: { 'X-Token': token, 'Content-Type': 'application/json' }, body: JSON.stringify({ player_name, score }) })`
    - Bij response 201: succesmelding en korte reload
    - Error handling: toont server- of netwerkfouten in UI

## Database (migratie)

-   `create_scores_table`:
    -   `id`, `player_name` (string), `time_seconds` (integer|null), `score` (integer|null), `game_id` (string|null), `submitted_from_ip` (string|null), `timestamps()`

## Beveiliging & aandachtspunten (wat je moet kunnen uitleggen)

-   Input validatie voorkomt ongeldige records en verkleint risico op injection
-   Token-based API beveiliging houdt ongewilde posts buiten — maar:
    -   Token in `.env` is gevoelig; niet checken in VCS
    -   Voor productie: gebruik rotaties, scopes, of een secure credentials store
-   Admin-auth met wachtwoord uit `.env` is eenvoudig — leg uit waarom dit niet ideaal voor multi-admin setups (geen gebruikers, geen hashing, geen lockouts)
-   Session-based admin vlag: eenvoudig en duidelijk; toont concept van stateful auth

## Examentips — korte praatpunten

-   Leg stap-voor-stap uit wat er gebeurt als een score wordt ingediend:

    1. Gebruiker vult formulier in (client-side validatie)
    2. JS stuurt POST naar `/api/scores` met `X-Token`
    3. Server valideert token en body
    4. Server valideert velden en maakt record aan met `Score::create()`
    5. Server retourneert 201; client toont succes en ververst

-   Noem concrete argumenten voor de gekozen implementatie (simpel, leerzaam) en mogelijke verbeteringen (authenticatie, rate-limiting, auditing)

## Hoe lokaal te testen (snel stappen)

1. Dependencies installeren (indien nodig):

```powershell
composer install
npm install
```

2. Run migrations en seed (indien nodig):

```powershell
php artisan migrate
```

3. Start dev server:

```powershell
php artisan serve
```

4. (Optioneel) Als je Vite gebruikt:

```powershell
npm run dev
```

5. In geval van caching issues (views):

```powershell
php artisan view:clear
```

## Locatie van het document

-   Bestand: `docs/exam_notes_nl.md` (aangemaakt in de repository root `docs/`)

---