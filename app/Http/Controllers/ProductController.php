<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\IProduct;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForVendor;
use App\Repositories\Eloquent\Criteria\OneProduct;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    protected $products;

    public function __construct(IProduct $products)
    {
        $this->products = $products;
    }

    public function createProduct(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            // 'image' => ['mimes:png,jpeg,gif,bmp', 'max:2048'],
            'price' => ['required'],
        ]);

        $product = $this->products->create([
            'vendor_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => (int)$request->price
        ]);

        return ApiResponder::successResponse("Created product successfully", new ProductResource($product), 201);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string'],
            // 'image' => ['mimes:png,jpeg,gif,bmp', 'max:2048'],
            'is_active' => ['required', 'boolean']
        ]);

        $product = $this->products->update($product->id, [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => (int)$request->price,
            'is_active' => $request->is_active
        ]);

        return ApiResponder::successResponse("Updated product successfully", new ProductResource($product));
    }


    public function getVendorProducts()
    {
        $products = $this->products
            ->withCriteria([
                new ForVendor(auth()->id())
            ])
            ->all();
        return ApiResponder::successResponse("Successful", ProductResource::collection($products));
    }

    public function getVendorProduct(Product $product)
    {
        $product = $this->products->findWhere('id', $product->id);

        return ApiResponder::successResponse("Successful", new ProductResource($product));
    }

    public function deleteProduct(Product $product)
    {
        $this->products->delete($product->id);

        return ApiResponder::successResponse("Product successfully deleted", code: 204);
    }
}
