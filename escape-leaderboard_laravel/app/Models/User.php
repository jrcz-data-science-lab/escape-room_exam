<?php

// Het User model representeert een (admin) gebruiker die kan inloggen.
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // HasFactory: maakt factories voor testen/seeding mogelijk
    // Notifiable: biedt notificatie-mogelijkheden
    use HasFactory, Notifiable;

    /**
     * Velden die via mass-assignment ingevuld mogen worden
     */
    protected $fillable = [
        'name',     // Gebruikersnaam
        'email',    // Emailadres van de gebruiker
        'password', // Gehashed wachtwoord
        'is_admin', // Admin vlag
    ];

    /**
     * Velden die verborgen moeten blijven bij serialisatie (bv. naar JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Type-casts voor velden
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean', // Cast naar boolean
    ];
}
