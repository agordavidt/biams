<?php



namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsIncomplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user has a profile
        if ($user->profile) {
            return redirect()->route('home')->with('info', 'Your profile is already complete.');
        }

        return $next($request);
    }
}