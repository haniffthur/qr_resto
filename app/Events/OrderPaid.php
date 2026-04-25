<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// Perhatikan "ShouldBroadcastNow" -> Biar langsung siaran detik itu juga
class OrderPaid implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // Ini nama "Frekuensi Radio" yang bakal didengerin sama layar Kasir
    public function broadcastOn(): array
    {
        return [
            new Channel('kasir-channel'),
        ];
    }
}