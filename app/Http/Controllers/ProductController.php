<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\ProductResource;
use App\Jobs\UploadImage;
use App\Models\Product;
use App\Repositories\Contracts\IProduct;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForVendor;
use App\Repositories\Eloquent\Criteria\OneProduct;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SebastianBergmann\Environment\Console;

/**
 * @group Product Management
 */
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
            'image' => ['required', 'mimes:png,jpeg,gif,bmp', 'max:2048'],
            'price' => ['required'],
        ]);

        //get the image
        $image = $request->file('image');

        $filename = $this->UploadToTempLocation($image);

        $product = $this->products->create([
            'vendor_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $filename,
            'disk' => config('site.upload_disk'),
            'price' => (int)$request->price
        ]);

        //dispacth job to handle image manipulation and upload to permanent location
        $this->dispatch(new UploadImage($product));

        return ApiResponder::successResponse("Created product successfully", new ProductResource($product), 201);
    }

    public function updateProduct(Request $request, Product $product)
    {

        // dd($request->input());

        $this->authorize('update', $product);

        $request->validate([
            'name' => ['required', 'string'],
            'image' => ['mimes:png,jpeg,gif,bmp', 'max:2048'],
            'is_active' => ['required'],
            'price' => ['required'],
        ]);

        //get the image
        $image = $request->file('image');

        // if image user doesnt change image(image feild is empty) then update other fields
        if (!$image) {
            $product = $this->products->update($product->id, $request->input());

            return ApiResponder::successResponse("Updated product successfully", new ProductResource($product));
        }

        $filename = $this->UploadToTempLocation($image);

        // Delete previous image before the update to change the image
        foreach (['thumbnail', 'large', 'original'] as $size) {
            //check if file exist
            if (Storage::disk($product->disk)->exists("uploads/products/{$size}/" . $product->image)) {
                Storage::disk($product->disk)->delete("uploads/products/{$size}/" . $product->image);
            }
        }

        $product = $this->products->update($product->id, [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => (int)$request->price,
            'image' => $filename,
            'upload_successful' => false,
            'is_active' => (bool)$request->is_active
        ]);

        //dispacth job to handle image manipulation and upload to permanent location
        $this->dispatch(new UploadImage($product));

        return ApiResponder::successResponse("Updated product successfully", new ProductResource($product));
    }

    public function getVendorProducts(Request $request)
    {
        $perPage = $request->query('perPage', 10); // retrieve perPage value from query params
        $page = $request->query('page', 1); // retrieve page value from query params

        $products = $this->products
            ->withCriteria([
                new ForVendor(auth()->id())
            ])
            ->all($perPage, $page);
        return ApiResponder::successResponse("Successful", ProductResource::collection($products));
    }

    public function getVendorProduct(Product $product)
    {
        // $this->authorize('view', $product);

        return ApiResponder::successResponse("Successful", new ProductResource($product));
    }

    public function deleteProduct(Product $product)
    {
        $this->authorize('delete', $product);


        //Deleting the images associated with the products
        foreach (['thumbnail', 'large', 'original'] as $size) {
            //check if file exist
            if (Storage::disk($product->disk)->exists("uploads/products/{$size}/" . $product->image)) {
                Storage::disk($product->disk)->delete("uploads/products/{$size}/" . $product->image);
            }
        }

        $this->products->delete($product->id);

        return ApiResponder::successResponse("Product successfully deleted", code: 204);
    }

    public function UploadToTempLocation($image)
    {
        // ofiice card.png = timestamp()_office_card.pnp
        $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

        // move image to temp location (tmp disk)
        $image->storeAs('uploads/original', $filename, 'tmp');

        return $filename;
    }
}
