<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponder;
use App\Http\Resources\VendorResource;
use App\Models\Vendor;
use App\Repositories\Contracts\IVendor;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\IsActive;
use Illuminate\Http\Request;

/**
 * @group Vendor Management
 */
class VendorController extends Controller
{
    protected $vendors;

    public function __construct(IVendor $vendors)
    {
        $this->vendors = $vendors;
    }

    public function getActiveVendors(Request $request)
    {
        $perPage = $request->query('perPage', 10); // retrieve perPage value from query params
        $page = $request->query('page', 1); // retrieve page value from query params

        $vendors = $this->vendors->withCriteria([
            new IsActive(),
        ])->all($perPage, $page);

        return ApiResponder::successResponse("List of active vendors", VendorResource::collection($vendors));
    }

    public function getVendor(Vendor $vendor)
    {
        $vendor = $this->vendors->withCriteria([
            new EagerLoad('products')
        ])->findWhere('id', $vendor->id);

        return ApiResponder::successResponse("Retrieved vendor", new VendorResource($vendor));
    }

    public function getVendorsWithin(Request $request)
    {
        $vendors = $this->vendors->within($request);
        return ApiResponder::successResponse('Success', VendorResource::collection($vendors));
    }
}
