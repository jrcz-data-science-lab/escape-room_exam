# Beveiliging van de Escape Room App

Dit document legt uit hoe de beveiliging werkt in de Escape Room Leaderboard applicatie.

---

## Inhoud

1. [Overzicht](#overzicht)
2. [Admin Login Beveiliging](#admin-login)
3. [API Token Beveiliging](#api-token)
4. [Voorbeelden](#voorbeelden)

---

## Overzicht

De app heeft **twee beveiligingslagen**:

| #   | Beveiliging     | Voor wie?       | Waarom?                                           |
| --- | --------------- | --------------- | ------------------------------------------------- |
| 1   | **Admin Login** | Admin dashboard | Alleen beheerders mogen games beheren             |
| 2   | **API Token**   | API endpoints   | Alleen escape room devices mogen scores toevoegen |

---

## Admin Login

### Wat doet het?

Zorgt dat alleen beheerders toegang hebben tot het admin dashboard.

### Hoe werkt het?

```
┌──────────────────────────────────────────────────────┐
│  GEBRUIKER WIL ADMIN PAGINA BEZOEKEN                 │
│  Bijvoorbeeld: /admin/games                          │
└──────────────────────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────┐
│  CHECK 1: Is de gebruiker ingelogd?                  │
│                                                      │
│  • Check sessie cookie                               │
│  • Kijk in database of sessie geldig is              │
│                                                      │
│  ❌ Niet ingelogd → Stuur naar login pagina          │
│  ✅ Wel ingelogd → Ga naar Check 2                  │
└──────────────────────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────┐
│  CHECK 2: Is de gebruiker een admin?                 │
│                                                      │
│  • Haal gebruiker op uit database                    │
│  • Check het 'is_admin' veld                         │
│                                                      │
│  ❌ Geen admin → Uitloggen + foutmelding           │
│  ✅ Is admin → Toon de admin pagina                 │
└──────────────────────────────────────────────────────┘
```

### Voorbeeld: Goede Login

```
👤 Gebruiker: Admin User
📧 Email: admin@escape.com
🔑 Wachtwoord: test (correct)

Stap 1: Gebruiker vult login formulier in
Stap 2: Laravel checkt email + wachtwoord
Stap 3: ✅ Wachtwoord klopt → Login gelukt
Stap 4: Laravel maakt sessie aan
Stap 5: Gebruiker gaat naar /admin/games
Stap 6: ✅ Ingelogd + ✅ Is admin → Pagina wordt getoond
```

### Voorbeeld: Foute Login

```
👤 Gebruiker: Hacker
📧 Email: hacker@evil.com
🔑 Wachtwoord: verkeerd123

Stap 1: Hacker vult login formulier in
Stap 2: Laravel checkt email + wachtwoord
Stap 3: ❌ Wachtwoord fout → Weiger toegang
Stap 4: Toon foutmelding: "Ongeldige inloggegevens"
```

### Voorbeeld: Normale Gebruiker Probeer Admin

```
👤 Gebruiker: Test User (gewone gebruiker, geen admin)
📧 Email: test@example.com

Stap 1: Gebruiker is ingelogd (gewone login)
Stap 2: Gebruiker probeert /admin/games te openen
Stap 3: ✅ Ingelogd + ❌ Geen admin → GEEN TOEGANG
Stap 4: Laravel logt gebruiker uit
Stap 5: Stuur naar login met foutmelding: "Geen admin rechten"
```

---

## API Token

### Wat doet het?

Zorgt dat alleen geautoriseerde escape room devices scores kunnen toevoegen.

### Hoe werkt het?

```
┌──────────────────────────────────────────────────────┐
│  ESCAPE ROOM DEVICE WIL SCORE TOEVOEGEN              │
│                                                      │
│  POST /api/scores                                    │
│  Header: X-API-TOKEN: OhmYWL0ROif65...               │
│  Body: {game_slug: "escaperoom-1", score: 1000}     │
└──────────────────────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────┐
│  CHECK 1: Is er een token meegestuurd?               │
│                                                      │
│  • Check header 'X-API-TOKEN'                        │
│  • Check alternatieve header 'X-Token'               │
│  • Check URL parameter '?api_token=...'              │
│                                                      │
│  ❌ Geen token → Fout: "Unauthorized"                  │
│  ✅ Token gevonden → Ga naar Check 2                 │
└──────────────────────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────┐
│  CHECK 2: Voor welke game is dit?                    │
│                                                      │
│  • Check 'game_slug' in de data                      │
│  • Check 'game' in de data                           │
│                                                      │
│  ❌ Geen game → Fout: "game_slug is required"        │
│  ✅ Game gevonden → Ga naar Check 3                  │
└──────────────────────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────┐
│  CHECK 3: Bestaat deze game?                         │
│                                                      │
│  • Zoek in database: WHERE slug = 'escaperoom-1'     │
│                                                      │
│  ❌ Game niet gevonden → Fout: "unknown game"        │
│  ✅ Game gevonden → Ga naar Check 4                  │
└──────────────────────────────────────────────────────┘
                          │
                          ▼
┌──────────────────────────────────────────────────────┐
│  CHECK 4: Klopt het token?                           │
│                                                      │
│  • Vergelijk gestuurd token met token in database     │
│  • EXACTE match nodig (hoofdletters/kleine letters)  │
│                                                      │
│  ❌ Tokens verschillen → Fout: "invalid game token"  │
│  ✅ Tokens identiek → Score wordt opgeslagen!        │
└──────────────────────────────────────────────────────┘

### Voorbeeld: Verkeerd Token

```
👤 Hacker probeert score toe te voegen

Stap 1: Hacker stuurt request:

   POST /api/scores
   Header: X-API-TOKEN: foute-token-123

   Body:
   {
       "game_slug": "escaperoom-1",
       "player_name": "Hacker",
       "score": 999999
   }

Stap 2: Laravel checkt token
Stap 3: Token is FOUT! ❌
Stap 4: Reactie: "401 Unauthorized - invalid game token"
Stap 5: Score wordt NIET opgeslagen
```

### Voorbeeld: Game Bestaat Niet

```
🎮 Device stuurt verkeerde game slug

Stap 1: Device stuurt:

   POST /api/scores
   Header: X-API-TOKEN: OhmYWL0ROif65...

   Body:
   {
       "game_slug": "niet-bestaande-game",
       "score": 100
   }

Stap 2: Laravel zoekt game in database
Stap 3: Game niet gevonden! ❌
Stap 4: Reactie: "401 Unauthorized - unknown game"
```

---

## Voorbeelden

### Scenario 1: Admin Opent Dashboard

```
┌─────────────────────────────────────┐
│ 1. Admin gaat naar /admin/games    │
│                                     │
│ 2. Laravel checkt:                │
│    ✅ Sessie cookie aanwezig       │
│    ✅ Gebruiker is admin           │
│                                     │
│ 3. Resultaat:                     │
│    🟢 Toegang verleend            │
│    🟢 Admin pagina wordt getoond  │
└─────────────────────────────────────┘
```

### Scenario 2: Hacker Probeert Admin

```
┌─────────────────────────────────────┐
│ 1. Hacker gaat naar /admin/games   │
│                                     │
│ 2. Laravel checkt:                │
│    ❌ Geen sessie cookie           │
│                                     │
│ 3. Resultaat:                     │
│    🔴 Toegang geweigerd            │
│    🔴 Doorsturen naar login pagina │
└─────────────────────────────────────┘
```

### Scenario 3: Escape Room Device Stuurt Score

```
┌─────────────────────────────────────┐
│ 1. Device stuurt score via API      │
│                                     │
│ 2. Laravel checkt:                │
│    ✅ Token is aanwezig            │
│    ✅ Game slug is aanwezig        │
│    ✅ Game bestaat in database     │
│    ✅ Token klopt exact            │
│                                     │
│ 3. Resultaat:                     │
│    🟢 Score wordt opgeslagen        │
│    🟢 "Score toegevoegd!"           │
└─────────────────────────────────────┘
```

### Scenario 4: Hacker Probeert Score Toe Te Voegen

```
┌─────────────────────────────────────┐
│ 1. Hacker stuurt request naar API   │
│                                     │
│ 2. Laravel checkt:                │
│    ❌ Token ontbreekt of is fout   │
│                                     │
│ 3. Resultaat:                     │
│    🔴 "401 Unauthorized"           │
│    🔴 Score wordt niet opgeslagen  │
└─────────────────────────────────────┘
```

---

## Belangrijke Beveiligingsregels

### 1. Wachtwoorden zijn Gehasht

```
Wat je intypt:    admin123
Wat er staat:     $2y$12$kCjwO8P5rZvQ9mX...

→ Zelfs als de database wordt gestolen, zijn wachtwoorden veilig
```

### 2. Tokens zijn Uniek en Wachtwoord-achtig

```
Elk spel heeft een eigen token:
• Game 1: OhmYWL0ROif65bY0k3NT5NCt6mpRUy2Jfa43KFbf
• Game 2: xK9mP2nQ8rT4vW6yZ1aB3cD5eF7gH9iJ0kL1mN
• Game 3: aB1cD2eF3gH4iJ5kL6mN7oP8qR9sT0uV1wX2yZ3

→ 40 karakters lang, onmogelijk te raden
```

### 3. Sessies Verlopen

```
⏰ Standaard: 120 minuten (2 uur)

→ Na 2 uur inactiviteit moet je opnieuw inloggen
→ Dit voorkomt dat iemand anders je computer gebruikt
```

### 4. Alles Wordt Gelogd

```
• Wie er inlogt (IP adres)
• Welke scores er worden toegevoegd
• Vanaf welk IP adres

→ Bij problemen kunnen we terugkijken wat er gebeurd is
```

---

## Samenvatting

| Beveiliging     | Werkt op    | Hoe                      | Wat gebeurt bij fout? |
| --------------- | ----------- | ------------------------ | --------------------- |
| **Admin Login** | /admin/\*   | Check sessie + admin rol | Doorsturen naar login |
| **API Token**   | /api/scores | Check token in header    | "401 Unauthorized"    |

**Kernprincipe:**

```
Geen geldige login/token? → GEEN TOEGANG! 🔒
```

---

_Documentatie versie: 1.1_  
_Laatst bijgewerkt: 2 april 2026_
