# Examenvragen & Antwoorden — Leaderboard (Nederlands)

Dit bestand bevat 10 veelvoorkomende examenvragen met korte voorbeeldantwoorden. Gebruik ze om mondelinge antwoorden te oefenen.

1. Hoe wordt een nieuwe score toegevoegd aan de applicatie?

Antwoord:
De frontend stuurt een POST naar `/api/scores` met JSON body `{ player_name, score }` en de header `X-Token`. De server valideert de token tegen `LEADERBOARD_API_TOKEN`, voert veldvalidatie uit en slaat het record op met `Score::create($validated)`. Bij succes retourneert de server HTTP 201 met het aangemaakte record.

2. Hoe voorkomt de applicatie dat iedereen scores kan posten?

Antwoord:
Er is token-gebaseerde beveiliging: de API verwacht een `X-Token` header met een waarde die overeenkomt met `LEADERBOARD_API_TOKEN` uit de `.env`. Zonder geldige token wordt het verzoek geweigerd.

3. Waar staat het admin-wachtwoord en hoe werkt admin-login?

Antwoord:
Het wachtwoord staat in de env-variabele `ADMIN_PASSWORD`. Bij login vergelijkt `AuthController` het ingevoerde wachtwoord met deze waarde. Bij een match zet de controller `session('is_admin') = true`. De `AdminAuth` middleware controleert deze sessie-flag voor toegang tot admin-routes.

4. Welke server-side validatie gebeurt bij het aanmaken van een score?

Antwoord:
`score` moet een integer zijn (min 0) en `player_name` moet een string zijn met maximaal 255 tekens. Dit wordt gedaan met `$request->validate([...])` in `Api\\ScoreController@store`.

5. Waarom is server-side validatie noodzakelijk als er client-side validatie is?

Antwoord:
Client-side validatie verbetert de gebruikerservaring, maar kan worden omzeild. Server-side validatie is de enige betrouwbare beveiliging tegen ongeldige of kwaadaardige input.

6. Hoe werkt paginatie in de admin interface?

Antwoord:
De admin-controller gebruikt `paginate(25)` om 25 records per pagina op te halen. Laravel genereert automatische paginalinks en laadt alleen benodigde data.

7. Noem drie productieverbeteringen voor beveiliging.

Antwoord:

1. Gebruik hashed wachtwoorden en een gebruikerssysteem (niet één env-wachtwoord).
2. Voeg rate-limiting toe aan de API (throttle middleware).
3. Sla API-keys/secret values op in een vault (bijv. AWS Secrets Manager) en rotatiebeleid.

4. Hoe voorkom je spam/overbelasting bij score submissions?

Antwoord:
Implementeer rate-limiting, controleer IP-adres en voeg server-side timing checks of captcha's toe voor verdachte activiteit.

9. Welke Eloquent-methodes zie je vaak en waarom zijn ze handig?

Antwoord:
`create()` voor mass-assignment, `orderByDesc()` en `limit()`/`paginate()` voor query's, en model-binding (`Score $score`) in controllers. Eloquent vermindert SQL-werk en beschermt tegen SQL-injecties.

10. Hoe test je de applicatie lokaal snel?

Antwoord:
Run migrations (`php artisan migrate`), start de server (`php artisan serve`) en test endpoints via browser of Postman. Voor geautomatiseerde tests gebruik je PHPUnit-testcases voor controllers en API endpoints.

---
