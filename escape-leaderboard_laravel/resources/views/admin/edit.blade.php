<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Score</title>
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
                <a href="{{ route('admin.index') }}">Admin</a>
                <a href="{{ route('leaderboard.index') }}">Home</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="home-container">
        <div class="admin-wrapper" style="max-width: 600px;">
            <h1 class="admin-title">Aanpassen score #{{ $score->id }}</h1>

            <form method="POST" action="{{ route('admin.scores.update', $score->id) }}" style="background: rgba(255, 255, 255, 0.95); padding: 35px; border-radius: 20px; box-shadow: var(--shadow-md);">@csrf @method('PUT')
                <div class="form-group">
                    <label>Speler</label>
                    <input name="player_name" value="{{ old('player_name', $score->player_name) }}" required />
                </div>
                <div class="form-group">
                    <label>Score</label>
                    <input name="score" type="number" min="0" value="{{ old('score', $score->score) }}" required />
                </div>
                <div class="button-group">
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary">Annuleer</a>
                    <button type="submit" class="btn">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>