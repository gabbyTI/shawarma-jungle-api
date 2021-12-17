<?php

namespace App\Http\Controllers\User;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

/**
 * @group Profile Management
 */
class MeController extends Controller
{
    /**
     * User Me-Endpoint
     *
     *
     * This is a public endpoint that returns whether a user is logged in or not.
     * <aside class="notice">If the user is logged in, the profile detais are returned</aside>
     *
     *
     * @responseFile 200 scenario="when user is logged in" storage\responses\get.me.user.loggedin.json
     * @responseFile 200 scenario="when user is not logged in" storage\responses\get.me.loggedout.json
     */
    public function getMe()
    {
        if (auth()->check()) {
            return ApiResponder::meEndpointResponse(new UserResource(auth()->user()));
        }

        return ApiResponder::meEndpointResponse(null);
    }
}
