<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Toon het login-formulier voor admins (redirect als al ingelogd)
    public function showLogin()
    {
        // Als user al ingelogd is, redirect naar admin dashboard
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.index');
        }

        return view('admin.login');
    }

    // Verwerk het login-formulier - DATABASE AUTHENTICATIE
    public function login(Request $request)
    {
        // Valideer email en wachtwoord
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email is verplicht',
            'email.email' => 'Voer een geldig emailadres in',
            'password.required' => 'Wachtwoord is verplicht',
        ]);

        // Zoek user in database
        $user = User::where('email', $credentials['email'])->first();

        // Check of user bestaat en wachtwoord klopt
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Check of user admin is
            if ($user->is_admin) {
                // Login de user
                Auth::login($user);
                $request->session()->regenerate();

                return redirect()->route('admin.index');
            } else {
                return back()->withErrors(['email' => 'Geen admin rechten']);
            }
        }

        // Login failed
        return back()->withErrors(['email' => 'Ongeldige inloggegevens'])
            ->withInput($request->except('password'));
    }

    // Logout action voor admin
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('leaderboard.index');
    }
}
