<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Request;

/**
 * @group Vendor
 */
/**
 * @group Profile Management
 */

class VendorMeController extends Controller
{

    /**
     * Vendor Me-Endpoint
     *
     * This is a public endpoint that returns whether a vendor is logged in or not.
     * <aside class="notice">If the vendor is logged in, the profile detais are returned</aside>
     *
     * @responseFile 200 scenario="when vendor is logged in" storage\responses\get.me.vendor.loggedin.json
     * @responseFile 200 scenario="when vendor is not logged in" storage\responses\get.me.loggedout.json
     */
    public function getMe()
    {
        if (auth()->check()) {
            return ApiResponder::meEndpointResponse(new VendorResource(auth()->user()));
        }

        return ApiResponder::meEndpointResponse(null);
    }
}
