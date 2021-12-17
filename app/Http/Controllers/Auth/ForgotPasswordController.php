<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Auth;

/**
 * @group Account Management
 */
class ForgotPasswordController extends Controller
{

    use SendsPasswordResetEmails;

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

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return ApiResponder::successResponse(trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return ApiResponder::failureResponse(trans($response), 422);
    }
}
