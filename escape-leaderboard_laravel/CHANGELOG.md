# 📋 Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

# Release Notes - Escape Room Leaderboard

## Versie 1.0.1 (2 april 2026)

### Overzicht

Deze beveiligingsrelease introduceert veilige wachtwoord hashing voor admin accounts en versterkt de authenticatie beveiliging. Met bcrypt/Argon2 hashing en database-gebaseerde authenticatie wordt de applicatie nu volledig beveiligd tegen wachtwoord gerelateerde aanvallen.

### Nieuwe features

- Veilige wachtwoord hashing met bcrypt/Argon2
- Database-gebaseerde admin authenticatie
- Automatische doorsturing naar dashboard voor ingelogde admins
- Verbeterde API token validatie met 4-staps controle
- Rate limiting op alle API endpoints (30 requests/minuut)

### Technische verbeteringen

- **Backend**
    - Laravel Hash facade geïmplementeerd voor authenticatie
    - AdminAuth middleware vernieuwd met Auth::check()
    - CheckLeaderboardApiToken middleware met strict validatie
    - DatabaseSeeder met gehashte admin gebruiker

- **Database**
    - `is_admin` boolean vlag toegevoegd aan users tabel
    - Gehashte wachtwoorden in database (ipv .env)
    - Geoptimaliseerde user authenticatie queries
    - IP logging voor audit trails behouden

- **Frontend**
    - Login formulier aangepast voor email + wachtwoord
    - Beveiligde password input fields
    - Automatische redirect voor ingelogde gebruikers
    - Foutmeldingen voor niet-admin gebruikers

### Security

- **Tokens**
    - API token validatie met strict string comparison
    - 4-staps validatie proces: token aanwezig, game slug, game bestaat, token klopt
    - Rate limiting ter preventie van brute force aanvallen
    - Token rotatie mogelijkheid behouden

- **Validatie**
    - Laravel Hash::make() voor wachtwoord hashing
    - Auth::check() en is_admin rol verificatie
    - Uitgebreide input validatie op login formulier
    - CSRF bescherming behouden op alle formulieren

### Breaking changes

- Admin login vereist nu email + wachtwoord (ipv alleen wachtwoord)
- .env ADMIN_PASSWORD configuratie is verwijderd
- Admin gebruiker moet worden aangemaakt via DatabaseSeeder

### Bug fixes

- Onveilige platte tekst wachtwoord opslag verwijderd
- Sessie-gebaseerde admin controle vervangen door database authenticatie
- Token validatie problemen opgelost met strict comparison
- Rate limiting correct geïmplementeerd op API endpoints

---

## Versie 1.0.0 (25 maart 2026)

### Overzicht

De Escape Room Leaderboard applicatie is een compleet systeem voor het beheren en weergeven van escape room scores. Met een veilige admin interface, publieke leaderboards en krachtige API endpoints biedt deze release een robuuste basis voor escape room locaties.

### Nieuwe features

- Complete leaderboard weergave per escape room
- Admin dashboard voor game en score beheer
- Per-game API token systeem voor externe integratie
- Publieke zoekfunctie voor spelers en scores
- Real-time score toevoeging via API
- Responsive design voor mobiele apparaten

### Technische verbeteringen

- **Backend**
    - Laravel 12.x framework met moderne architectuur
    - RESTful API endpoints met rate limiting
    - Eloquent ORM met geoptimaliseerde queries
    - Middleware voor beveiliging en validatie

- **Database**
    - Geoptimaliseerde database schema met indexes
    - Cascade delete voor data integriteit
    - IP logging voor audit trails
    - Foreign key constraints

- **Frontend**
    - Donker thema met glassmorphism effecten
    - Mobile-first responsive design
    - Real-time notificaties en feedback
    - Geoptimaliseerde CSS met Vite build

### Security

- **Tokens**
    - Unieke 40-karakter API tokens per game
    - Beveiligde token validatie met strict comparison
    - Rate limiting (30 requests per minuut)
    - Token rotatie mogelijkheid

- **Validatie**
    - Uitgebreide input validatie op alle endpoints
    - CSRF bescherming op alle formulieren
    - SQL injection preventie via Eloquent
    - XSS bescherming met Laravel escaping

### Breaking changes

- Geen breaking changes in deze release

### Bug fixes

- Token validatie voor per-game beveiliging opgelost
- IP logging correct geïmplementeerd voor audit trails
- Responsive design problemen op kleine schermen opgelost
- Donker thema consistentie over alle pagina's
- Input validatie voor alle API endpoints toegevoegd
- Wachtwoord masking tegen shoulder surfing geïmplementeerd

---

## [0.x.x] - Development Versions

### Development Phase

- Initial project setup
- Core functionality development
- Security implementation
- UI/UX development
- Performance optimization

---

## 📊 **Version Summary**

| Version | Release Date | Type  | Key Features       |
| ------- | ------------ | ----- | ------------------ |
| 1.0.0   | 2026-03-25   | Major | Production Release |
