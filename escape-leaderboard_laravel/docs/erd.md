# ERD — Entity Relationship Diagram

## Doel

Documenteer de belangrijkste entiteiten en relaties (scores, users, games indien van toepassing).

## Entiteiten

- games
    - id (PK) - Auto Increment
    - name (VARCHAR, UNIQUE)
    - slug (VARCHAR, UNIQUE, NULLABLE)
    - description (TEXT, NULLABLE)
    - api_token (VARCHAR, UNIQUE)
    - created_at (TIMESTAMP)
    - updated_at (TIMESTAMP)
- scores
    - id (PK) - Auto Increment
    - player_name (VARCHAR)
    - score (INTEGER)
    - game_id (FK -> games.id)
    - submitted_from_ip (VARCHAR, NULL)
    - created_at (TIMESTAMP)
    - updated_at (TIMESTAMP)
- users
    - id, name, email, password, is_admin, created_at, updated_at

## Relaties

- `games` (1) --- (N) `scores`
- `users` (1) --- (N) `scores` (optioneel: if scores are linked to registered users via user_id)

## Voorbeeld (tekstueel)

```
users(id) 1---N scores(user_id)
```

## Aanbeveling

- Voeg een afbeelding/PNG van het ERD toe in `docs/` zodra beschikbaar
- Documenteer velden en toegepaste casts/indices

## Acties

- [ ] Maak diagram in draw.io of drawio-export (.png)
- [ ] Voeg PNG toe als `docs/erd.png` of `docs/erd.drawio`
