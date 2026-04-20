<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // 1. Buat Kategori
    $makanan = \App\Models\Category::create(['name' => 'Makanan Utama', 'slug' => 'makanan-utama']);
    $minuman = \App\Models\Category::create(['name' => 'Minuman Segar', 'slug' => 'minuman-segar']);
    $snack = \App\Models\Category::create(['name' => 'Cemilan', 'slug' => 'cemilan']);

    // 2. Buat Menu
    $makanan->menus()->createMany([
        ['name' => 'Nasi Goreng Spesial', 'price' => 25000, 'description' => 'Nasi goreng dengan telur dan ayam suwir'],
        ['name' => 'Mie Ayam Jamur', 'price' => 18000, 'description' => 'Mie kenyal dengan topping ayam jamur gurih'],
    ]);

    $minuman->menus()->createMany([
        ['name' => 'Es Teh Manis', 'price' => 5000, 'description' => 'Kesegaran teh asli'],
        ['name' => 'Jus Alpukat', 'price' => 15000, 'description' => 'Alpukat mentega dengan kental manis cokelat'],
    ]);

    // 3. Buat 5 Meja Otomatis
    for ($i = 1; $i <= 5; $i++) {
        \App\Models\Table::create([
            'number' => str_pad($i, 2, '0', STR_PAD_LEFT), // Hasilnya: 01, 02, dst
            'token' => \Illuminate\Support\Str::random(10), // Buat keamanan URL QR
            'status' => 'available'
        ]);
    }
}
}
