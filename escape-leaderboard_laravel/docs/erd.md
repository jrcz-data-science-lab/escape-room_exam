# ERD — Entity Relationship Diagram

## Doel

Documenteer de belangrijkste entiteiten en relaties (scores, users, games indien van toepassing).

## Entiteiten

-   users
    -   id, name, email, password, is_admin, created_at, updated_at
-   scores
    -   id, player_name, score, time_seconds, game_id, submitted_from_ip, created_at, updated_at

## Relaties

-   `users` (1) --- (N) `scores` (optioneel: if scores are linked to registered users via user_id)

## Voorbeeld (tekstueel)

```
users(id) 1---N scores(user_id)
```

## Aanbeveling

-   Voeg een afbeelding/PNG van het ERD toe in `docs/` zodra beschikbaar
-   Documenteer velden en toegepaste casts/indices

## Acties

-   [ ] Maak diagram in draw.io of drawio-export (.png)
-   [ ] Voeg PNG toe als `docs/erd.png` of `docs/erd.drawio`
