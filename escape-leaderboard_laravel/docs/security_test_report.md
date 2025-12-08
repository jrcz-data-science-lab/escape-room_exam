# Security Test Report — Escape Room Leaderboard

Datum: 2025-12-08
Uitgevoerd door: student / repo-owner (bewijs van statische checks en route-checks)

## Doel

Korte, reproduceerbare security check om te verifiëren dat belangrijke beveiligingsmechanismen aanwezig zijn en te rapporteren welke verbeteringen nog nodig zijn. Dit document bevat de exacte commando's die zijn uitgevoerd, de output, de analyse en concrete aanbevelingen (inclusief een patch-voorstel).

## Uitgevoerde checks

1. Routes en middleware inspectie

-   Commando: `php artisan route:list`

# Security test — kort rapport

Datum: 2025-12-08
Uitgevoerd door: repository-eigenaar

## Doel

Kort, praktisch onderzoek om te controleren of de belangrijkste beveiligingsmaatregelen aanwezig zijn en om concrete verbeterpunten te benoemen.

## Uitgevoerde stappen

1. Routes gecontroleerd:

```bash
php artisan route:list
```

Relevante routes (verkort):

```
POST    api/scores    -> Api\ScoreController@store
POST    admin/login   -> Admin\AuthController@login
POST    admin/logout  -> Admin\AuthController@logout
```

2. Statische controle in de codebase:

-   Gezocht op `@csrf`, `X-Token`, `leaderboard.token`, `session()->put('is_admin')`, `session()->forget('is_admin')`, `localStorage`, `sessionStorage`.

## Observaties

-   `POST /api/scores` is aanwezig en valt onder middleware `leaderboard.token`.
-   `app/Http/Middleware/CheckLeaderboardApiToken.php` controleert `X-Token` (of `X-API-TOKEN` / `api_token`) tegen `config('services.leaderboard.api_token')`.
-   Server-side validatie is aanwezig in de API-controller en in de admin-update (o.a. `score` heeft `min:0`).
-   Blade-templates gebruiken `@csrf` op state-changing forms.
-   De frontend bewaart het API-token alleen in een inputveld en schrijft het niet naar `localStorage` of `sessionStorage`.
-   Admin-login zet `session('is_admin')` en admin-routes zijn beschermd door `AdminAuth`.
-   De logout-actie verwijdert alleen de `is_admin`-flag (`session()->forget('is_admin')`); de sessie wordt niet volledig geïnvalideerd en het CSRF-token wordt niet vernieuwd.

## Conclusie

De belangrijkste basismaatregelen zijn aanwezig: API-token, server-side validatie en CSRF-bescherming. Een duidelijk verbeterpunt is de logout-implementatie: deze zou de sessie moeten invalidaten en het CSRF-token moeten regenereren.

## Aanbevelingen

1. Verbeter logout (prioriteit: hoog)

Voorgestelde wijziging in `app/Http/Controllers/Admin/AuthController.php`:

```php
public function logout(Request $request)
{
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('leaderboard.index');
}
```

2. Rate-limiting (prioriteit: middel)

Beperk het aantal requests naar `POST /api/scores` met Laravel's `throttle` middleware, bijvoorbeeld `->middleware('throttle:30,1')`.

3. Secrets beheer (prioriteit: middel)

Gebruik in productie een secrets manager (zoals Azure Key Vault) voor `services.leaderboard.api_token` en `ADMIN_PASSWORD`.

4. Logging / auditing (prioriteit: laag -> middel)

Log admin-logins en belangrijke CRUD-acties zodat wijzigingen te reconstrueren zijn.

5. Tests (prioriteit: laag -> middel)

Voeg feature-tests toe die:

-   een geldige POST met token een 201 teruggeeft
-   een ongeldige token een 401 teruggeeft

## Bestanden die bij deze controle horen

-   `routes/api.php`
-   `app/Http/Middleware/CheckLeaderboardApiToken.php`
-   `app/Http/Controllers/Api/ScoreController.php`
-   `resources/views/leaderboard/index.blade.php`
-   `app/Http/Controllers/Admin/AuthController.php`
-   `app/Http/Middleware/AdminAuth.php`
-   `app/Models/Score.php`

## Volgende stappen (optioneel)

-   Ik kan de logout-verbetering direct doorvoeren en committen.
-   Ik kan throttling toevoegen aan de API-route.
-   Ik kan een kort script toevoegen met de commando's om deze checks te reproduceren.

Einde.

-   Statische zoekopdrachten (grep) naar security-patronen — matches en bestanden genoemd hierboven.
