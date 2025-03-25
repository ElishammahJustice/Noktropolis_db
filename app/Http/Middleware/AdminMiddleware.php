<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->user() || optional($request->user()->role)->name !== 'admin') {
            return response()->json(['error' => 'Access denied. Admins only.'], 403);
        }

        return $next($request);
    }
}
