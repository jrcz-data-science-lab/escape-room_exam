<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Games beheer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('leaderboard.index') }}" class="text-lg font-semibold">Leaderboard</a>
            <div>
                <a href="{{ route('admin.index') }}" class="mr-4 text-sm text-gray-700">Scorebeheer</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button class="text-sm text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Games beheer</h1>
            <a href="{{ route('admin.games.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Nieuwe game</a>
        </div>
        @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border rounded">{{ session('success') }}</div>
        @endif
        <div class="grid gap-4">
            @foreach($games as $game)
            <div class="p-4 bg-white rounded shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold">{{ $game->name }} <span class="text-sm text-gray-500">({{ $game->slug }})</span></h2>
                        <p class="text-sm text-gray-600">{{ $game->description }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('leaderboard.game', $game->slug) }}" class="px-3 py-2 border rounded text-sm">Bekijk public</a>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.games.addScore', $game->id) }}" class="mt-3 flex gap-2">
                    @csrf
                    <input name="player_name" placeholder="Speler naam" class="border px-2 py-1 rounded" required>
                    <input name="score" placeholder="Score" type="number" class="border px-2 py-1 rounded w-24" required>
                    <button class="px-3 py-1 bg-gray-800 text-white rounded">Snelle toevoeging</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>