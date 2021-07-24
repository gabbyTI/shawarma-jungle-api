<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function getMe()
    {
        if (auth()->check()) {
            return ApiResponder::meEndpointResponse(new UserResource(auth()->user()));
        }

        return ApiResponder::meEndpointResponse(null);
    }
}
