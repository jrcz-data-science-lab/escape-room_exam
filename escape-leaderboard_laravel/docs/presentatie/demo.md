# Demo instructies

## Doel

Stapsgewijze instructie om de app lokaal te draaien en demo-functionaliteit te tonen.

## Voorwaarden

-   PHP 8.x
-   Composer dependencies `composer install`
-   .env met juiste DB instellingen

## Starten

```powershell
# Installeer dependencies
composer install
# Migrate en seed (optioneel)
php artisan migrate --seed
# Start dev server
php artisan serve
```

## Demo stappen

1. Ga naar `/leaderboard` en toon top scores
2. Gebruik API endpoint `/api/scores` om score te posten
3. Open admin UI (`/admin/scores`) om scores te bewerken

## Acties

-   [ ] Vul demo-data in indien nodig
-   [ ] Voeg screenshots of video-opname toe in `docs/evidence/`
