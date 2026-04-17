# Escape Room Leaderboard System

Dit project is een moderne webapplicatie voor het beheren en real-time weergeven van ontsnappingstijden. Het systeem is ontwikkeld met de focus op een snelle gebruikerservaring in een fysieke escape room omgeving, waar zowel game-masters als spelers direct inzicht nodig hebben in de actuele ranglijsten.

## Projectomschrijving
In plaats van statische lijstjes biedt dit systeem een dynamisch platform. Het doel was om een tool te bouwen die niet alleen functioneel is, maar ook de sfeer van een escape room ondersteunt door middel van een "dark-first" interface en snelle dataverwerking.

### Kernfunctionaliteiten
* **Real-time Leaderboard:** De ranglijst wordt direct bijgewerkt zodat records meteen zichtbaar zijn voor het publiek.
* **Admin Dashboard:** Een beveiligde omgeving voor game-masters om scores te valideren, teams aan te maken en games te beheren.
* **Security-first:** Voorzien van multi-layer bescherming tegen onbevoegde toegang en data-manipulatie.
* **Mobile-first:** Volledig responsive design, geoptimaliseerd voor tablets en telefoons tijdens de spelbegeleiding.

## Technische Architectuur
De applicatie rust op een solide backend met moderne frontend-tooling:

* **Framework:** Laravel 12 (PHP 8.2+)
* **Frontend:** Tailwind CSS voor de styling en Vite voor efficiënt asset management.
* **Database:** MySQL (geoptimaliseerd met indexen voor snelle score-opvragingen).
* **Beveiliging:** Gebruik van CSRF-protection, strikte input-validatie en IP-logging via Middleware om misbruik te voorkomen.

---

## Installatie & Setup

Volg de onderstaande stappen om de applicatie in een lokale ontwikkelomgeving te draaien:

1. **Clone de repository:**
   ```bash
   git clone [https://github.com/jouw-username/escape-leaderboard.git](https://github.com/jouw-username/escape-leaderboard.git)
   cd escape-leaderboard
Dependencies installeren:

Bash
composer install
npm install
Configuratie:
Maak een kopie van het omgevingsbestand, configureer de database en genereer de applicatie-key:

Bash
cp .env.example .env
php artisan key:generate
Database Migraties:
Maak de tabellen aan en vul de database met testdata (optioneel):

Bash
php artisan migrate --seed
Server opstarten:
Draai de lokale server en de frontend build-tool (bij voorkeur in aparte terminals):

Bash
php artisan serve
npm run dev
Quality Assurance
Betrouwbaarheid is essentieel voor een scoresysteem. De applicatie is voorzien van geautomatiseerde tests om de integriteit van de score-berekeningen en de beveiliging te waarborgen:

Bash
php artisan test
Licentie & Contact
Dit project is gelicentieerd onder de MIT-licentie. Vragen, suggesties of bugs gevonden? Open gerust een issue of stuur een pull request.

Ontwikkeld door: [Jouw Naam]
