<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Games beheer</title>
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('leaderboard.index') }}" class="navbar-title">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="navbar-logo" />
                <span class="navbar-title-text">Leaderboard</span>
            </a>
            <div class="navbar-links">
                <a href="{{ route('admin.index') }}">Scorebeheer</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="home-container">
        <div class="admin-wrapper">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1 class="admin-title" style="margin: 0;">Games beheer</h1>
                <a href="{{ route('admin.games.create') }}" class="btn">Nieuwe game</a>
            </div>

            @if(session('success'))
            <div class="form-alert success">
                {{ session('success') }}
            </div>
            @endif

            <div class="games-grid">
                @foreach($games as $game)
                <div class="game-card">
                    <h3 class="game-title">{{ $game->name }}</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 15px;">Slug: <strong>{{ $game->slug }}</strong></p>
                    @if($game->description)
                    <p class="game-description">{{ $game->description }}</p>
                    @endif
                    <div style="margin-bottom: 20px;">
                        <a href="{{ route('leaderboard.game', $game->slug) }}" class="game-link-button" style="margin-bottom: 15px;">Bekijk public</a>
                    </div>
                    <form method="POST" action="{{ route('admin.games.addScore', $game->id) }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-start;">
                        @csrf
                        <input name="player_name" placeholder="Speler naam" style="flex: 1; min-width: 150px;" required>
                        <input name="score" placeholder="Score" type="number" min="0" style="width: 100px;" required>
                        <button type="submit" class="btn" style="flex: 1; min-width: 120px;">Snelle toevoeging</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>