<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Table;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori
        $makanan = Category::create([
            'name' => 'Makanan', 
            'slug' => 'makanan'
        ]);
        
        $minuman = Category::create([
            'name' => 'Minuman', 
            'slug' => 'minuman'
        ]);
        
        $dessert = Category::create([
            'name' => 'Dessert', 
            'slug' => 'dessert'
        ]);

        // 2. Buat Menu Makanan (2 Item: 1 Populer, 1 Biasa)
        $makanan->menus()->createMany([
            [
                'name' => 'Nasi Goreng Spesial', 
                'price' => 38000, 
                'description' => 'Nasi goreng bumbu rahasia dengan telur ceplok, udang segar, dan kerupuk renyah.',
                'is_popular' => true,
                'image' => null
            ],
            [
                'name' => 'Soto Ayam Lamongan', 
                'price' => 28000, 
                'description' => 'Soto ayam kuah kuning gurih dengan koya spesial, soun, dan telur rebus.',
                'is_popular' => false,
                'image' => null
            ],
        ]);

        // 3. Buat Menu Minuman (2 Item: 1 Populer, 1 Biasa)
        $minuman->menus()->createMany([
            [
                'name' => 'Es Kopi Susu Aren', 
                'price' => 22000, 
                'description' => 'Perpaduan espresso house blend dengan susu segar dan manisnya gula aren murni.',
                'is_popular' => true,
                'image' => null
            ],
            [
                'name' => 'Es Teh Cincau', 
                'price' => 12000, 
                'description' => 'Es teh manis segar dengan tambahan irisan cincau hitam yang kenyal.',
                'is_popular' => false,
                'image' => null
            ],
        ]);

        // 4. Buat Menu Dessert (2 Item: 1 Populer, 1 Biasa)
        $dessert->menus()->createMany([
            [
                'name' => 'Pisang Goreng Keju', 
                'price' => 18000, 
                'description' => 'Pisang kepok goreng krispi dengan taburan keju cheddar parut dan susu kental manis.',
                'is_popular' => true,
                'image' => null
            ],
            [
                'name' => 'Pudding Cokelat Vla', 
                'price' => 15000, 
                'description' => 'Pudding cokelat lembut yang disajikan dingin dengan siraman vla vanilla manis.',
                'is_popular' => false,
                'image' => null
            ],
        ]);

        // 5. Buat 5 Meja Otomatis untuk Testing
        for ($i = 1; $i <= 5; $i++) {
            Table::create([
                'number' => 'A' . str_pad($i, 2, '0', STR_PAD_LEFT), // Hasil: A01, A02, dst
                'token' => Str::random(10),
                'status' => 'available'
            ]);
        }
    }
}