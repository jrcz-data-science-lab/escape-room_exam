# Oefenquiz — Leaderboard (Nederlands)

Deze multiple-choice quiz helpt je oefenen. Per vraag is één antwoord correct.

1. Welke HTTP-methode gebruik je om een nieuwe score toe te voegen?
   A) GET
   B) POST
   C) PUT
   D) DELETE

Correct: B

2. Welke header moet de client meesturen bij het toevoegen van een score?
   A) Authorization
   B) X-Token
   C) X-CSRF-Token
   D) Content-Type

Correct: B

3. Waar wordt het admin-wachtwoord opgeslagen?
   A) In de database
   B) In `config/app.php`
   C) In `.env` (ADMIN_PASSWORD)
   D) In localStorage

Correct: C

4. Welke Laravel-functie zorgt voor server-side validatie in de controller?
   A) validate()
   B) check()
   C) ensure()
   D) assert()

Correct: A

5. Welke Eloquent-methode wordt gebruikt om direct een nieuw record aan te maken?
   A) save()
   B) insert()
   C) create()
   D) push()

Correct: C

6. Hoe kun je voorkomen dat een gebruiker te vaak scores indient?
   A) Gebruik CSRF tokens
   B) Gebruik rate-limiting (throttle)
   C) Verwijder inputvelden
   D) Zet de server uit

Correct: B

7. Welke actie is nodig na het wijzigen van Blade-views om caching issues te voorkomen?
   A) composer install
   B) npm run dev
   C) php artisan view:clear
   D) php artisan migrate

Correct: C

8. Wat retourneert de API bij succesvolle creatie van een score?
   A) 200 OK
   B) 201 Created
   C) 404 Not Found
   D) 500 Internal Server Error

Correct: B

9. Welke methode haalt de top 10 scores op in de controller?
   A) all()
   B) take(10)
   C) limit(10)->get()
   D) orderByDesc('score')->limit(10)->get()

Correct: D

10. Waarom is server-side validatie belangrijk?
    A) Het is niet belangrijk
    B) Omdat het de UX verbetert
    C) Omdat client-side validatie kan worden omzeild en server validatie beschermt tegen ongeldige of kwaadaardige input
    D) Omdat het sneller is dan client-side validatie

Correct: C
