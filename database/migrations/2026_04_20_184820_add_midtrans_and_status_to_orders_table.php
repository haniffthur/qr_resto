<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kode Unik Order buat Midtrans
            if (!Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code')->unique()->nullable()->after('id');
            }
            // Nama Pemesan (Opsional, biasa diambil dari Meja)
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('table_id');
            }
            // Status Dapur (pending, processing, completed)
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending')->after('total_price');
            }
            // Status Pembayaran (pending, success, failed)
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('status');
            }
            // Token dari Midtrans Snap
            if (!Schema::hasColumn('orders', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('payment_status');
            }
            // Catatan pesanan dari customer
            if (!Schema::hasColumn('orders', 'note')) {
                $table->text('note')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_code', 'customer_name', 'status', 'payment_status', 'snap_token', 'note']);
        });
    }
};