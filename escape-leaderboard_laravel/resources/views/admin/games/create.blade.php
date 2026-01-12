<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Escape Room toevoegen</title>
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
                <a href="{{ route('admin.games.index') }}">Games beheer</a>
                <a href="{{ route('admin.index') }}">Scorebeheer</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="home-container">
        <div class="admin-wrapper" style="max-width: 700px;">
            <h1 class="admin-title">Nieuwe Escape Room toevoegen</h1>

            @if(session('new_game_token'))
            <div class="token-display">
                <p class="token-display-title">Game aangemaakt — kopieer het token:</p>
                <div class="token-input-group">
                    <input readonly value="{{ session('new_game_token') }}" id="newToken">
                    <button onclick="navigator.clipboard.writeText(document.getElementById('newToken').value)" class="btn">Kopieer</button>
                </div>
                <p style="color: var(--text-light); margin-bottom: 10px;">Slug: <strong>{{ session('new_game_slug') }}</strong></p>
                <p class="token-warning">⚠️ Bewaar dit token veilig — het wordt niet nogmaals getoond.</p>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.games.store') }}" style="background: rgba(255, 255, 255, 0.95); padding: 35px; border-radius: 20px; box-shadow: var(--shadow-md);">
                @csrf
                <div class="form-group">
                    <label>Naam</label>
                    <input name="name" required value="{{ old('name') }}">
                    @error('name') <p style="color: var(--danger-red); font-size: 14px; margin-top: 8px;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label>Slug (optioneel)</label>
                    <input name="slug" value="{{ old('slug') }}">
                    <p class="info-text">Wordt gebruikt in de URL (bv. escape-1). Leeg laten genereert automatisch een slug.</p>
                    @error('slug') <p style="color: var(--danger-red); font-size: 14px; margin-top: 8px;">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label>Omschrijving (optioneel)</label>
                    <textarea name="description" style="min-height: 120px; resize: vertical;">{{ old('description') }}</textarea>
                    @error('description') <p style="color: var(--danger-red); font-size: 14px; margin-top: 8px;">{{ $message }}</p> @enderror
                </div>
                <div class="button-group">
                    <a href="{{ route('admin.games.index') }}" class="btn btn-secondary">Annuleren</a>
                    <button type="submit" class="btn btn-success">Aanmaken</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>