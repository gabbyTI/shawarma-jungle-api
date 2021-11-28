<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\IProduct;

class ProductRepository extends BaseRepository implements IProduct
{
    public function model()
    {
        return Product::class;
    }

    public function getVendorProducts($vendor)
    {
        return $vendor->products;
    }

    public function getVendorProduct($vendor, $product_id)
    {
        return $vendor->products->where('id', $product_id);
    }
}
