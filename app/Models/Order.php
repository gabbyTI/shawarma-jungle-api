<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'amount',
        'order_products',
        'shipping_address_id',
        'payment_type',
        'status',
        // 'delivery_type',
        'delivery_fee',
        'is_delivery'
    ];

    protected $casts = [
        'order_products' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function shippingDetail()
    {
        return $this->hasOne(ShippingDetail::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
