<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application (for authenticated users).
     */
    public const HOME = '/dashboard'; // ← Change this to a dashboard route
    
    /**
     * Default route for unauthenticated users (public landing page).
     */
    public const GUEST_HOME = '/';

    /**
     * Register route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Determine redirect path after authentication.
     */
    public static function redirectToHome($user): string
    {
        if (! $user) {
            return self::GUEST_HOME;
        }

        if ($user->hasRole('Super Admin')) return route('super_admin.dashboard');
        if ($user->hasRole('Governor')) return route('governor.dashboard');
        if ($user->hasRole('Commissioner')) return route('commissioner.dashboard');
        if ($user->hasRole('State Admin')) return route('admin.dashboard');
        if ($user->hasRole('LGA Admin')) return route('lga_admin.dashboard');
        if ($user->hasRole('Enrollment Agent')) return route('enrollment.dashboard');
        if ($user->hasRole('Vendor Manager')) return route('vendor.dashboard');
        if ($user->hasRole('Distribution Agent')) return route('vendor.distribution.dashboard');
        if ($user->hasRole('User')) return route('farmer.dashboard');

        return self::GUEST_HOME;
    }
}
