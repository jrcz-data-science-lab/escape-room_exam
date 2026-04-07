<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Maak admin user met gehasht wachtwoord
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@escape-room.com',
            'password' => Hash::make('admin123'), // Gehasht met Bcrypt/Argon2
            'is_admin' => true,
        ]);

        // Optionele test user (geen admin)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);
    }
}
