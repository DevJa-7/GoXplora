<?php

namespace App\Http\Middleware;

use Closure;

class AdminPanelAccess
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
        if (!is('admin')) {
            return redirect('/');
        }
        
        return $next($request);
    }
}
