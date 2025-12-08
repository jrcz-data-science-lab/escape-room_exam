# Vaststellingsdocument – Software Developer (Crebo 25604)

## Escape Room Leaderboard Application

---

## Realiseert software

### 1. Wat is het doel van de applicatie? Welk probleem los je op of welke oplossing bied je aan?

**Doel:** Het leaderboard-systeem biedt een centrale plaats waar deelnemers van een escape-room hun scores kunnen indienen en zien hoe zij scoren ten opzichte van anderen.

**Probleem:** Escape-rooms hebben behoefte aan real-time feedback en ranken van scores. Handmatige tracking is foutgevoelig en minder gebruiksvriendelijk.

**Oplossing:** Dit is een webapplicatie die:

-   Geautomatiseerd scores inzamelt via een veilige API
-   Real-time een ranglijst weergeeft (publiek)
-   Admins een interface biedt voor beheer (aanpassen/verwijderen van scores)
-   Gebruiksvriendelijk en snel inzetbaar is

---

### 2. Welke soorten eindgebruikers gaan de applicatie gebruiken?

-   **Publieke gebruikers / Score-indieners:** Bezoekers/deelnemers van de escape-room die hun scores willen indienen via het webformulier.
-   **API-consumers:** Systemen of kiosken die scores programmatisch indienen met een API-token.
-   **Admin / Beheerders:** Medewerkers van de escape-room die scores kunnen valideren, corrigeren of verwijderen.
-   **Examinatoren (CREBO):** Die volledige inzage in de broncode, database, en testen krijgen.

---

### 3. Beschrijf uitgebreid (functioneel) wat voor soort applicatie je gaat maken/bouwen.

**Applicatietype:** Web-based leaderboard met twee interface-laags (publiek + admin).

**Functioneel overzicht:**

1. **Publieke Leaderboard (GET /):**

    - Toont een ranglijst van alle scores, gesorteerd van hoog naar laag.
    - Bevat invoervelden voor naam en score.
    - Bevat een token-invoerveld (voor API-toegang).
    - Auto-refresh elke 30 seconden.
    - Toont alleen indiening-ui, niet admin-functies.

2. **Score-indiening (POST /api/scores):**

    - Client-side JavaScript fetch naar `/api/scores`.
    - Vereist `X-Token` header (API-token).
    - Payload: `{ "player_name": "<naam>", "score": <getal> }`.
    - Server-side validatie (naam string max 255 tekens, score integer ≥ 0).
    - Bij succes: 201 response met nieuw record.
    - Bij fout: 401 (invalid token) of validation error.

3. **Admin Interface (GET /admin, POST /admin/login):**

    - Login-form met wachtwoord.
    - Dashboard met paginatie (25 records per pagina).
    - Edit-form per score (naam + score).
    - Delete-button per score.
    - Logout-knop.
    - Beveiligd met sessie-flag + middleware.

4. **Admin Authenticatie:**
    - Environment-variabele `ADMIN_PASSWORD`.
    - Session-flag `is_admin` na succesvolle login.
    - Middleware `AdminAuth` blokkeert ongeauthenticeerde requests.

**Dataflow:**

```
Publieke gebruiker → Voer naam/score/token in → Klik "Verstuur score"
                     ↓
                   JavaScript fetch naar /api/scores (POST)
                     ↓
                   Middleware CheckLeaderboardApiToken controleert X-Token
                     ↓
                   Controller valideert JSON input
                     ↓
                   Score::create() slaat op in database
                     ↓
                   Response 201 + record teruggegeven
                     ↓
                   Frontend reload en toont nieuwe score
```

---

### 4. Beschrijf de te realiseren User Interface (UI) van de applicatie.

**Publieke UI (resources/views/leaderboard/index.blade.php):**

-   Header met "Leaderboard"-titel en navigatie (login-link voor admins).
-   Tabel: `# | Speler | Score` (responsive layout, Tailwind CSS).
-   Formulier: Token-input (password-field, Toon-knop), Naam-input, Score-input (number field), Verstuur-knop.
-   Alert-meldingen (groen voor succes, rood voor fouten).
-   Automatische refresh elke 30 seconden.

**Admin UI (resources/views/admin/):**

-   `login.blade.php`: Eenvoudig login-formulier (wachtwoord-input, Inloggen-knop).
-   `index.blade.php`: Tabel met scores (kolommen: #, Speler, Score, Bewerkingen). Knoppen: Edit, Verwijderen. Paginatie-links.
-   `edit.blade.php`: Form met Naam en Score-velden, Update- en Annuleren-knoppen.

**Design / Stijl:**

-   Tailwind CSS (utility-first CSS framework).
-   Donkere koppelingen (black navbar), licht grijs body.
-   Responsive (mobiel-friendly).
-   Minimalistisch, snel laadbaar.

---

### 5. Beschrijf de te realiseren of uit te breiden databronnen die betrekking hebben op de applicatie.

**Database: SQLite (standaard Laravel) / MySQL (productie).**

**Schema (Migrations):**

-   Tabel `scores`:
    -   `id` (Primary Key, auto-increment)
    -   `player_name` (string, max 255)
    -   `score` (integer, nullable)
    -   `time_seconds` (integer, nullable)
    -   `game_id` (string, nullable)
    -   `submitted_from_ip` (string, nullable)
    -   `created_at`, `updated_at` (timestamps)

**Databronnen:**

-   `database/migrations/2025_10_30_123625_create_scores_table.php` — definiëert schema.
-   `app/Models/Score.php` — Eloquent model met `$fillable` voor mass-assignment.
-   `.env` file — bevat `ADMIN_PASSWORD`, `DB_CONNECTION`, `DB_DATABASE`.
-   `config/services.php` — bevat API-token configuratie (`services.leaderboard.api_token`).

**Data-retentie:**

-   Scores worden indefiniëf opgeslagen (geen automatische cleanup).
-   IP's kunnen geanonimiseerd/gehashed worden (privacy-voorzorgsmaatregel).

---

### 6. Voor welke platform(en) wordt de applicatie gemaakt en waarom?

**Platform(en):**

-   **Web (HTTP/HTTPS)** — primaire platform.
-   **Desktop**: Via webbrowser (Chrome, Firefox, Safari, Edge).
-   **Mobiel**: Responsive design, ook op smartphone/tablet.

**Waarom web?**

-   Eenvoudig in-en uitroll (geen installatie nodig).
-   Cross-platform (iOS, Android, Windows, macOS).
-   Gemakkelijk admin-toegang (van overal).
-   Real-time updates (auto-refresh op publieke pagina).
-   Schaalbaar voor toekomstige uitbreidingen (push notifications, analytics).

---

### 7. Met welke programmeertalen wordt de applicatie gerealiseerd?

-   **Backend:** PHP (7.4+) — Laravel framework.
-   **Frontend:** HTML5, CSS3 (Tailwind CSS), JavaScript (vanilla, fetch API).
-   **Database:** SQL (via Laravel migrations/Eloquent ORM).

---

### 8. Welke ontwikkeltools heb je nodig of ga je gebruiken in dit project?

-   **IDE:** Visual Studio Code (VS Code).
-   **Server:** Laravel Development Server (`php artisan serve`).
-   **Database:** SQLite (dev), MySQL (production-ready).
-   **Versiebeheer:** Git + GitHub.
-   **Package Manager:** Composer (PHP dependencies).
-   **Testing:** PHPUnit + Laravel Testing Utilities.
-   **API Testing:** Postman of cURL.
-   **Deployment:** Mogelijk Azure App Service of shared hosting.

---

### 9. Hoe wordt security in de applicatie gerealiseerd?

**API-beveiliging:**

-   API-token verificatie via `X-Token` header (middleware `CheckLeaderboardApiToken`).
-   Token opgeslagen in `config/services.php`, niet hard-coded.
-   HTTPS enforced (in productie).

**Admin-beveiliging:**

-   Wachtwoord-based authenticatie (`ADMIN_PASSWORD` env-variabele).
-   Session-flags (`is_admin`) na login.
-   Middleware `AdminAuth` blokkeert ongeauthenticeerde requests.
-   CSRF-token vereist voor state-changing requests.
-   Session invalidatie bij logout (voorkomt sessiefixatie).

**Input-validatie:**

-   Server-side validatie verplicht (client-side kan omzeild worden).
-   `player_name`: required, string, max 255 tekens.
-   `score`: required, integer, minimaal 0.
-   Geen SQL-injectie mogelijk (Eloquent ORM parametrized queries).
-   Geen XSS-risico (token in RAM, niet in localStorage).

**Best practices (productie):**

-   Gehashte admin-wachtwoorden (bcrypt via Laravel auth).
-   Rate-limiting op API-endpoints.
-   Secrets in environment variabelen / secret manager (Azure Key Vault).
-   Logging van alle admin-acties (audit trail).
-   HTTPS + HSTS headers.
-   CORS configuratie (if exposed to third-party clients).

---

### 10. Met welke frameworks wordt de applicatie gerealiseerd?

-   **Backend:** Laravel 11+ (PHP framework).
-   **Frontend:** Tailwind CSS (CSS framework).
-   **Testing:** PHPUnit (Laravel's built-in test suite).
-   **Database ORM:** Eloquent (Laravel's ORM).

---

### 11. Kan er een onafhankelijke testomgeving ingezet worden in de organisatie?

**Ja.**

-   **Development-omgeving:** Local (`php artisan serve`).
-   **Staging-omgeving:** Aparte server/database (kan Azure App Service zijn).
-   **Production-omgeving:** Live-applicatie.

Elke omgeving heeft eigen `.env` file met eigen API-token, admin-wachtwoord, en database.

---

### 12. Heb je de mogelijkheid om de applicatie te laten testen los van de oplevering?

**Ja.**

-   **Unit-tests:** `tests/Unit/ScoreModelTest.php` (model-logica).
-   **Feature-tests:** `tests/Feature/ExampleTest.php` (API-endpoints, admin-routes).
-   **Manual testing:** Via Postman of browser.
-   **Test-checklist:** Zie `docs/testplan.md`.

Tests kunnen onafhankelijk van oplevering gerund worden met `php artisan test`.

---

### 13. Heb je de mogelijkheid om versiebeheer toe te passen?

**Ja.**

-   **Git:** Lokaal + remote op GitHub.
-   **Commits:** Semantische commit-boodschappen (feat, fix, docs, test).
-   **Branches:** `main` (stable), `develop` (WIP).
-   **Tags:** Release-tags (v1.0, v1.1, enz.).
-   **PR/Code-review:** Via GitHub pull requests.

---

### 14. Heb je de mogelijkheid om bug tracking toe te passen?

**Ja.**

-   **GitHub Issues:** Voor bugs, features, en taken.
-   **Trello / Kanban:** Voor sprint-planning (backlog, in progress, done).
-   **Severity-labels:** critical, high, medium, low.
-   **Assignment:** Wie aan welke issue werkt.

---

### 15. Kun je tijdens de examens volledige inzage geven in de projectbestanden (broncode, database, versiebeheer, backlog)?

**Ja.**

-   **Broncode:** Repository-inhoud (`app/`, `routes/`, `resources/`, `database/`).
-   **Database:** Live database + migrations (`database/migrations/`).
-   **Versiebeheer:** Git log + GitHub history.
-   **Backlog:** `docs/todo_export.csv` en GitHub Issues.
-   **Documentatie:** `docs/` folder (design, testplan, project-plan).
-   **Tests:** `tests/` folder + test-resultaten.

Alles is in de repository beschikbaar voor audit.

---

## Werkt in een ontwikkelteam

### 16. Uit welke personen bestaat jouw ontwikkelteam?

**Teamsamenstelling:**

-   **Jij:** Full-stack developer (design, backend, frontend, testing, deployment).
-   **Examinatoren (CREBO):** Review en assessment.
-   **Optioneel (niet vereist voor deze opdracht):** Peer-review door medestudenten.

Dit is primair een individueel project, maar in een organisatie zou het team bestaan uit:

-   Backend-developer (Laravel-expert).
-   Frontend-developer (UI/UX).
-   QA-engineer (testing).
-   DevOps (deployment, infrastructure).

---

### 17. Kun je in de organisatie actief deelnemen aan werkoverleg?

**Ja.**

-   **Sprint-planning:** Wekelijks (backlog prioritering).
-   **Daily standup:** Korte updates (wat gedaan, wat volgende, blockers).
-   **Retrospective:** Elke sprint (wat ging goed, verbeteringsopportuniteiten).
-   **Sprint-review:** Demo van gerealiseerde features.

Voor deze individuele opdracht: zelf-evaluatie + feedback van examinatoren.

---

### 18. Kan er iteratief gewerkt worden volgens de AGILE/SCRUM-methodiek?

**Ja.**

-   **Sprints:** 1-week sprints (4 sprints totaal, ~4 weken project).
-   **Backlog-items:** User stories + taken (in `docs/todo_export.md`).
-   **Estimatie:** Story points (t-shirt sizing: S, M, L).
-   **Definition of Done:** Code merged, tests groen, dokumentatie bijgewerkt.

Voorbeeld sprint:

```
Sprint 1 (Week 1): Setup + Score model
- Setup Laravel project ✓
- Create Score model + migration ✓
- Setup database ✓

Sprint 2 (Week 2): API endpoints
- POST /api/scores endpoint ✓
- Token middleware ✓
- Input validation ✓
- Tests ✓

Sprint 3 (Week 3): Admin interface
- Admin login ✓
- Admin dashboard ✓
- Edit/delete scores ✓

Sprint 4 (Week 4): Testing & docs
- Full test coverage ✓
- Documentation ✓
- Performance tuning ✓
- Demo & examen-voorbereiding ✓
```

---

### 19. Wordt er een andere projectmethodiek gebruikt dan AGILE/SCRUM?

**Nee, niet van toepassing.**

Voor dit project wordt AGILE/SCRUM gebruikt (zie vraag 18).

---

## Bij antwoord "Nee"

### 20. Motiveer ieder antwoord dat je met "Nee" hebt ingevuld.

**Geen "Nee"-antwoorden.** Alle vragen zijn met "Ja" beantwoord of zijn niet van toepassing.

---

## Acceptatiecriteria

### 21. Beschrijf de acceptatiecriteria van jouw applicatie.

**Functioneel (Must-have):**

1. ✓ **Publieke leaderboard werkt:**

    - GET / toont een tabel met alle scores, gesorteerd van hoog naar laag.
    - Auto-refresh elke 30 seconden.
    - Geen admin-opties zichtbaar voor gewone bezoekers.

2. ✓ **Score-indiening via formulier:**

    - Gebruiker vult naam, score en token in.
    - JavaScript fetch POST naar `/api/scores`.
    - Server valideert en slaat op (201 response).
    - Foutmeldingen tonen bij invalid input of token.

3. ✓ **API-endpoint `/api/scores` (POST):**

    - Vereist `X-Token` header.
    - Payload: `{ "player_name", "score" }`.
    - Server-side validatie (naam string, score integer ≥ 0).
    - 201 response bij succes, 401 bij invalid token.

4. ✓ **Admin-login werkt:**

    - GET `/admin/login` toont login-form.
    - POST `/admin/login` controleert wachtwoord.
    - Bij succes: session('is_admin') = true, redirect naar admin-dashboard.

5. ✓ **Admin-dashboard werkt:**

    - GET `/admin/` toont tabel met alle scores (paginatie 25 per pagina).
    - Edit-functie: formulier voorbevolkt, update slaat op.
    - Delete-functie: verwijdert score.
    - Logout: invalidate sessie, redirect naar publieke page.

6. ✓ **Security:**
    - API-token middleware controleert X-Token.
    - Admin-middleware blokkeert ongeauthenticeerde requests.
    - Server-side input validatie.
    - CSRF-tokens op alle state-changing forms.
    - Token niet in localStorage/sessionStorage.

**Technisch (Must-have):**

7. ✓ **Database schema:**

    - `scores` tabel met `player_name`, `score`, `time_seconds`, `game_id`, `submitted_from_ip`.
    - Migrations werken: `php artisan migrate` zet alles up.

8. ✓ **Laravel-best practices:**

    - Eloquent ORM (Score::create).
    - Middleware voor authenticatie/autorisatie.
    - Controllers voor business-logica.
    - Views (Blade templates) voor UI.
    - Environment-variabelen voor secrets.

9. ✓ **Tests:**

    - Unit-test voor Score-model.
    - Feature-tests voor API en admin-routes.
    - Tests draaien met `php artisan test` zonder fouten.

10. ✓ **Documentatie:**
    - README.md: hoe te installeren en runnen.
    - Project-plan.md: milestones en planning.
    - Design.md: security-overwegingen.
    - Testplan.md: test-cases.
    - Cheatsheet: examen-voorbereiding.

**Non-functioneel (Should-have):**

11. ~ **Performance:**

    -   Pagina laadt < 2 seconden.
    -   API-response < 500ms.
    -   Paginatie voor grote datasets (25 records per pagina).

12. ~ **Responsive design:**
    -   Werkt op desktop, tablet, mobiel.
    -   Tailwind CSS grid/flex layout.

**Acceptance Test Scenario:**

**Scenario 1: Score indienen**

```
Given: Gebruiker op publieke leaderboard-pagina
When: Vult naam "Jan", score "123", token "abc123" in
And: Klikt "Verstuur score"
Then: Score verschijnt in tabel
And: Success-melding toont
```

**Scenario 2: Admin score verwijderen**

```
Given: Admin ingelogd (session('is_admin') = true)
When: Admin-dashboard geopend
And: Klikt delete-knop op een score
Then: Score verdwijnt uit tabel
And: Redirect naar admin-dashboard met success-melding
```

**Scenario 3: Invalid token**

```
Given: Gebruiker op publieke pagina
When: Voert invalid token in ("wrong_token")
And: Klikt "Verstuur score"
Then: API retourneert 401
And: Error-melding toont: "Unauthorized - invalid API token"
```

---

## Ondertekening

**Stagiair/Student:** ************\_\_\_************  
**Datum:** ************\_\_\_************

**Bedrijfsleiding:** ************\_\_\_************  
**Datum:** ************\_\_\_************

**CREBO-Examinator:** ************\_\_\_************  
**Datum:** ************\_\_\_************

---

**Bijlagen:**

-   README.md (installatie & setup)
-   Project-plan.md (timeline & milestones)
-   Design.md (security & architektuurbeslissingen)
-   Testplan.md (test-cases)
-   docs/cheatsheet_one_pager_nl.md (examen-voorbereiding)

---

_(Einde vaststellingsdocument)_
