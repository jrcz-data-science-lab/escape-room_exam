<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Edit Score</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('leaderboard.index') }}" class="text-lg font-semibold">Leaderboard</a>
            <div>
                <a href="{{ route('admin.index') }}" class="mr-4 text-sm text-gray-700">Admin</a>
                <a href="{{ route('leaderboard.index') }}" class="mr-4 text-sm text-gray-700">Home</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button class="text-sm text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        {{-- Pagina kop - toont welke score we aanpassen --}}
        <h1 class="text-2xl mb-4">Aanpassen score #{{ $score->id }}</h1>

        {{-- Formulier om een score te updaten (PUT) --}}
        <form method="POST" action="{{ route('admin.scores.update', $score->id) }}">@csrf @method('PUT')
            <div class="mb-4">
                <label class="block mb-1">Speler</label>
                {{-- Vult het veld met oude input of het huidige model attribuut --}}
                <input name="player_name" value="{{ old('player_name', $score->player_name) }}" class="w-full border px-3 py-2 rounded" />
            </div>
            <div class="mb-4">
                <label class="block mb-1">Score</label>
                <input name="score" value="{{ old('score', $score->score) }}" class="w-full border px-3 py-2 rounded" />
            </div>
            <div>
                {{-- Opslaan knop en annuleren link --}}
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Opslaan</button>
                <a href="{{ route('admin.index') }}" class="ml-2">Annuleer</a>
            </div>
        </form>
    </div>
</body>

</html>