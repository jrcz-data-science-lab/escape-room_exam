# Todo Export

Deze file bevat de volledige takenlijst (Trello-ready) voor het project en examenbewijzen.

---

-   [ ] Base Controller instellen
    -   Bestand: `app/Http/Controller.php` — Zorg dat de base controller correcte namespace en helper-methodes heeft.
        Acceptatiecriteria:
-   Controller laadt zonder syntax-fouten
-   Commentaar/usage toegevoegd
-   Routes die deze controller gebruiken blijven werken
-   [ ] Admin Auth controller
    -   Bestand: `app/Http/Controllers/Admin/AuthController.php` — Beheer admin login/logout en sessies.
        Acceptatiecriteria:
-   Login en logout endpoints werken
-   Validatie van credentials is aanwezig
-   Eventuele redirects voor admin-UI getest
-   [ ] Admin Score beheer controller
    -   Bestand: `app/Http/Controllers/Admin/ScoreAdminController.php` — CRUD voor scores in admin UI.
        Acceptatiecriteria:
-   Score lijst, edit, update en delete werken
-   Form-requests/validatie toegepast
-   Admin-only toegang via middleware
-   [ ] API Score controller
    -   Bestand: `app/Http/Controllers/Api/ScoreController.php` — Publieke API voor het aanleveren en opvragen van scores.
        Acceptatiecriteria:
-   Endpoints voor create/list beschikbaar en gedocumenteerd
-   API-token eller rate-limit middleware toegepast indien nodig
-   JSON responses volgen API-contract
-   [ ] Web Leaderboard controller
    -   Bestand: `app/Http/Controllers/Web/LeaderboardController.php` — Renders leaderboard views en filters.
        Acceptatiecriteria:
-   Pagina toont top scores
-   Paginatie en sortering werken
-   View data is gevalideerd en veilig (XSS-safe)
-   [ ] Middleware: AdminAuth
    -   Bestand: `app/Http/Middleware/AdminAuth.php` — Zorgt dat admin routes beveiligd zijn.
        Acceptatiecriteria:
-   Beveiliging voor admin routes actief
-   Redirect naar admin login bij ontbreken van rechten
-   Unit/feature test de toegang
-   [ ] Middleware: Authenticate
    -   Bestand: `app/Http/Middleware/Authenticate.php` — Algemene auth middleware.
        Acceptatiecriteria:
-   Werkt samen met Laravel auth guard
-   Redirect/JSON response correct voor web/api
-   [ ] Middleware: CheckLeaderboardApiToken
    -   Bestand: `app/Http/Middleware/CheckLeaderboardApiToken.php` — Valideert API token voor leaderboard endpoints.
        Acceptatiecriteria:
-   Afwijzing bij ontbrekend of ongeldig token
-   Logs/headers correct gezet
-   Tests voor accept/deny
-   [ ] Middleware: EncryptCookies
    -   Bestand: `app/Http/Middleware/EncryptCookies.php` — Cookie encryptie configuratie.
        Acceptatiecriteria:
-   Cookies blijven leesbaar door app
-   Geen regressie in sessie/CSRF
-   [ ] Middleware: LeaderboardTokenMiddleware
    -   Bestand: `app/Http/Middleware/LeaderboardTokenMiddleware.php` — Extra token checks voor leaderboard functionaliteit.
        Acceptatiecriteria:
-   Specifieke header of param gecontroleerd
-   Compatibel met API routes
-   [ ] Middleware: PreventRequestsDuringMaintenance
    -   Bestand: `app/Http/Middleware/PreventRequestsDuringMaintenance.php` — Stopt requests tijdens maintenance.
        Acceptatiecriteria:
-   Laravel maintenance modus respecteert deze middleware
-   API krijgt juiste statuscode
-   [ ] Middleware: RedirectIfAuthenticated
    -   Bestand: `app/Http/Middleware/RedirectIfAuthenticated.php` — Redirects voor reeds ingelogde gebruikers.
        Acceptatiecriteria:
-   Gebruikers worden correct doorgestuurd
-   Testcases voor web flows
-   [ ] Middleware: TrimStrings
    -   Bestand: `app/Http/Middleware/TrimStrings.php` — Trims input strings.
        Acceptatiecriteria:
-   Whitespace verwijderd van input
-   Exclude arrays en bestanden nog correct
-   [ ] Middleware: TrustProxies
    -   Bestand: `app/Http/Middleware/TrustProxies.php` — Proxy headers configuratie.
        Acceptatiecriteria:
-   Proxy headers juist geïnterpreteerd
-   Correcte APP_URL en scheme bij proxied requests
-   [ ] Middleware: ValidateSignature
    -   Bestand: `app/Http/Middleware/ValidateSignature.php` — Ondertekende URL-validatie.
        Acceptatiecriteria:
-   Ongeldige tekeningen resulteren in 403
-   Gekeurde routes blijven werken
-   [ ] Middleware: VerifyCsrfToken
    -   Bestand: `app/Http/Middleware/VerifyCsrfToken.php` — CSRF bescherming.
        Acceptatiecriteria:
-   Forms met CSRF-token geaccepteerd
-   Exclusions (API endpoints) correct ingesteld
-   [ ] Kernel configuratie
    -   Bestand: `app/Http/Kernel.php` — Register middleware groups en route middleware.
        Acceptatiecriteria:
-   Alle middleware geregistreerd
-   Middleware groepen voor web/api correct ingesteld
-   Geen duplicate of ontbrekende middleware
-   [ ] Model: Score
    -   Bestand: `app/Models/Score.php` — Eloquent model voor scores.
        Acceptatiecriteria:
-   `$fillable` bevat de juiste velden
-   Relaties (indien aanwezig) correct
-   Model heeft passende casts en mutators
-   Unit test aanwezig (`tests/Unit/ScoreModelTest.php` bestaat en passed)
-   [ ] Model: User
    -   Bestand: `app/Models/User.php` — Eloquent model voor gebruikers.
        Acceptatiecriteria:
-   Auth-guard compatibel
-   `$fillable`, `$hidden` en casts correct ingesteld
-   Eventuele admin-flag (is_admin) aanwezig en getest
-   [ ] Service Provider: AppServiceProvider
    -   Bestand: `app/Providers/AppServiceProvider.php` — App-brede bindings en bootstrapping.
        Acceptatiecriteria:
-   Bindings werken zonder errors
-   Eventuele view composers of macros goed geregistreerd
-   Performance: geen zware operaties in boot() die tijdens iedere request draaien
-   [ ] B1-K1 W1 — Plant werkzaamheden en bewaakt voortgang
    -   Omschrijving: Je maakt een planning, stelt doelen, bewaakt voortgang en communiceert met opdrachtgever of team.
        Mogelijk bewijs / voorbeelden:
-   Projectplanning (Gantt of backlog), Trello-board, logboek, sprintplanning
    Mapped files / plekken in repo:
-   `docs/project-plan.md` (nieuw)
-   Todo/Trello board: deze todo-lijst (export naar CSV/JSON)
    Subtaken (concrete acties):
-   [ ] Maak `docs/project-plan.md` met milestones en scope
-   [ ] Exporteer deze todo-lijst naar CSV/JSON voor Trello import
-   [ ] Zet backlog items (prioriteit) op Trello en koppel links in `docs/project-plan.md`
        Acceptatiecriteria:
-   `docs/project-plan.md` aanwezig
-   Trello-board/backlog bestaat of CSV/JSON export beschikbaar
-   [ ] B1-K1 W2 — Ontwerpt software
    -   Omschrijving: Je maakt een (deel)ontwerp dat aansluit op eisen/wensen; onderbouw keuzes en houdt rekening met privacy & veiligheid.
        Mogelijk bewijs / voorbeelden:
-   UML-diagrammen, wireframes, ERD, functioneel ontwerp
    Mapped files / plekken in repo:
-   `docs/erd.md` (nieuw)
-   `docs/design.md` (nieuw)
-   `resources/views/` (wireframes / screenshots)
    Subtaken:
-   [ ] Maak `docs/erd.md` met ERD van `scores` en `users`
-   [ ] Documenteer privacy/security overwegingen in `docs/design.md`
-   [ ] Voeg wireframes/schetsen (png/pdf) toe aan `docs/` of `resources/views/` als voorbeelden
        Acceptatiecriteria:
-   ERD of UML diagram aanwezig
-   Ontwerpkeuzes gedocumenteerd met privacy/security motivering
-   [ ] B1-K1 W3 — Realiseert (onderdelen van) software
    -   Omschrijving: Je programmeert onderdelen van software volgens codeconventies. De code is leesbaar en werkt.
        Mogelijk bewijs / voorbeelden:
-   Github-repo, codevoorbeelden, werkende demo
    Mapped files / plekken in repo (kernimplementatie):
-   `app/Http/Controllers/Web/LeaderboardController.php`
-   `app/Http/Controllers/Api/ScoreController.php`
-   `app/Http/Controllers/Admin/ScoreAdminController.php`
-   `app/Models/Score.php`
-   `app/Models/User.php`
-   `app/Providers/AppServiceProvider.php`
    Subtaken:
-   [ ] Zorg dat leaderboard en API endpoints werken (basic smoke test)
-   [ ] Maak betekenisvolle commits met duidelijke messages
-   [ ] Publiceer korte demo of screenshots in `docs/` (zoals `docs/demo.md`)
        Acceptatiecriteria:
-   Kernfunctionaliteit geïmplementeerd en documentatie aanwezig
-   Commits met betekenisvolle messages aanwezig
-   Basis tests draaien groen (phpunit passing)
-   [ ] B1-K1 W4 — Test software
    -   Omschrijving: Je maakt testscenario’s, voert testen uit (unit/integratie/acceptatie) en rapporteert resultaten.
        Mogelijk bewijs / voorbeelden:
-   Testplan, bugrapporten, screenshots testresultaten
    Mapped files / plekken in repo:
-   `docs/testplan.md` (nieuw)
-   `tests/Unit/` (unit tests)
-   `tests/Feature/` (feature/integratietests)
    Subtaken:
-   [ ] Maak `docs/testplan.md` met testcases en acceptance criteria
-   [ ] Verifieer en documenteer bestaande `tests/Unit/ScoreModelTest.php` uitvoering
-   [ ] Voeg één feature test toe die API create/list covered
-   [ ] Voeg test-run artifact (screenshot of `php artisan test` output) toe aan `docs/test-results/`
        Acceptatiecriteria:
-   Testplan aanwezig
-   Tests draaien en resultaten gedocumenteerd
-   [ ] B1-K1 W5 — Doet verbetervoorstellen voor software
    -   Omschrijving: Je analyseert feedback en testresultaten en doet concrete verbeteringsvoorstellen.
        Mogelijk bewijs / voorbeelden:
-   Versiebeheer commits, changelog, verbeterplan
    Mapped files / plekken in repo:
-   `docs/verbeterplan.md` (nieuw)
-   `CHANGELOG.md` (nieuw of bijgewerkt)
    Subtaken:
-   [ ] Maak `docs/verbeterplan.md` met prioriteit en acties
-   [ ] Voeg changelog entries toe bij belangrijke commits in `CHANGELOG.md`
-   [ ] Koppel verbeterpunten aan issues of Trello-cards
-   [ ] B1-K2 W1 — Voert overleg
    -   Omschrijving: Je neemt actief deel aan teamoverleg, communiceert helder en maakt afspraken.
        Mogelijk bewijs / voorbeelden:
-   Verslag van meetings, feedback teamleden
    Mapped files / plekken in repo:
-   `docs/meetings/` (folder met meeting-minutes)
    Subtaken:
-   [ ] Voeg één of meer meeting minutes toe in `docs/meetings/`
-   [ ] Koppel action-items aan Trello-cards of issues
        Acceptatiecriteria:
-   Meeting minutes aanwezig
-   Afspraken met toegewezen acties zichtbaar
-   [ ] B1-K2 W2 — Presenteert het opgeleverde werk
    -   Omschrijving: Je presenteert het resultaat en licht keuzes toe (inhoudelijk en technisch).
        Mogelijk bewijs / voorbeelden:
-   Presentatie, video-opname, demo
    Mapped files / plekken in repo:
-   `docs/presentatie/` (slides, notities)
-   `docs/demo.md` (demo instructies)
    Subtaken:
-   [ ] Maak slides of korte demo instructie in `docs/presentatie/`
-   [ ] Maak opname of instructie voor live demo und voeg link toe in docs
        Acceptatiecriteria:
-   Slides/demo instructie beschikbaar
-   Opname of demo link aanwezig
-   [ ] B1-K2 W3 — Reflecteert op het werk
    -   Omschrijving: Je evalueert eigen werk en teamproces. Je benoemt verbeterpunten en geeft feedback.
        Mogelijk bewijs / voorbeelden:
-   Reflectieverslag, 360° feedback
    Mapped files / plekken in repo:
-   `docs/reflectie.md` (nieuw)
    Subtaken:
-   [ ] Schrijf reflectieverslag in `docs/reflectie.md`
-   [ ] Noem minimaal 3 verbeterpunten en acties
        Acceptatiecriteria:
-   Reflectieverslag aanwezig en concreet
-   [ ] Generieke onderdelen — Nederlands, Rekenen, Engels, L&B
    -   Omschrijving: Voldoet aan generieke eisen taal, rekenen en burgerschap (niveau 4).
        Mogelijk bewijs / voorbeelden:
-   Toetsresultaten, portfolio-opdrachten, L&B-verslagen
    Mapped files / plekken in repo:
-   `docs/generic/` (plaats om bewijsstukken te verzamelen)
    Subtaken:
-   [ ] Voeg bewijs of notities voor generieke onderdelen toe in `docs/generic/`
-   [ ] Documenteer status (klaar/afwachten toets)
        Acceptatiecriteria:
-   Bewijsstukken toegevoegd aan portfolio folder
-   [ ] Profieldeel Software Developer (P1) — Eindproject bewijs
    -   Omschrijving: Je laat zien dat je software kunt ontwikkelen die functioneel, veilig en gebruiksvriendelijk is.
        Mogelijk bewijs / voorbeelden:
-   Eindproject, examenopdracht, stageverslag
    Mapped files / plekken in repo:
-   `README.md` (project overview)
-   `docs/evidence/` (screenshot/demo/artifacts)
-   `docs/installation.md` (installatie instructies)
    Subtaken:
-   [ ] Werk `README.md` bij met features, installatiestappen en bewijsstukken
-   [ ] Voeg screenshots of demo link toe in `docs/evidence/`
-   [ ] Documenteer acceptatiecriteria van examenopdracht in `docs/evidence/acceptance.md`
        Acceptatiecriteria:
-   Volledige README en bewijsstukken aanwezig
-   Demo link of screenshots toegevoegd
