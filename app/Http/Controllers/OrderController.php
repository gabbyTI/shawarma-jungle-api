<?php

namespace App\Http\Controllers;

use App\Events\OrderConfirmed;
use App\Helpers\ApiResponder;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Repositories\Contracts\IOrder;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\ForVendor;
use Illuminate\Http\Request;

/**
 * @group Order Management
 */
class OrderController extends Controller
{
    protected $orders;

    public function __construct(IOrder $orders)
    {
        $this->orders = $orders;
    }

    public function placeOrder(Request $request, Vendor $vendor)
    {
        $request->validate([
            'amount' => ['required'],
            'order_products' => ['required', 'array'],
            'shipping_address' => ['required_if:is_delivery,true', 'integer'],
            'is_delivery' => ['required', 'boolean'],
            'payment_type' => ['required', 'string', 'in:card,pay-on-delivery'],
            'delivery_fee' => ['required_if:is_delivery,true', 'integer']
        ]);
        // dd($request->all());

        $order = $this->orders->create([
            'user_id' => auth()->id(),
            'vendor_id' => $vendor->id,
            'amount' => $request->amount,
            'order_products' => $request->order_products,
            'shipping_detail_id' => $request->shipping_address,
            'delivery_type' => $request->delivery_type,
            'payment_type' => $request->payment_type,
        ]);

        return ApiResponder::successResponse("Order created", new OrderResource($order), 201);
    }

    public function getUserOrders()
    {
        $orders = $this->orders->withCriteria([
            new ForUser(auth()->id())
        ])->all();

        return ApiResponder::successResponse("Fetched user orders", OrderResource::collection($orders));
    }

    public function getVendorOrders()
    {
        $orders = $this->orders->withCriteria([
            new ForVendor(auth()->id())
        ])->all();

        return ApiResponder::successResponse("Fetched vendor orders", OrderResource::collection($orders));
    }

    public function getVendorOrder(Order $order)
    {
        $order = $this->orders->withCriteria([
            new EagerLoad("user")
        ])->find($order->id);

        return ApiResponder::successResponse("Fetched vendor order", new OrderResource($order));
    }

    public function setOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:confirmed,cancelled,completed']
        ]);

        $order = $this->orders->update($order->id, [
            'status' => $request->status
        ]);

        return ApiResponder::successResponse("Order $request->status", new OrderResource($order));
    }
}
