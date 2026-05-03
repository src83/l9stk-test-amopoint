<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Illuminate\Http\Request;

class CheckHost
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $domain = Config::get('constants.DESKTOP_DOMAIN');
        $mobileDomain = Config::get('constants.MOBILE_DOMAIN');

        $allowedHosts = [$domain, $mobileDomain];

        if (! in_array($request->getHost(), $allowedHosts, true)) {
            abort(403);
        }

        return $next($request);
    }
}
