<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $userRole = $request->user()->role;
        if (!$userRole || !in_array($userRole, $roles)) {
            abort('403', 'Akses dilarang');
        }
        return $next($request);
    }
}
