<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponder;
use Closure;
use Illuminate\Http\Request;

class WhiteListIpAddressessMiddleware​
{
    protected $whitelistIps = [
        '52.31.139.75',
        '52.49.173.169',
        '52.214.14.220',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request->whitelistIps);
        if (!array_key_exists($request->getClientIp(), $this->whitelistIps)) {
            return ApiResponder::failureResponse("You are restricted to access the site.", 403);
        }

        return $next($request);
    }
}
