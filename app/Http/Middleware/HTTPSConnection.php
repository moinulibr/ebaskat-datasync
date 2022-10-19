<?php

namespace App\Http\Middleware;
use Closure;

class HTTPSConnection {

    public function handle($request, Closure $next)
    {
        if(env('APP_SECURE',0) == 1) {
            if (!$request->secure()) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        return $next($request);
    }

}
