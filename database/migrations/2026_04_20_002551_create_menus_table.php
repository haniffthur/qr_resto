<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel categories
            // cascadeOnDelete(): Kalau kategori dihapus, menu di dalamnya ikut hapus
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2); // Menggunakan decimal untuk akurasi uang (12 digit, 2 desimal)
            $table->string('image')->nullable();
            
            // Kolom Status (Nomor 2 yang kita bahas)
            $table->string('status')->default('available'); // available / unavailable
            
            // Kolom Menu Populer (Hybrid Observer)
            $table->integer('total_sold')->default(0); 
            
            // Kolom Flag Populer Manual (Opsional, jika admin ingin set manual)
            $table->boolean('is_popular')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};