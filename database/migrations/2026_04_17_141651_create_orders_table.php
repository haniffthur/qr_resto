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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('table_id')->constrained();
        $table->string('order_code')->unique();
        $table->integer('total_price');
        $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
        $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
        $table->string('payment_method'); // QRIS, TRANSFER, CASH
        $table->string('payment_reference')->nullable(); // ID dari Midtrans
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
