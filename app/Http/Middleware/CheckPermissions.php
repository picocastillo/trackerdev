<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->id)
            if (!$request->user()->canSeeTask($request->id)){
                abort(401,"No podes acceder a la página.");
            }
        
        return $next($request);
    }
}
