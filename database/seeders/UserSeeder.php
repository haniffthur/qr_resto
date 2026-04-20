<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Bikin Akun Admin (Bos)
        User::create([
            'name' => 'Admin Resto',
            'email' => 'admin@resto.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Bikin Akun Kasir (Petugas)
        User::create([
            'name' => 'Kasir Satu',
            'email' => 'kasir@resto.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);
    }
}