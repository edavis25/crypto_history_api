<?php

namespace App\Http\Middleware;

use Closure;

class ForceJsonRequest
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
        // force fill the request headers to accept only JSON responses.
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
