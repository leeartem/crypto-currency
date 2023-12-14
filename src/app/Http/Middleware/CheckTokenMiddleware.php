<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token || $token !== config('auth.token')) {
            return response()->json(
                [
                    'status' => 'error',
                    'code'   => 403,
                    'error'  => 'Invalid Token',
                ],
                403
            );
        }

        return $next($request);
    }
}
