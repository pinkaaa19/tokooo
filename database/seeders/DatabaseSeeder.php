<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Pembeda Admin: Nilai role diisi 'admin'
        User::create([
            'name' => 'Admin Toko Toraja',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin', // <-- PEMBEDA UTAMA (Sebagai Admin)
        ]);

        // 2. Pembeda User: Nilai role diisi 'user' atau 'customer'
        User::create([
            'name' => 'Kustomer Toko Toraja',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user', // <-- PEMBEDA UTAMA (Sebagai Customer)
        ]);
    }
}
