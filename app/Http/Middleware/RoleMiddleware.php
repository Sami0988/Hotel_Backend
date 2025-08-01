<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next, ...$roles)
{
    $user = Auth::user();

    if (!in_array($user->role, $roles)) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    return $next($request);
}

}
