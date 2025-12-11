<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Game;
use App\Models\Score;

class ScoreApiGameTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_score_with_valid_game_token_returns_201()
    {
        $game = Game::create(["name" => "Test Game", "slug" => "test-game", "api_token" => 'game-token-123']);

        $response = $this->postJson('/api/scores', [
            'game_slug' => 'test-game',
            'player_name' => 'Alice',
            'score' => 100,
        ], ['X-Token' => 'game-token-123']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('scores', ['player_name' => 'Alice', 'score' => 100, 'game_id' => $game->id]);
    }

    public function test_post_score_with_invalid_game_token_returns_401()
    {
        $game = Game::create(["name" => "Test Game 2", "slug" => "test-game-2", "api_token" => 'token-abc']);

        $response = $this->postJson('/api/scores', [
            'game_slug' => 'test-game-2',
            'player_name' => 'Bob',
            'score' => 50,
        ], ['X-Token' => 'wrong-token']);

        $response->assertStatus(401);
    }

    public function test_global_token_fallback_works()
    {
        config(['services.leaderboard.api_token' => 'global-token-xyz']);
        $game = Game::create(["name" => "Test Game 3", "slug" => "test-game-3", "api_token" => 'token-zzz']);

        $response = $this->postJson('/api/scores', [
            'game_slug' => 'test-game-3',
            'player_name' => 'Carol',
            'score' => 75,
        ], ['X-Token' => 'global-token-xyz']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('scores', ['player_name' => 'Carol', 'score' => 75, 'game_id' => $game->id]);
    }
}
