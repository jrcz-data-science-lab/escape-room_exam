<?php

namespace App\Http\Controllers\Admin;
// Namespace voor administratie controllers

use App\Http\Controllers\Controller;
// Basis controller import

use Illuminate\Http\Request;
// Request import voor toegang tot form input en sessie

class AuthController extends Controller
{
    // Toon het login-formulier voor admins
    public function showLogin()
    {
        return view('admin.login'); // render de Blade view resources/views/admin/login.blade.php
    }

    // Verwerk het login-formulier
    public function login(Request $request)
    {
        // Valideer dat een wachtwoord is opgegeven
        $request->validate([
            'password' => 'required|string'
        ]);

        // Haal het opgegeven wachtwoord uit de request
        $password = $request->input('password');

        // Vergelijk met de ADMIN_PASSWORD uit de environment
        // LET OP: in productie wil je gehashte wachtwoorden en een gebruikersmodel
        if ($password === env('ADMIN_PASSWORD')) {
            // Sla in de sessie op dat de gebruiker admin is
            $request->session()->put('is_admin', true);
            // Redirect naar admin dashboard
            return redirect()->route('admin.index');
        }

        // Bij foutieve inlog: terug naar formulier met foutmelding
        return back()->withErrors(['password' => 'Onjuiste beheerderswachtwoord']);
    }

    // Logout action voor admin
    public function logout(Request $request)
    {
        // Invalideer de volledige sessie en genereer een nieuw CSRF-token
        // Dit voorkomt session-fixation en zorgt dat oude sessie-cookies ongeldig worden
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('leaderboard.index');
    }
}
