<?php

namespace App\Exceptions;

use App\Helpers\ApiResponder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException && $request->expectsJson()) {
            return ApiResponder::failureResponse("You are not authorized to access this resource", 403);
        }

        if ($exception instanceof ModelNotFoundException && $request->expectsJson()) {

            return ApiResponder::failureResponse("The resource was not found in the database", 404);
        }

        if ($exception instanceof UnspecifiedModel && $request->expectsJson()) {

            return ApiResponder::failureResponse("The model is not specified", 404);
        }

        if ($exception instanceof AuthenticationException && $request->expectsJson()) {

            return ApiResponder::failureResponse("You are not logged in", 401);
        }

        if ($exception instanceof ValidationException && $request->expectsJson()) {

            return ApiResponder::failureResponse($exception->getMessage(),  $exception->status, $exception->errors());
            // return ApiResponder::failureResponse($exception->getMessage(),  $exception->status, $this->transformErrors($exception));
        }

        return parent::render($request, $exception);
    }

    // transform the error messages,
    private function transformErrors(ValidationException $exception)
    {
        $errors = [];

        foreach ($exception->errors() as $field => $message) {
            $errors[] = [
                $field => [
                    $message[0]
                ]
            ];
        }

        return $errors;
    }
}
