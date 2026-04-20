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
    Schema::create('tables', function (Blueprint $table) {
        $table->id();
        $table->string('number')->unique(); // Contoh: '01', '02'
        $table->string('token')->unique();  // Untuk keamanan QR
        $table->enum('status', ['available', 'occupied'])->default('available');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
