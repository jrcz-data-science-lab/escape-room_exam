# Design & Security Overwegingen

## Doel

Leg ontwerpkeuzes vast en motiveer beslissingen met aandacht voor privacy en veiligheid.

## Onderwerpen om te documenteren

-   API authenticatie: API-token, rate-limiting, CORS
-   Mass-assignment: gebruik van `$fillable` om onbedoelde updates te voorkomen
-   Data-retentie: hoe lang worden scores bewaard?
-   Privacy: welke persoonsgegevens worden opgeslagen (player_name, IP)? Is anonimiseren nodig?
-   Input validatie en sanitatie: guard tegen XSS/SQL-injectie

## Voorbeeld conclusie

-   `player_name` en `submitted_from_ip` worden alleen bewaard als strikt noodzakelijk; IP kan gehasht of geanonimiseerd worden voor privacy.

## Acties

-   [ ] Documenteer gekozen auth-methode en waar middleware toegepast is
-   [ ] Besluit over IP-retentie en anonimisatie opnemen
