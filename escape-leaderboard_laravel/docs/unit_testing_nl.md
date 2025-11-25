# Unit Testing Documentatie

## ScoreModelTest

Deze documentatie beschrijft de unit tests die zijn geïmplementeerd voor het Score model.

### Test voor Fillable Velden

**Bestandslocatie:** `tests/Unit/ScoreModelTest.php`

#### Structuur van de Test

```php
class ScoreModelTest extends TestCase
```

-   Dit is een test klasse die erft van PHPUnit's `TestCase`
-   De naam van de klasse eindigt op "Test" volgens PHPUnit conventie
-   De test bevindt zich in de `Tests\Unit` namespace

#### De Test Methode

```php
public function test_fillable_contains_expected_fields()
```

-   De methode begint met `test_` wat PHPUnit vertelt dat dit een test methode is
-   De naam is descriptief en vertelt precies wat we testen
-   Voorzien van PHP DocBlock commentaar voor duidelijke documentatie

#### Test Stappen (Arrange-Act-Assert Pattern)

1. **Arrange (Voorbereiden):**
    ```php
    $model = new Score();
    ```
    - We maken een nieuwe instantie van het Score model
2. **Act (Uitvoeren):**
    ```php
    $fillable = $model->getFillable();
    ```
    - We halen de `$fillable` array op van het model
3. **Assert (Verifiëren):**
    ```php
    $this->assertContains('player_name', $fillable, 'player_name moet in $fillable aanwezig zijn');
    $this->assertContains('score', $fillable, 'score moet in $fillable aanwezig zijn');
    $this->assertContains('time_seconds', $fillable, 'time_seconds moet in $fillable aanwezig zijn');
    ```
    - Controleert of specifieke velden aanwezig zijn in de `$fillable` array
    - Elke assert heeft een duidelijke foutmelding

#### Waarom deze Test Belangrijk is

1. **Veiligheid:**

    - Test of mass-assignment correct is geconfigureerd
    - Voorkomt onbedoelde wijzigingen aan beveiligingsinstellingen

2. **Documentatie:**

    - Dient als levende documentatie van welke velden mass-assignable zijn
    - Maakt het model gebruik duidelijk voor andere ontwikkelaars

3. **Regressiepreventie:**

    - Als iemand per ongeluk de `$fillable` array wijzigt, zal de test falen
    - Voorkomt onbedoelde wijzigingen aan kritieke modelconfiguratie

4. **Onderhoudbaarheid:**
    - Makkelijk te begrijpen wat er getest wordt
    - Duidelijke foutmeldingen maken debuggen eenvoudig

#### Best Practices Gedemonstreerd

1. **Naamgeving:**

    - Duidelijke, beschrijvende namen voor tests
    - Volgt PHPUnit conventies

2. **Single Responsibility:**

    - Elke test controleert één specifiek aspect
    - Focus op één functionaliteit per test

3. **Documentatie:**

    - PHP DocBlocks voor testmethoden
    - Duidelijke, informatieve assertions

4. **Code Kwaliteit:**
    - Volgt het Arrange-Act-Assert pattern
    - Betekenisvolle assertions met duidelijke foutmeldingen

#### Test Uitvoering

De test kan worden uitgevoerd met het volgende commando:

```bash
php artisan test --filter=ScoreModelTest
```

Verwachte output:

```
PASS  Tests\Unit\ScoreModelTest
✓ fillable contains expected fields
```

### Examendoeleinden

Deze unit test demonstreert de volgende vaardigheden:

1. **Testing Fundamentals:**

    - Begrip van unit testing principes
    - Correct gebruik van PHPUnit
    - Toepassing van het Arrange-Act-Assert pattern

2. **Best Practices:**

    - Duidelijke teststructuur
    - Goede documentatie
    - Betekenisvolle assertions
    - Naamgevingsconventies

3. **Laravel Specifiek:**

    - Begrip van Laravel models
    - Mass-assignment beveiliging
    - Laravel testing framework

4. **Code Kwaliteit:**
    - Single Responsibility Principle
    - Leesbaarheid
    - Onderhoudbaarheid
    - Herbruikbaarheid
