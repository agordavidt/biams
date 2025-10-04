<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\AnalyticsPolicy; // <-- Import AnalyticsPolicy
use Illuminate\Support\Facades\Gate; // <-- Import Gate facade
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        'analytics' => AnalyticsPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view_analytics', [AnalyticsPolicy::class, 'view_analytics']);
        Gate::define('export_analytics', [AnalyticsPolicy::class, 'export_analytics']);
    }
}
