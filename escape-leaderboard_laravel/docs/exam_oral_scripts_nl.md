# Mondelinge Scripts — Leaderboard (Nederlands)

Gebruik deze korte scripts als spreektekst tijdens een mondeling examen. Ze zijn zo geformuleerd dat je ze makkelijk kunt onthouden en kort kunt voordragen.

## 🔐 Veiligheid — Architectuurkeuzes

**Waarom geen gebruikerslogin voor scores?**
Dit is een open leaderboard-applicatie. Gebruikers hoeven zich niet in te loggen — ze voeren alleen hun naam in. Dit maakt de UX eenvoudig en laagdrempelig. De beveiliging zit in de **API-token** (X-Token header), niet in gebruikersauthenticatie.

**Waarom zit de token niet in localStorage?**
Het token zou anders permanent bewaard blijven en kwetsbaar zijn voor XSS-aanvallen. Door het alleen in het invoerveld (RAM) te houden, wordt het gewist zodra de pagina sluit. Dit is minder persistent, maar veel veiliger.

**Waarom separate admin-authenticatie?**
De admin-interface (beheer scores, verwijderen) vereist wachtwoordbeveiliging via `ADMIN_PASSWORD` environment-variabele. Dit scheidt normale gebruikers (score invoeren) van beheerders (score beheren). De sessie-flag `is_admin` zorgt ervoor dat alleen ingelogde admins tot admin-routes kunnen.

**Server-side validatie — waarom essentieel?**
Client-side validatie (JavaScript) kan altijd worden omzeild. Server-side validatie is daarom verplicht: het valideert alle input (type, lengte, bereik) voordat het in de database gaat. Dit beschermt tegen injection, ongeldige data en kwaadaardige requests.

---

## 📝 Basisvragen

1. **Hoe wordt een nieuwe score toegevoegd?**
   "De client verstuurt een POST request naar `/api/scores` met een JSON-body die `player_name` en `score` bevat. De server controleert eerst of de meegezonden `X-Token` header overeenkomt met de token uit de environment. Vervolgens voert hij veldvalidatie uit — score moet een integer zijn en minimaal 0 — en slaat het record op met `Score::create()`. Bij succes wordt er een 201-status teruggegeven met het nieuwe record."

2. **Hoe is de admin-authenticatie opgezet?**
   "Het admin-wachtwoord staat in de environment-variabele `ADMIN_PASSWORD`. De login controller vergelijkt het ingevoerde wachtwoord daarmee en bij succes wordt `session('is_admin')` op true gezet. Een middleware `AdminAuth` controleert deze sessie-flag voor toegang tot admin-routes; zonder deze flag word je teruggericht naar de login-pagina."

3. **Wat doet de frontend met tokens?**
   "Tokens worden alleen in het invoerveld bewaard en niet in localStorage of sessionStorage. Ze blijven dus alleen in het RAM aanwezig terwijl de pagina open is. Er is een knop ('Toon'/'Verberg') om het token tijdelijk zichtbaar te maken. Dit beperkt blootstelling aan XSS-aanvallen en lost het token automatisch op bij page-close."

4. **Wat is het doel van server-side validatie?**
   "Server-side validatie is verplicht omdat client-side validatie kan worden omzeild via developer tools of directe API-calls. Het beschermt de database en de business logic tegen ongeldige of kwaadaardige invoer. In deze app: `player_name` moet string zijn, max 255 tekens; `score` moet integer zijn, minimaal 0."

5. **Noem twee verbeteringen voor productie-beveiliging.**
   "Eerste: gebruik gehashte wachtwoorden en echte gebruikersaccounts (via Laravel Fortify of Sanctum) in plaats van plain-text wachtwoord in env. Tweede: implementeer rate-limiting (throttle middleware) op de API-endpoint om brute-force attacks en DoS te voorkomen. Derde: bewaar gevoelige tokens in een secrets manager (Azure Key Vault, HashiCorp Vault) en voer logging/auditing uit van alle admin-acties."

6. **Hoe test je lokaal of de API werkt?**
   "Voer `php artisan migrate` uit om de database op te zetten, start de server met `php artisan serve`, en test de endpoint `/api/scores` met Postman of curl. POST een JSON-body met `player_name` en `score`, zet de `X-Token` header, en check of je een 201-respons en het nieuwe record terugkrijgt."

---

## 📊 Dataweergave & Queries

7. **Hoe werkt paginatie in de admin-interface?**
   "De admin-controller gebruikt `Score::orderByDesc('score')->paginate(25)`. Dit haalt scores op, gesorteerd van hoog naar laag, in pagina's van 25 records per pagina. Laravel genereert automatisch de paginalinks en navigatie-buttons."

8. **Hoe ga je om met caching van views bij wijzigingen?**
   "Voer `php artisan view:clear` uit om gecompileerde Blade-views te verwijderen uit `storage/framework/views/`. Hierna worden wijzigingen in `.blade.php`-bestanden direct zichtbaar zonder server restart."

9. **Hoe leg je rate-limiting uit in één zin?**
   "Rate-limiting voorkomt dat één gebruiker of IP te veel verzoeken in korte tijd doet en beschermt zo tegen misbruik, brute-force en DoS-achtige situaties."

---

## 🔍 Extra (optioneel voor diepgaande vragen)

10. **Wat staat in de Score-model `$fillable` array en waarom?**
    "De `$fillable` array definieert welke velden mass-assignment toestaan: `player_name`, `time_seconds`, `score`, `game_id`, `submitted_from_ip`. Dit zorgt ervoor dat we veilig `Score::create($validated)` kunnen gebruiken zonder kwetsbaarheden. Velden die NIET in `$fillable` staan, kunnen niet via mass-assignment veranderd worden."

11. **Waarom wordt het logout-token na uitloggen vernieuwd?**
    "De logout-actie roept `session()->invalidate()` en `session()->regenerateToken()` aan. Dit wist alle sessiegegevens en genereert een nieuw CSRF-token, wat sessiefixatie-aanvallen voorkomt en zorgt voor een schone sessie-break."
