<?php

namespace App\Repositories\Contracts;

interface IProduct
{
    public function getVendorProducts($vendor);
    public function getVendorProduct($vendor, $product_id);
    public function addToCart($productId);
}
