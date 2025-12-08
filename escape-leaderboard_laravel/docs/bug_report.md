# Bug Report — Escape Leaderboard

Issues die ik tegenkwam en hoe ik ze aangepakt heb.

---

## 1. Negatieve scores in admin (OPGELOST)

**Probleem:**  
Ik merkte op dat je via de admin-interface negatieve scores kon invoeren (bijv. `-50`), maar de API accepteerde dit niet. De validatie was niet consistent tussen beide.

**Waar:**  
`ScoreAdminController` had geen `min:0` check, `ScoreController` (API) wel.

**Fix:**  
Toegevoegd aan admin-update validatie: `'score' => 'required|integer|min:0'`

Nu werkt het hetzelfde aan beide kanten.

---

## 2. Logout verwijdert sessie niet volledig (OPEN)

**Probleem:**  
Na logout verwijder je alleen de `is_admin` flag, maar de sessie-ID zelf blijft geldig. Theoretisch kun je met een oude sessie-cookie nog steeds teruggaan.

**Waar:**  
`AuthController` — `logout()` gebruikte alleen `session()->forget('is_admin')`

**Fix nodig:**

```php
public function logout(Request $request)
{
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('leaderboard.index');
}
```

Dit invalidates de hele sessie en genereert een nieuw CSRF-token. Belangrijk voor security.

---

## 3. API-token zichtbaar in veld (ACCEPTABEL)

**Observatie:**  
Het API-token staat in een inputveld op de leaderboard-pagina — dus zichtbaar op het scherm.

**Waarom niet erg:**

-   Het veld is read-only
-   Het token wordt niet in `localStorage` opgeslagen (goed!)
-   Het is ingesteld voor demo/testing
-   Voor production zou je dit anders doen (dynamisch token, etc.)

---

## 4. Geen rate-limiting op API (OPEN)

**Probleem:**  
Er is geen beperking op hoe veel requests je naar `POST /api/scores` kunt sturen. Een aanvaller kan spammen.

**Fix nodig:**  
In `routes/api.php`:

```php
Route::post('/scores', [ScoreController::class, 'store'])
    ->middleware(['leaderboard.token', 'throttle:30,1']);
```

Dit beperkt tot 30 requests per minuut per IP. Laravel geeft dan een 429-error.

---

## 5. Admin-wachtwoord in plaintext (ACCEPTABEL)

**Observatie:**  
`ADMIN_PASSWORD=secret123` staat in `.env` zonder hashing.

**Waarom oké voor nu:**

-   `.env` zit in `.gitignore` (niet in repo)
-   Dit is een demo-project
-   Voor production zou je hashing gebruiken (User model + bcrypt)

---

## Wat ik gecontroleerd heb

-   ✅ CSRF-token aanwezig in forms (`@csrf`)
-   ✅ API-token wordt als `X-Token` header verstuurd
-   ✅ Token staat niet in browser-storage
-   ✅ Admin middleware controleert sessie-flag
-   ✅ Validatie is nu consistent (negatieve scores niet meer in admin)

---

## Test-ideeën voor later

```php
// Negatieve scores moeten afgewezen worden
$response = $this->post('/api/scores', ['score' => -10]);
$response->assertStatus(422);

// Rate-limit na 30 requests
for ($i = 0; $i < 31; $i++) {
    $this->post('/api/scores', [...], ['X-Token' => $token]);
}
// 31e request zou 429 moeten geven
```

---

Dat is het. De belangrijkste fix is de logout-hardening en rate-limiting.
