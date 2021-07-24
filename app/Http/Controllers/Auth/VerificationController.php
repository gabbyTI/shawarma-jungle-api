<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\Contracts\IUser;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');

        $this->users = $users;
    }

    public function verify(Request $request, User $user)
    {
        //check if url is a valid signed url
        if (!URL::hasValidSignature($request)) {
            return ApiResponder::failureResponse("Invalid verification link or signature", 422);
        }

        //check if user has already verified email
        if ($user->hasVerifiedEmail()) {
            return ApiResponder::failureResponse("Email address already verified", 422);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return ApiResponder::successResponse("Email successfully verified");
    }

    public function resend(Request $request)
    {
        $this->validate($request, ['email' => ['required', 'email']]);

        $user = $this->users->findWhere('email', $request->email);

        if (!$user) {
            return ApiResponder::failureResponse("No user could be found with this email address", 422);
        }
        //check if user has already verified email
        if ($user->hasVerifiedEmail()) {
            return ApiResponder::failureResponse("Email address already verified", 422);
        }

        $user->sendEmailVerificationNotification();

        return ApiResponder::successResponse("Verification link resent");
    }
}
