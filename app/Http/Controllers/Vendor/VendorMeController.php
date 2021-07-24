<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Request;

class VendorMeController extends Controller
{
    public function getMe()
    {
        if (auth()->check()) {
            return ApiResponder::meEndpointResponse(new VendorResource(auth()->user()));
        }

        return ApiResponder::meEndpointResponse(null);
    }
}
