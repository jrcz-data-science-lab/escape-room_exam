<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

    <div class="home-container" style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 100px);">
        <div class="admin-wrapper" style="max-width: 500px; width: 100%;">
            <h2 class="admin-title">Admin Login</h2>

            @if($errors->any())
            <div style="background: rgba(255, 71, 87, 0.2); color: var(--danger-red); padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid var(--danger-red); text-align: center; font-weight: 600;">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="login-section">
                @csrf
                <label style="width: 100%; text-align: left; color: var(--text-light); font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; font-size: 14px;">Wachtwoord</label>
                <input name="password" type="password" class="login-input" required autofocus />
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
</body>

</html>