<?php

namespace App\Helpers;

class ApiResponder
{
    public static function failureResponse($message, $code, $verification_errors = null)
    {
        return response()->json(
            [
                "success" => false,
                "message" => $message,
                "verification_errors" => $verification_errors
            ],
            $code
        );
    }

    public static function successResponse($message, $data = null, $code = 200)
    {
        return response()->json(
            [
                "success" => true,
                "message" => $message,
                "data" => $data
            ],
            $code
        );
    }

    public static function meEndpointResponse($user)
    {
        $isLoggedIn = $user == null ? false : true;
        return response()->json(
            [
                "logged_in" => $isLoggedIn,
                "user" => $user,
            ],
        );
    }
}
