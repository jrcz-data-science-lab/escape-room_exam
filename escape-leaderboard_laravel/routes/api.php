<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ScoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| API-routes worden geladen door de RouteServiceProvider en krijgen de
| "api" middleware groep toegewezen (o.a. geen CSRF cookie vereist).
|
*/

// Voorbeeld route (auth:sanctum) — laat gebruikersinfo teruggeven als geïdentificeerd
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Endpoint om nieuwe scores te plaatsen. Deze route valt onder de API groep
// en is beveiligd met de 'leaderboard.token' middleware (controleert X-Token header)
// Beperk het aantal verzoeken per minuut om misbruik (spam/DoS) te voorkomen:
// maximaal 30 verzoeken per minuut per IP-adres. Overschrijding levert HTTP 429 op.
Route::post('/scores', [ScoreController::class, 'store'])
    ->middleware(['leaderboard.token', 'throttle:30,1']);
