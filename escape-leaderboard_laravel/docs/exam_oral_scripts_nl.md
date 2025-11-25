# Mondelinge Scripts — Leaderboard (Nederlands)

Gebruik deze korte scripts als spreektekst tijdens een mondeling examen. Ze zijn zo geformuleerd dat je ze makkelijk kunt onthouden en kort kunt voordragen.

1. Hoe wordt een nieuwe score toegevoegd?
   "De client verstuurt een POST request naar /api/scores met een JSON-body die `player_name` en `score` bevat. De server controleert eerst of de meegezonden `X-Token` overeenkomt met de token uit de environment. Vervolgens voert hij veldvalidatie uit en slaat het record op met `Score::create()`. Bij succes wordt er een 201-status teruggegeven met het nieuwe record."

2. Hoe is de admin-authenticatie opgezet?
   "Het admin-wachtwoord staat in de environment-variabele `ADMIN_PASSWORD`. De login controller vergelijkt het ingevoerde wachtwoord daarmee en bij succes wordt `session('is_admin')` op true gezet. Een middleware `AdminAuth` controleert deze sessie-flag voor toegang tot admin-routes."

3. Wat doet de frontend met tokens?
   "Tokens worden alleen in het invoerveld bewaard en niet in localStorage of sessionStorage. Er is een knop om het token tijdelijk zichtbaar te maken. Dit beperkt blootstelling in de browser."

4. Wat is het doel van server-side validatie?
   "Server-side validatie is verplicht omdat client-side validatie kan worden omzeild. Het beschermt de database en de business logic tegen ongeldige of kwaadaardige invoer."

5. Noem twee verbeteringen voor productie-beveiliging.
   "Gebruik gehashte wachtwoorden en echte gebruikersaccounts; implementeer rate-limiting voor de API; bewaar gevoelige tokens in een secrets manager en voer logging/auditing uit."

6. Hoe test je lokaal of de API werkt?
   "Voer `php artisan migrate` uit, start de server met `php artisan serve`, en test de endpoint met Postman of via de frontend. Check headers en de 201-respons bij een succesvolle creatie."

7. Hoe werkt paginatie in de admin-interface?
   "De admin-controller gebruikt `paginate(25)` wat ervoor zorgt dat per pagina 25 records worden opgehaald en Laravel genereert automatisch de paginalinks."

8. Wat doet `Score::orderByDesc('score')->limit(10)->get()`?
   "Deze query haalt de top 10 scores op uit de database, gesorteerd van hoog naar laag."

9. Hoe ga je om met caching van views bij wijzigingen?
   "Voer `php artisan view:clear` uit om gecompileerde Blade-views te verwijderen zodat wijzigingen direct zichtbaar worden."

10. Hoe leg je rate-limiting uit in één zin?
    "Rate-limiting voorkomt dat één gebruiker of IP te veel verzoeken in korte tijd doet en beschermt zo tegen misbruik of DoS-achtige situaties."
