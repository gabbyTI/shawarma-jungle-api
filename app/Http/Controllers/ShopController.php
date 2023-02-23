<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Vendor;
use App\Repositories\Contracts\IProduct;
use App\Repositories\Eloquent\Criteria\ForVendor;
use App\Repositories\Eloquent\Criteria\IsActive;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $products;

    public function __construct(IProduct $products)
    {
        $this->products = $products;
    }

    public function getVendorProducts(Request $request, Vendor $vendor)
    {
        $perPage = $request->query('perPage', 10); // retrieve perPage value from query params
        $page = $request->query('page', 1); // retrieve page value from query params

        $products = $this->products
            ->withCriteria([
                new ForVendor($vendor->id),
                new IsActive()
            ])
            ->all($perPage, $page);
        return ApiResponder::successResponse("Successful", ProductResource::collection($products));
    }

    public function getVendorProduct(Product $product)
    {
        // $this->authorize('view', $product);

        return ApiResponder::successResponse("Successful", new ProductResource($product));
    }
}
