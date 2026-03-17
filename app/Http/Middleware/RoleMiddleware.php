<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        $userPosition = auth()->user()->position ?? '';

        if (!in_array($userPosition, $roles)) {
            abort(403, 'You are not authorized to access this page.');
        }

        return $next($request);
    }
}