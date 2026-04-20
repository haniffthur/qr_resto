<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    public function table() {
        return $this->belongsTo(Table::class);
    }

    public function details() {
        return $this->hasMany(OrderDetail::class);
    }
}