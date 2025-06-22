<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('api')->check() && Auth::guard('api')->user()->status == 'active') {
            return $next($request);
        }

        return abort(403, 'Access denied. You do not have permission to access this resource.');
    }
}

