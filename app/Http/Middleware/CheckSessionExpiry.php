<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Checks if user session is valid and handles expired sessions gracefully
 */
class CheckSessionExpiry
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for guest routes
        if (!Auth::check()) {
            return $next($request);
        }

        // Check if session has the last activity timestamp
        $lastActivity = $request->session()->get('last_activity_time');
        
        if ($lastActivity) {
            $sessionLifetime = config('session.lifetime') * 60; // Convert minutes to seconds
            $currentTime = time();
            
            // If session has expired
            if (($currentTime - $lastActivity) > $sessionLifetime) {
                // Clear session and auth
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirect to login with informative message
                return redirect()->route('login')
                    ->with('status', 'Your session has expired. Please login again.')
                    ->with('session_expired', true);
            }
        }
        
        // Update last activity timestamp
        $request->session()->put('last_activity_time', time());
        
        return $next($request);
    }
}