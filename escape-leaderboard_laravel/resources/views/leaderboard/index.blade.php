<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
</head>

<body>
    {{-- Navigatiebalk bovenaan met link naar de publieke leaderboard en admin link --}}
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('leaderboard.index') }}" class="navbar-title">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="navbar-logo" />
                <span class="navbar-title-text">Leaderboard</span>
            </a>
            <div class="navbar-links">
                @if(session('is_admin'))
                <a href="{{ route('admin.index') }}">Admin</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button type="submit">Logout</button>
                </form>
                @else
                <a href="{{ route('admin.login') }}">Admin login</a>
                @endif
            </div>
        </div>
    </nav>
    <div class="home-container">
        @if(isset($game) && isset($scores))
        {{-- Per-game leaderboard --}}
        <div class="leaderboard-board">
            <div class="leaderboard-header">
                <h1 class="leaderboard-title">Leaderboard: {{ $game->name }}</h1>
                <p class="leaderboard-subtitle">Zoek je naam om te zien waar je staat — alleen je beste score wordt zichtbaar.</p>
            </div>

            <div class="search-container">
                <form method="GET" action="{{ route('leaderboard.game', $game->slug) }}">
                    <input id="player-search" type="text" name="q" value="{{ isset($q) ? $q : '' }}" placeholder="Zoek op spelernaam" class="search-input" autocomplete="off">
                    <div class="button-group">
                        <button type="submit" class="btn">Zoek</button>
                        <a href="{{ route('leaderboard.game', $game->slug) }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
                @if(isset($searchedRank) && $searchedRank)
                <div class="search-result">Jouw positie: <strong>#{{ $searchedRank }}</strong></div>
                @elseif(isset($q) && $q)
                <div class="search-result" style="opacity: 0.8;">Geen exacte positie gevonden voor "{{ $q }}" (toon matches hieronder)</div>
                @endif

                {{-- Suggestielijst voor realtime zoeken --}}
                <div id="player-suggestions" class="suggestions-box hidden"></div>
            </div>

            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Speler</th>
                        <th style="text-align: right;">Beste score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scores as $index => $row)
                    <tr>
                        <td class="rank-number">{{ ($scores->currentPage() - 1) * $scores->perPage() + $index + 1 }}</td>
                        <td class="player-name">{{ $row->player_name }}</td>
                        <td class="score-value" style="text-align: right;">{{ number_format($row->best_score) }}</td>
                    </tr>
                    @endforeach
                    @if($scores->isEmpty())
                    <tr>
                        <td colspan="3" class="empty-state">Nog geen scores beschikbaar voor deze escape room</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <div class="pagination">{{ $scores->links() }}</div>
        </div>
        @else
        {{-- Hoofdpagina: Top 10 scores --}}
        <div class="leaderboard-board">
            <div class="leaderboard-header">
                <h1 class="leaderboard-title">Top 10 Scores</h1>
                <p class="leaderboard-subtitle">De beste scores van alle escape rooms</p>
            </div>

            <table class="leaderboard-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Speler</th>
                        <th>Score</th>
                        <th>Escape room</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topScores as $index => $row)
                    <tr class="@if($index === 0) rank-1 @elseif($index === 1) rank-2 @elseif($index === 2) rank-3 @endif">
                        <td class="rank-number">{{ $index+1 }}</td>
                        <td class="player-name">{{ $row->player_name }}</td>
                        <td class="score-value">{{ number_format($row->score) }}</td>
                        <td><a href="{{ route('leaderboard.game', $row->game->slug) }}" class="game-link">{{ $row->game ? $row->game->name : 'Onbekend' }}</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="empty-state">Er zijn nog geen scores toegevoegd</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        {{-- Links naar alle game leaderboards --}}
        @if(isset($games) && $games->isNotEmpty())
        <div class="games-section">
            <div class="games-header">
                <h2 class="games-title">Bekijk leaderboards per escape room</h2>
            </div>
            <div class="games-grid">
                @foreach($games as $g)
                <div class="game-card">
                    <h3 class="game-title">{{ $g->name }}</h3>
                    @if($g->description)
                    <p class="game-description">{{ $g->description }}</p>
                    @endif
                    <a href="{{ route('leaderboard.game', $g->slug) }}" class="game-link-button">Bekijk leaderboard</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Formulier om een nieuwe score in te dienen --}}
        <div class="leaderboard-board" style="margin-top: 50px;">
            <div class="leaderboard-header">
                <h2 class="leaderboard-title">Score indienen</h2>
                <p class="leaderboard-subtitle">Voer je naam en score in. Je moet een geldige token opgeven om te kunnen indienen.</p>
            </div>

            <div class="container-box">
                {{-- Plaats waar foutmeldingen of succesberichten verschijnen --}}
                <div id="form-alert" class="hidden form-alert"></div>

                {{-- Selecteer game (per escape room) --}}
                @if(isset($games) && $games->isNotEmpty())
                <div class="form-group">
                    <label>Selecteer escape room</label>
                    <select id="game_select" name="game_slug">
                        @foreach($games as $g)
                        <option value="{{ $g->slug }}" @if(isset($game) && $game->id == $g->id) selected @endif>{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="form-alert error">Geen escape rooms beschikbaar. Neem contact op met de admin om games toe te voegen.</div>
                @endif

                {{-- Token invoerveld (masked) en knop om deze tijdelijk zichtbaar te maken --}}
                <div class="form-group">
                    <label>Token</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input id="token" type="password" placeholder="X-Token" style="flex: 1;" />
                        <button id="toggleShowToken" type="button" class="btn btn-secondary" style="padding: 14px 20px;">Toon</button>
                    </div>
                    <button id="clearToken" type="button" style="margin-top: 10px; padding: 8px 16px; background: none; color: var(--text-dark); border: none; cursor: pointer; text-decoration: underline; font-size: 14px;">Wis token</button>
                </div>

                {{-- Speler naam veld --}}
                <div class="form-group">
                    <label>Speler</label>
                    <input id="player_name" type="text" placeholder="Naam" />
                </div>

                {{-- Score invoer veld --}}
                <div class="form-group">
                    <label>Score</label>
                    <input id="score" type="number" min="0" placeholder="Score" />
                </div>

                {{-- Verzenden knop --}}
                <div class="button-group">
                    <button id="submitBtn" type="button" class="btn" @if(!isset($games) || $games->isEmpty()) disabled @endif>Verstuur score</button>
                </div>
            </div>

            <div class="text-center mt-4" style="color: var(--text-light); opacity: 0.8;">
                <p>Scores worden automatisch bijgewerkt</p>
            </div>
        </div>
    </div>

    {{--
        Uitleg (NL): Dit script behandelt token-beheer en het versturen van scores.
        Belangrijk: tokens worden bewust NIET in localStorage/sessionStorage opgeslagen.
        Ze blijven alleen in het invoerveld aanwezig terwijl de pagina open is.
    --}}
    <script>
        // Debounce helper om API-calls te beperken
        function debounce(fn, delay = 200) {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), delay);
            };
        }

        // Auto-refresh elke 30 seconden
        setInterval(() => {
            // only reload if not currently filling the form
            const active = document.activeElement;
            if (!['INPUT', 'TEXTAREA'].includes(active.tagName)) {
                window.location.reload();
            }
        }, 30000);

        // Token handling: do NOT persist tokens client-side. The token remains only in the input while the page is open.
        const tokenInput = document.getElementById('token');
        const toggleShow = document.getElementById('toggleShowToken');

        // Start empty by default
        tokenInput.value = '';

        // Toggle show/hide token
        toggleShow.addEventListener('click', (e) => {
            e.preventDefault();
            if (tokenInput.type === 'password') {
                tokenInput.type = 'text';
                toggleShow.textContent = 'Verberg';
            } else {
                tokenInput.type = 'password';
                toggleShow.textContent = 'Toon';
            }
        });

        // Clear token from input only
        const clearTokenBtn = document.getElementById('clearToken');
        if (clearTokenBtn) {
            clearTokenBtn.addEventListener('click', (e) => {
                e.preventDefault();
                tokenInput.value = '';
                showAlert('Token gewist', false);
            });
        }

        function showAlert(message, isSuccess = false) {
            const el = document.getElementById('form-alert');
            el.className = 'form-alert ' + (isSuccess ? 'success' : 'error');
            el.textContent = message;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 4000);
        }

        document.getElementById('submitBtn').addEventListener('click', async (e) => {
            e.preventDefault();
            const token = document.getElementById('token').value.trim();
            const player_name = document.getElementById('player_name').value.trim();
            const score = document.getElementById('score').value;

            if (!token) {
                showAlert('Token is verplicht');
                return;
            }
            if (!player_name) {
                showAlert('Speler naam is verplicht');
                return;
            }
            if (score === '' || isNaN(score)) {
                showAlert('Score is verplicht en moet een nummer zijn');
                return;
            }

            // Do not persist token client-side. It stays only in the input for this session.

            // determine selected game slug (if select exists)
            let gameSlug = null;
            const gameSelect = document.getElementById('game_select');
            if (gameSelect) {
                gameSlug = gameSelect.value;
            } else {
                // If the page was rendered for a specific game, Blade provides $game
            }

            if (!gameSlug) {
                showAlert('Selecteer een escape room');
                return;
            }

            try {
                const res = await fetch('/api/scores', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Token': token
                    },
                    body: JSON.stringify({
                        player_name,
                        score: parseInt(score, 10),
                        game_slug: gameSlug
                    })
                });

                if (res.status === 201) {
                    showAlert('Score succesvol toegevoegd', true);
                    setTimeout(() => window.location.reload(), 800);
                    return;
                }

                const data = await res.json();
                if (data && data.message) {
                    showAlert(data.message || 'Fout bij toevoegen');
                } else {
                    showAlert('Fout bij toevoegen (status ' + res.status + ')');
                }
            } catch (err) {
                console.error('Error submitting score:', err);
                showAlert('Fout bij toevoegen (netwerkfout)');
            }
        });

        // Realtime speler-zoek (typeahead) voor per-game pagina
        const searchInput = document.getElementById('player-search');
        const suggestionsBox = document.getElementById('player-suggestions');

        if (searchInput && suggestionsBox) {
            const fetchSuggestions = debounce(async (value) => {
                const query = value.trim();
                if (!query) {
                    suggestionsBox.classList.add('hidden');
                    suggestionsBox.innerHTML = '';
                    return;
                }

                try {
                    const params = new URLSearchParams();
                    params.set('q', query);
                    const gameSlug = "{{ isset($game) ? $game->slug : '' }}";
                    if (gameSlug) {
                        params.set('game_slug', gameSlug);
                    }

                    const res = await fetch(`/api/players?${params.toString()}`);
                    if (!res.ok) throw new Error('Suggesties ophalen mislukt');
                    const names = await res.json();

                    if (!names.length) {
                        suggestionsBox.classList.add('hidden');
                        suggestionsBox.innerHTML = '';
                        return;
                    }

                    suggestionsBox.innerHTML = names.map(name => `
                        <button type="button" class="suggestion-item">${name}</button>
                    `).join('');
                    suggestionsBox.classList.remove('hidden');

                    // Klik op suggestie: vul veld en verstuur formulier
                    suggestionsBox.querySelectorAll('.suggestion-item').forEach(btn => {
                        btn.addEventListener('click', () => {
                            searchInput.value = btn.textContent;
                            suggestionsBox.classList.add('hidden');
                            suggestionsBox.innerHTML = '';
                        });
                    });
                } catch (err) {
                    console.error(err);
                    suggestionsBox.classList.add('hidden');
                    suggestionsBox.innerHTML = '';
                }
            }, 200);

            searchInput.addEventListener('input', (e) => {
                fetchSuggestions(e.target.value);
            });

            // Verberg suggesties bij verlies van focus (kleine delay om klikken toe te laten)
            searchInput.addEventListener('blur', () => {
                setTimeout(() => {
                    suggestionsBox.classList.add('hidden');
                }, 150);
            });
        }
    </script>
</body>

</html>