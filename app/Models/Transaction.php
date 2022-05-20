<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reference',
        'amount',
        'status',
        'paystack_fee',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
