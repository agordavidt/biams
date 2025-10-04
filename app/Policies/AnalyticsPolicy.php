<?php


namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnalyticsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view analytics
     */
    public function view_analytics(User $user): bool
    {
        return $user->hasAnyRole([
            'Super Admin',
            'Governor',
            'State Admin',
            'LGA Admin',
            'Enrollment Agent'
        ]);
    }

    /**
     * Determine if user can export analytics
     */
    public function export_analytics(User $user): bool
    {
        return $user->hasAnyRole([
            'Super Admin',
            'Governor',
            'State Admin',
            'LGA Admin'
        ]);
    }

    /**
     * Determine if user can view state-wide data
     */
    public function viewStatewide(User $user): bool
    {
        return $user->hasAnyRole([
            'Super Admin',
            'Governor',
            'State Admin'
        ]);
    }

    /**
     * Determine if user can view specific LGA data
     */
    public function viewLGA(User $user, ?int $lgaId): bool
    {
        // State-level can view any LGA
        if ($this->viewStatewide($user)) {
            return true;
        }

        // LGA-level can only view their own
        if ($user->hasAnyRole(['LGA Admin', 'Enrollment Agent'])) {
            return $user->administrative_id === $lgaId;
        }

        return false;
    }
}

