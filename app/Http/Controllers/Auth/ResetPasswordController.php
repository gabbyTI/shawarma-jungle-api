<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function broker()
    {
        $middlewareDriver = Auth::getDefaultDriver();
        switch ($middlewareDriver) {
            case 'user-api':
                return Password::broker('users');
                break;

            case 'vendor-api':
                return Password::broker('vendors');
                break;
        }
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return ApiResponder::successResponse(trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return ApiResponder::failureResponse(trans($response), 422);
    }
}
