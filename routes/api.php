<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShippingDetailController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\User\MeController;
use App\Http\Controllers\Vendor\VendorMeController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// API Version 1
// Route::group(['prefix' => 'v1'], function () {
//                                            PUBLIC ROUTES

Route::group(['middleware' => ['assign.guard:user-api']], function () {
    Route::get('me', [MeController::class, 'getMe']);
    Route::get('shop/vendors/get-vendors-within', [VendorController::class, 'getVendorsWithin']);
    Route::post('paystack/webhook', [PaymentController::class, 'payWebhook']);
});
Route::group(['middleware' => ['assign.guard:vendor-api']], function () {
    Route::get('vendor/me', [VendorMeController::class, 'getMe']);
});
//                                            END OF PUBLIC ROUTES

//                                            AUTHENTICATED ROUTES
Route::group(['middleware' => ['assign.guard:user-api', 'auth:user-api']], function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('account/delete', [LoginController::class, 'deleteAccount']);

    Route::put('settings/profile', [SettingController::class, 'updateUserProfile']);
    Route::put('settings/password', [SettingController::class, 'updateUserPassword']);

    Route::post('users/shipping-details', [ShippingDetailController::class, 'createShippingDetail']);
    Route::get('users/shipping-details', [ShippingDetailController::class, 'getUserShippingDetails']);
    Route::get('users/shipping-details/{shippingDetail}', [ShippingDetailController::class, 'getUserShippingDetail']);
    Route::put('users/shipping-details/{shippingDetail}', [ShippingDetailController::class, 'updateShippingDetail']);
    Route::delete('users/shipping-details/{shippingDetail}', [ShippingDetailController::class, 'deleteShippingDetail']);

    Route::get('vendors', [VendorController::class, 'getActiveVendors']);
    Route::get('vendors/{vendor}', [VendorController::class, 'getVendor']);

    //shop
    Route::get('shop/vendors/{vendor}/products', [ShopController::class, 'getVendorProducts']);
    Route::get('shop/vendors/products/{product}', [ShopController::class, 'getVendorProduct']);

    //payment
    Route::post('pay/{order}', [PaymentController::class, 'pay']);


    //cart
    Route::get('cart', [CartController::class, 'cart']);
    Route::get('cart/items', [CartController::class, 'cartItems']);
    Route::get('cart/totals', [CartController::class, 'cartTotals']);
    Route::post('cart/add-to-cart/{product}', [CartController::class, 'addToCart']);
    Route::post('cart/remove-from-cart', [CartController::class, 'removeFromCart']);
    Route::post('cart/clear', [CartController::class, 'clearCart']);
    Route::post('cart/cart-item-quantity-set', [CartController::class, 'cartItemQuantitySet']);
    Route::post('cart/increment-cart-item', [CartController::class, 'incrementCartItem']);
    Route::post('cart/decrement-cart-item', [CartController::class, 'decrementCartItem']);
    // Route::post('cart/apply-discount/', [CartController::class, 'applyDiscount']);

    //orders
    Route::post('orders/vendor/{vendor}', [OrderController::class, 'placeOrder']);
    Route::get('orders/user', [OrderController::class, 'getUserOrders']);
});

Route::group(['prefix' => 'vendor', 'middleware' => ['assign.guard:vendor-api', 'auth:vendor-api']], function () {

    Route::put('settings/password', [SettingController::class, 'updateVendorPassword']);
    //products
    Route::get('products', [ProductController::class, 'getVendorProducts']);
    Route::get('products/{product}', [ProductController::class, 'getVendorProduct']);
    Route::post('products', [ProductController::class, 'createProduct']);
    Route::put('products/{product}', [ProductController::class, 'updateProduct']);
    Route::delete('products/{product}', [ProductController::class, 'deleteProduct']);
    //orders
    Route::get('orders/', [OrderController::class, 'getVendorOrders']);
    Route::get('orders/{order}', [OrderController::class, 'getVendorOrder']);
    Route::patch('orders/{order}/set-order-status', [OrderController::class, 'setOrderStatus']);
});
//                                            END OF AUTHENTICATED ROUTES

//                                            GUEST ROUTES
Route::group(['middleware' => ['assign.guard:user-api', 'guest:user-api']], function () {
    Route::post('register/user', [RegisterController::class, 'register']);
    Route::post('verification/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verification/resend', [VerificationController::class, 'resend']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
});

Route::group(['prefix' => 'vendor', 'middleware' => ['assign.guard:vendor-api', 'guest:vendor-api']], function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('verification/verify/{vendor}', [VerificationController::class, 'verifyVendor'])->name('verification.verify.vendor');
    Route::post('verification/resend', [VerificationController::class, 'resendVendor']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);
});
    //                                            END OF GUEST ROUTES
// });
