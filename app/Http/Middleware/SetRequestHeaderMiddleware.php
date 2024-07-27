<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class SetRequestHeaderMiddleware
{

    public function handle($request, Closure $next, ...$guards)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}

