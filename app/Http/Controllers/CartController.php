<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Contracts\IProduct;
use Freshbitsweb\LaravelCartManager\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $products;

    public function __construct(IProduct $products)
    {
        cart()->setUser(auth()->id());
        $this->products = $products;
    }

    /**
     * User cart.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function cart()
    {
        cart()->refreshAllItemsData();
        // return cart()->totals();
        return ApiResponder::successResponse("Cart", new CartResource(cart()->toArray()));
    }

    /**
     * All user cart items.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function cartTotals()
    {
        cart()->refreshAllItemsData();
        return ApiResponder::successResponse("Cart totals", cart()->totals());
    }

    /**
     * All user cart items.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function cartItems()
    {
        cart()->refreshAllItemsData();
        return ApiResponder::successResponse("Cart Items", CartItemResource::collection(cart()->items()));
    }

    /**
     * Add to cart.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function addToCart(Product $product)
    {
        $this->products->addToCart($product->id);

        return ApiResponder::successResponse("Added To Cart", CartItemResource::collection(cart()->items()));
    }

    /**
     * Remove from cart.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function removeFromCart()
    {
        request()->validate([
            'cartItemIndex' => 'required'
        ]);

        cart()->removeAt(request('cartItemIndex'));

        cart()->refreshAllItemsData();

        return response()->json([
            'cartItems' => CartItemResource::collection(cart()->items())
        ]);
    }

    /**
     * Clear Cart
     *
     * @return json
     */
    public function clearCart()
    {
        return cart()->clear();
    }

    /**
     * Increment cart item quantity.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function cartItemQuantitySet()
    {
        request()->validate([
            'cartItemIndex' => 'required',
            'cartQuantity' => 'required',

        ]);

        cart()->refreshAllItemsData();

        cart()->setQuantityAt(request('cartItemIndex'), request('cartQuantity'));

        return response()->json([
            'cartItems' => CartItemResource::collection(cart()->items())
        ]);
    }

    /**
     * Increment cart item quantity.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function incrementCartItem()
    {
        request()->validate([
            'cartItemIndex' => 'required',
        ]);

        cart()->refreshAllItemsData();

        cart()->incrementQuantityAt(request('cartItemIndex'));

        return response()->json([
            'cartItems' => CartItemResource::collection(cart()->items())
        ]);
    }

    /**
     * Decrement cart item quantity.
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function decrementCartItem()
    {
        request()->validate([
            'cartItemIndex' => 'required',
        ]);

        cart()->refreshAllItemsData();

        cart()->decrementQuantityAt(request('cartItemIndex'));

        return response()->json([
            'cartItems' => CartItemResource::collection(cart()->items())
        ]);
    }

    /**
     * Applies the discount to the cart.
     *
     * @return Illuminate\Http\JsonResponse
     */
    // public function applyDiscount()
    // {
    //     if (request('discountType') == 1) {
    //         cart()->applyDiscount($percentage = request('discountInput'));
    //         return $this->getCartDetails();
    //     }
    //     cart()->applyFlatDiscount($amount = request('discountInput'));

    //     return $this->getCartDetails();
    // }
}
