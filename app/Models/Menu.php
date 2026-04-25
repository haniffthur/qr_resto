<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
    'category_id', 
    'name', 
    'description', 
    'price', 
    'image', 
    'status', 
    'total_sold', 
    'is_popular'
];

    /**
     * Relasi ke Kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Order Items (Untuk menghitung penjualan)
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}