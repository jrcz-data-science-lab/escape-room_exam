<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Game;
use App\Models\Score;

class LeaderboardSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_leaderboard_search_and_rank()
    {
        $game = Game::create(["name" => "Search Game", "slug" => "search-game", "api_token" => 'tkn']);

        // Create multiple players with varying best scores
        Score::create(['player_name' => 'Player A', 'score' => 300, 'game_id' => $game->id]);
        Score::create(['player_name' => 'Player B', 'score' => 500, 'game_id' => $game->id]);
        Score::create(['player_name' => 'Player C', 'score' => 200, 'game_id' => $game->id]);
        // Add additional scores for Player A (lower) to ensure aggregation uses max
        Score::create(['player_name' => 'Player A', 'score' => 250, 'game_id' => $game->id]);

        // Search exact Player A
        $response = $this->get('/games/search-game?q=Player A');
        $response->assertStatus(200);
        $response->assertSee('Player A');
        // Player B should be ranked #1, Player A #2
        $response->assertSee('#2');
    }
}
