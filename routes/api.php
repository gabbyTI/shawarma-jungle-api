<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingDetailController;
use App\Http\Controllers\User\MeController;
use App\Http\Controllers\Vendor\VendorMeController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
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
Route::group(['middleware' => ['assign.guard:user-api', 'auth:user-api']], function () {
    Route::get('me', [MeController::class, 'getMe']);
});
Route::group(['middleware' => ['assign.guard:vendor-api']], function () {
    Route::get('vendor/me', [VendorMeController::class, 'getMe']);
});
//                                            END OF PUBLIC ROUTES

//                                            AUTHENTICATED ROUTES
Route::group(['middleware' => ['assign.guard:user-api', 'auth:user-api']], function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('account/delete', [LoginController::class, 'deleteAccount']);

    Route::post('users/shipping-details', [ShippingDetailController::class, 'createShippingDetail']);
    Route::get('users/shipping-details', [ShippingDetailController::class, 'getUserShippingDetails']);
    Route::get('users/shipping-details/{shippingDetail}', [ShippingDetailController::class, 'getUserShippingDetail']);
    Route::put('users/shipping-details/{shippingDetail}', [ShippingDetailController::class, 'updateShippingDetail']);
    Route::delete('users/shipping-details/{shippingDetail}', [ShippingDetailController::class, 'deleteShippingDetail']);

    Route::get('vendors', [VendorController::class, 'getActiveVendors']);
    Route::get('vendors/{vendor}', [VendorController::class, 'getVendor']);

    Route::post('orders/users/{user}/vendors/{vendor}', [OrderController::class, 'placeOrder']);
    Route::get('orders/users', [OrderController::class, 'getUsersOrders']);
});

Route::group(['prefix' => 'vendor', 'middleware' => ['assign.guard:vendor-api', 'auth:vendor-api']], function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('account/delete', [LoginController::class, 'deleteAccount']);

    Route::get('products', [ProductController::class, 'getVendorProducts']);
    Route::get('products/{product}', [ProductController::class, 'getVendorProduct']);
    Route::post('products', [ProductController::class, 'createProduct']);
    Route::put('products/{product}', [ProductController::class, 'updateProduct']);
    Route::delete('products/{product}', [ProductController::class, 'deleteProduct']);
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
