<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ReceiptQr extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'user_id',
        'order_id',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
