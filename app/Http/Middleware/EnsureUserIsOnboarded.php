<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsOnboarded
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is onboarded
        if ($user->status !== 'onboarded') {
            return redirect()->route('home')->with('error', 'You must be onboarded to access this page.');
        }

        return $next($request);
    }
}