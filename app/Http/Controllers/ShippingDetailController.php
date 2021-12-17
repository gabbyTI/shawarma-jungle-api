<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\ShippingDetailResource;
use App\Repositories\Contracts\IShippingDetail;
use App\Models\ShippingDetail;
use App\Repositories\Eloquent\Criteria\ForUser;
use Illuminate\Http\Request;

/**
 * @group Shipping Details Management
 */
class ShippingDetailController extends Controller
{
    protected $shippingDetails;

    public function __construct(IShippingDetail $shippingDetails)
    {
        $this->shippingDetails = $shippingDetails;
    }

    public function createShippingDetail(Request $request)
    {
        $request->validate([
            'address' => ['required', 'string'],
            'landmark' => ['string', 'max:250'],
            'description' => ['string', 'max:500'],
            'phone' => ['required', 'string', 'max:14'],
            'second_phone' => ['string', 'max:14'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
        ]);

        $shippingDetail = $this->shippingDetails->create([
            'user_id' => auth()->id(),
            'address' => $request->address,
            'landmark' => $request->landmark,
            'description' => $request->description,
            'phone' => $request->phone,
            'second_phone' => $request->second_phone,
            'state' => $request->state,
            'city' => $request->city,
        ]);

        return ApiResponder::successResponse("created shipping address", new ShippingDetailResource($shippingDetail), 201);
    }

    public function getUserShippingDetails()
    {
        $shippingDetails = $this->shippingDetails->withCriteria([
            new ForUser(auth()->id())
        ])->all();

        return ApiResponder::successResponse("Successful", ShippingDetailResource::collection($shippingDetails));
    }

    public function getUserShippingDetail(ShippingDetail $shippingDetail)
    {
        return ApiResponder::successResponse("Successful", new ShippingDetailResource($shippingDetail));
    }

    public function updateShippingDetail(Request $request, ShippingDetail $shippingDetail)
    {
        $this->authorize('update', $shippingDetail);

        $request->validate([
            'address' => ['required', 'string'],
            'landmark' => ['string', 'max:250'],
            'description' => ['string', 'max:500'],
            'phone' => ['required', 'string', 'max:14'],
            'second_phone' => ['string', 'max:14'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
        ]);

        $shippingDetail = $this->shippingDetails->update($shippingDetail->id, $request->all());

        return ApiResponder::successResponse("Updated", new ShippingDetailResource($shippingDetail));
    }

    public function deleteShippingDetail(ShippingDetail $shippingDetail)
    {
        $this->authorize('delete', $shippingDetail);

        $this->shippingDetails->delete($shippingDetail->id);

        return ApiResponder::successResponse("Deleted", null, 204);
    }
}
