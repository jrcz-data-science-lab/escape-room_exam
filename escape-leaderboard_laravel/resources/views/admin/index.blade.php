<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Scores</title>
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
                <a href="{{ route('leaderboard.index') }}">Home</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="home-container">
        <div class="admin-wrapper">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 15px;">
                <h1 class="admin-title" style="margin: 0;">Beheer Scores</h1>
                <a href="{{ route('admin.games.index') }}" class="btn">Games beheren</a>
            </div>

            @if(session('success'))
            <div class="form-alert success">
                {{ session('success') }}
            </div>
            @endif

            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Speler</th>
                        <th>Score</th>
                        <th>Aangemaakt</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scores as $index => $s)
                    <tr class="@if($index === 0) rank-1 @endif">
                        <td class="rank-number">{{ $s->id }}</td>
                        <td class="player-name">{{ $s->player_name }}</td>
                        <td class="score-value">{{ number_format($s->score) }}</td>
                        <td>{{ $s->created_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.scores.edit', $s->id) }}" class="btn" style="padding: 8px 16px; font-size: 0.85rem;">Aanpassen</a>
                                <form method="POST" action="{{ route('admin.scores.destroy', $s->id) }}" style="display:inline">@csrf @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Weet je het zeker?')">Verwijder</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $scores->links() }}
            </div>
        </div>
    </div>
</body>

</html>