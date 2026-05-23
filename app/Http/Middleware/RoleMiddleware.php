<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }
        if (empty($roles)) {
            return $next($request);
        }
        if (!in_array($user->role, $roles)) {
            abort(403);
        }
        return $next($request);
    }
}
