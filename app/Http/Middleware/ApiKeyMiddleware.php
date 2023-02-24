<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the current route is defined in the api.php file
        if ($request->is('api/*')) {
            $apiKey = $request->header('X-API-Key');

            if (!in_array($apiKey, explode(',', config('app.api_keys')))) {
                return response()->json(['message' => 'Unauthorized.'], 401);
            }
        }

        return $next($request);
    }
}
