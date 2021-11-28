<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'slug',
        'image',
        'price',
        'is_active'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
