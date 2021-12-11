<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use App\Repositories\Contracts\IVendor;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\IsActiveVendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    protected $vendors;

    public function __construct(IVendor $vendors)
    {
        $this->vendors = $vendors;
    }

    public function getActiveVendors()
    {
        $vendors = $this->vendors->withCriteria([
            new IsActiveVendor(),
        ])->all();

        return ApiResponder::successResponse("List of active vendors", VendorResource::collection($vendors));
    }

    public function getVendor(Vendor $vendor)
    {
        $vendor = $this->vendors->withCriteria([
            new EagerLoad('products')
        ])->findWhere('id', $vendor->id);

        return ApiResponder::successResponse("Retrieved vendor", new VendorResource($vendor));
    }

    public function search(Request $request)
    {
        $vendors = $this->vendors->search($request);
        return ApiResponder::successResponse('Success', VendorResource::collection($vendors));
    }
}
