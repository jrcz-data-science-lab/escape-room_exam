<?php

use Illuminate\Support\Facades\Route;
// Route facade import voor het definiëren van routes

use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Web\LeaderboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ScoreAdminController;
use App\Http\Middleware\AdminAuth;

// Publieke leaderboard-pagina
Route::get('/', [\App\Http\Controllers\Web\LeaderboardController::class, 'index'])
    ->name('leaderboard.index');

// Admin authenticatie routes (login form en login POST)
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');

// Admin logout (POST actie)
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin routes gegroepeerd achter de AdminAuth middleware
Route::middleware([AdminAuth::class])->prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard / lijst van scores
    Route::get('/', [ScoreAdminController::class, 'index'])->name('index');
    // Edit formulier voor een specifieke score
    Route::get('/scores/{score}/edit', [ScoreAdminController::class, 'edit'])->name('scores.edit');
    // Update actie (PUT)
    Route::put('/scores/{score}', [ScoreAdminController::class, 'update'])->name('scores.update');
    // Verwijderen van een score (DELETE)
    Route::delete('/scores/{score}', [ScoreAdminController::class, 'destroy'])->name('scores.destroy');
});
