<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Menangani event "updated" pada Order.
     */
    public function updated(Order $order): void
    {
        // Jika payment_status berubah dari pending ke paid
        if ($order->wasChanged('payment_status') && $order->payment_status === 'paid') {
            
            // Ambil semua item di pesanan ini
            foreach ($order->orderItems as $item) {
                // Tambahkan jumlah quantity yang terjual ke kolom total_sold di tabel menus
                $item->menu->increment('total_sold', $item->quantity);
            }
        }
    }
}