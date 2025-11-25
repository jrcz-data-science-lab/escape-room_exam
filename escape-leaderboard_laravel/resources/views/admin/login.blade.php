<!doctype html>
<html lang="nl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    {{-- Top navigatiebalk (zelfde als op de publieke pagina) — zwart voor betere zichtbaarheid --}}
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

    {{-- Eenvoudige, gecentreerde admin login voor rust en focus --}}
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h2 class="text-2xl mb-4">Admin login</h2>

            {{-- Foutmelding area: toont validatie fouten als die er zijn --}}
            @if($errors->any())
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4">{{ $errors->first() }}</div>
            @endif

            {{-- Login formulier stuurt POST naar admin.login.post route --}}
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <label class="block mb-2">Wachtwoord</label>
                <input name="password" type="password" class="w-full border px-3 py-2 rounded mb-4" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Login</button>
            </form>
        </div>
    </div>
</body>

</html>