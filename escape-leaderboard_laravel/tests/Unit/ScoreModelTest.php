<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Score;

class ScoreModelTest extends TestCase
{
    /**
     * Test of het Score-model de verwachte fillable-velden heeft.
     * Dit is een eenvoudige unit-test die laat zien dat mass-assignment
     * veilig gebruikt kan worden voor de verwachte velden.
     */
    public function test_fillable_contains_expected_fields()
    {
        $model = new Score();

        $fillable = $model->getFillable();

        $this->assertContains('player_name', $fillable, 'player_name moet in $fillable aanwezig zijn');
        $this->assertContains('score', $fillable, 'score moet in $fillable aanwezig zijn');
        $this->assertContains('time_seconds', $fillable, 'time_seconds moet in $fillable aanwezig zijn');
    }
}
