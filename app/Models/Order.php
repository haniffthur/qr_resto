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
        'total_price',
        'status',
        'payment_status'
    ];

    // INI YANG KURANG: Relasi ke OrderItem
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke Table (Meja)
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}