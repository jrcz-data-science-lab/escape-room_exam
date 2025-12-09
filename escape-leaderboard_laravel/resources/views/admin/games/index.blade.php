<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Spellen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('leaderboard.index') }}" class="text-lg font-semibold">Leaderboard</a>
            <div>
                <a href="{{ route('leaderboard.index') }}" class="mr-4 text-sm text-gray-700">Home</a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">@csrf
                    <button class="text-sm text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl">Spellen</h1>
            <a href="{{ route('admin.games.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded">Nieuw spel</a>
        </div>

        @if($games->isEmpty())
        <p>Geen spellen gevonden.</p>
        @else
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">Naam</th>
                        <th class="px-4 py-2">Slug</th>
                        <th class="px-4 py-2">Aangemaakt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $game)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $game->name }}</td>
                        <td class="px-4 py-2">{{ $game->slug }}</td>
                        <td class="px-4 py-2">{{ $game->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</body>

</html>