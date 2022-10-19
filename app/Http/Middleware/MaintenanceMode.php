<?php

namespace App\Http\Middleware;
use Closure;

class MaintenanceMode
{
    public function handle($request, Closure $next)
    {
        if(env('APP_MAINTAIN',false) == true) {
            return redirect()->route('front-maintenance');
        }
        return $next($request);
    }
}
