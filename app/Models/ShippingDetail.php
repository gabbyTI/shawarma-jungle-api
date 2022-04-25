<?php

namespace App\Models;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'landmark',
        'description',
        'phone',
        'second_phone',
        'state',
        'city',
        'location',
    ];

    protected $casts = [
        'location' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(User::class);
    }
}
