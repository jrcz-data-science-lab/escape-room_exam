# Testplan

## Doel

Beschrijf de teststrategie, testcases en gewenste acceptance criteria voor unit-, feature- en integratietests.

## Scope

-   Unit tests: modellen en kleine helperfuncties
-   Feature tests: API create/list, admin CRUD
-   Integratie: end-to-end smoke test van leaderboard flow

## Testcases (voorbeeld)

1. Unit: `Score` model heeft juiste `$fillable`-velden
    - Acceptance: PHPUnit test bestaat en passed (`tests/Unit/ScoreModelTest.php`)
2. Feature: API kan score aanmaken (POST) en lijst teruggeven (GET)
    - Acceptance: response 200/201 en JSON-schema klopt
3. Smoke: Web leaderboard toont top-scores
    - Acceptance: pagina laadt en toont ten minste 1 score

## Testuitvoering

-   Command voor unit/tests:

```powershell
php artisan test --filter=ScoreModelTest
```

## Artifacts

-   `docs/test-results/` (place test-run output, screenshots)

## Acties

-   [ ] Voeg testcases toe in `tests/Feature/` (API create/list)
-   [ ] Run tests en voeg resultaat toe in `docs/test-results/`
-   [ ] Documenteer testdata en test-setup (DB seeders, env)
