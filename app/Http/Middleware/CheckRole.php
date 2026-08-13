<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
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
        if (! $request->user() || ! in_array($request->user()->getRole(), $roles, true)) {
            abort(401, 'No podes acceder a la página.');
        }

        return $next($request);
    }
}
