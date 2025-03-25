<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized access. Please log in.'], 401);
        }

        // If `role` is a column in users table
        $userRole = is_string($user->role) ? $user->role : ($user->role->name ?? null);

        if (!$userRole || !in_array($userRole, $roles)) {
            return response()->json(['error' => 'Forbidden. You do not have permission.'], 403);
        }

        return $next($request);
    }
}
