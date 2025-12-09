<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Nieuw spel</title>
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
        <div class="max-w-xl mx-auto mt-8">
            <h1 class="text-2xl font-bold mb-4">Nieuw spel toevoegen</h1>

            @if(session('success'))
            <div class="mb-4 text-green-700">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.games.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Naam</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="border rounded w-full px-3 py-2">
                    @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Beschrijving (optioneel)</label>
                    <textarea name="description" class="border rounded w-full px-3 py-2" rows="4">{{ old('description') }}</textarea>
                    @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
                </div>

                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Toevoegen</button>
                    <a href="{{ route('admin.games.index') }}" class="ml-3 text-gray-600">Annuleren</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>