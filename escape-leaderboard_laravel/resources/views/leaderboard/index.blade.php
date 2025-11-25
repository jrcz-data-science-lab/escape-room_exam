<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    {{-- Navigatiebalk bovenaan met link naar de publieke leaderboard en admin link -- zwart voor consistentie --}}
    <nav class="bg-black text-white shadow-sm" style="background:#000;color:#fff;">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('leaderboard.index') }}" class="text-lg font-semibold text-white">Leaderboard</a>
            <div>
                @if(session('is_admin'))
                <a href="{{ route('admin.index') }}" class="mr-4 text-sm text-white">Admin</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button class="text-sm text-white">Logout</button>
                </form>
                @else
                <a href="{{ route('admin.login') }}" class="text-sm text-white">Admin login</a>
                @endif
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-8">
        {{-- Titel van de pagina --}}
        <h1 class="text-4xl font-bold text-center mb-8">Leaderboard</h1>

        {{-- Tabel met scores (hier worden de scores weergegeven) --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">#</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Speler</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold">Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($scores as $index => $score)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $score->player_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-right font-mono">
                            {{ number_format($score->score) }}
                        </td>
                    </tr>
                    @endforeach

                    @if($scores->isEmpty())
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm text-gray-500 text-center">
                            Nog geen scores beschikbaar
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Formulier om een nieuwe score in te dienen --}}
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4 text-center">Score indienen</h2>

            <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
                {{-- Uitlegtekst voor gebruikers over het formulier --}}
                <p class="text-sm text-gray-600 mb-4">Voer je naam en score in. Je moet een geldige token opgeven om te kunnen indienen.</p>

                {{-- Plaats waar foutmeldingen of succesberichten verschijnen --}}
                <div id="form-alert" class="hidden mb-4 text-sm"></div>

                {{-- Token invoerveld (masked) en knop om deze tijdelijk zichtbaar te maken --}}
                <label class="block mb-2">Token</label>
                <div class="flex items-center gap-2 mb-3">
                    <input id="token" type="password" class="flex-1 border px-3 py-2 rounded" placeholder="X-Token" />
                    <button id="toggleShowToken" class="text-sm text-gray-600 px-2 py-1">Toon</button>
                </div>
                <div class="flex items-center gap-3 mb-3 text-sm">
                    {{-- Knop om het token uit het invoerveld te wissen --}}
                    <button id="clearToken" class="text-sm text-gray-500">Wis token</button>
                </div>

                {{-- Speler naam veld --}}
                <label class="block mb-2">Speler</label>
                <input id="player_name" type="text" class="w-full border px-3 py-2 rounded mb-3" placeholder="Naam" />

                {{-- Score invoer veld --}}
                <label class="block mb-2">Score</label>
                <input id="score" type="number" min="0" class="w-full border px-3 py-2 rounded mb-3" placeholder="Score" />

                {{-- Verzenden knop --}}
                <div class="flex justify-center items-center">
                    <button id="submitBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Verstuur score</button>
                </div>
            </div>

            <div class="mt-4 text-center text-sm text-gray-600">
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
        document.getElementById('clearToken').addEventListener('click', (e) => {
            e.preventDefault();
            tokenInput.value = '';
            showAlert('Token gewist', 'text-gray-600 bg-gray-100');
        });

        function showAlert(message, classes = 'text-white bg-red-500') {
            const el = document.getElementById('form-alert');
            el.className = classes + ' p-2 rounded mb-4';
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
                        score: parseInt(score, 10)
                    })
                });

                if (res.status === 201) {
                    showAlert('Score succesvol toegevoegd', 'text-white bg-green-600');
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
                showAlert('Netwerkfout: ' + err.message);
            }
        });
    </script>
</body>

</html>