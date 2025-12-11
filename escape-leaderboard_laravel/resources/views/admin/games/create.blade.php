<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nieuwe Escape Room toevoegen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('leaderboard.index') }}" class="text-lg font-semibold">Leaderboard</a>
            <div>
                <a href="{{ route('admin.games.index') }}" class="mr-4 text-sm text-gray-700">Games beheer</a>
                <a href="{{ route('admin.index') }}" class="mr-4 text-sm text-gray-700">Scorebeheer</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button class="text-sm text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-8 max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Nieuwe Escape Room toevoegen</h1>
        @if(session('new_game_token'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded">
            <p class="font-semibold">Game aangemaakt — kopieer het token:</p>
            <div class="mt-2 flex items-center gap-2">
                <input readonly class="flex-1 p-2 border rounded" value="{{ session('new_game_token') }}" id="newToken">
                <button onclick="navigator.clipboard.writeText(document.getElementById('newToken').value)" class="px-3 py-2 bg-gray-800 text-white rounded">Kopieer</button>
            </div>
            <p class="text-sm text-gray-600 mt-2">Slug: <strong>{{ session('new_game_slug') }}</strong></p>
            <p class="text-sm text-red-600 mt-1">Bewaar dit token veilig — het wordt niet nogmaals getoond.</p>
        </div>
        @endif
        <form method="POST" action="{{ route('admin.games.store') }}" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            <div>
                <label class="block text-sm font-medium">Naam</label>
                <input name="name" required class="mt-1 w-full border px-3 py-2 rounded" value="{{ old('name') }}">
                @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Slug (optioneel)</label>
                <input name="slug" class="mt-1 w-full border px-3 py-2 rounded" value="{{ old('slug') }}">
                <p class="text-sm text-gray-500 mt-1">Wordt gebruikt in de URL (bv. escape-1). Leeg laten genereert automatisch een slug.</p>
                @error('slug') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Omschrijving (optioneel)</label>
                <textarea name="description" class="mt-1 w-full border px-3 py-2 rounded">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-green-600 text-white rounded">Aanmaken</button>
                <a href="{{ route('admin.games.index') }}" class="px-4 py-2 border rounded">Annuleren</a>
            </div>
        </form>
    </div>
</body>
</html>