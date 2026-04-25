<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
 protected $fillable = [
        'table_id', 
        'order_code',
        'customer_name', 
        'total_price', 
        'status', 
        'payment_status',
        'snap_token',
        'note'
    ];


    // Relasi ke Table (Meja)
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}