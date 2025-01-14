<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   // app/Http/Middleware/EnsureUserIsUser.php
public function handle($request, Closure $next)
{
    if (auth()->user()->role !== 'user') {
        abort(403, 'Unauthorized action.');
    }
    return $next($request);
}
}
