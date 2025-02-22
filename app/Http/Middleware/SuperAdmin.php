<?php




namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user is a super admin
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return $next($request);
        }

        // Redirect or deny access
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}