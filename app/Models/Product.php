<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'slug',
        'image',
        'price',
        'is_active',
        'upload_successful',
        'disk',
        'description'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getImagesAttribute()
    {
        return [
            "thumbnail" => $this->getImagePath("thumbnail"),
            "original" => $this->getImagePath("original"),
            "large" => $this->getImagePath("large"),
        ];
    }

    public function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/products/{$size}/" . $this->image);
    }
}
