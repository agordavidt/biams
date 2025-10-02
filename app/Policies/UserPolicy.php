<?php


namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $authUser): bool
    {
        // Super Admin can see all users
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }

        // State Admin can see all users in their department/agency
        if ($authUser->hasRole('State Admin')) {
            return $authUser->administrative_id !== null;
        }

        // LGA Admin can see users in their LGA
        if ($authUser->hasRole('LGA Admin')) {
            return $authUser->administrative_id !== null;
        }

        return false;
    }

    /**
     * Determine if the user can view a specific user.
     */
    public function view(User $authUser, User $targetUser): bool
    {
        // Super Admin can view anyone
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }

        // State Admin can view users in their department/agency
        if ($authUser->hasRole('State Admin')) {
            return $this->isSameAdministrativeUnit($authUser, $targetUser);
        }

        // LGA Admin can view users in their LGA
        if ($authUser->hasRole('LGA Admin')) {
            return $this->isSameAdministrativeUnit($authUser, $targetUser);
        }

        // Users can view their own profile
        return $authUser->id === $targetUser->id;
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $authUser): bool
    {
        // Super Admin can create any user
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }

        // LGA Admin can create Enrollment Agents in their LGA
        if ($authUser->hasRole('LGA Admin') && $authUser->hasPermissionTo('manage_lga_agents')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can update a specific user.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        // Super Admin can update anyone
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }

        // LGA Admin can update Enrollment Agents in their LGA
        if ($authUser->hasRole('LGA Admin') && $authUser->hasPermissionTo('manage_lga_agents')) {
            return $this->isSameAdministrativeUnit($authUser, $targetUser) 
                && $targetUser->hasRole('Enrollment Agent');
        }

        return false;
    }

    /**
     * Determine if the user can delete a specific user.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        // Cannot delete yourself
        if ($authUser->id === $targetUser->id) {
            return false;
        }

        // Super Admin can delete any user except other Super Admins
        if ($authUser->hasRole('Super Admin')) {
            return !$targetUser->hasRole('Super Admin');
        }

        // LGA Admin can delete Enrollment Agents in their LGA
        if ($authUser->hasRole('LGA Admin') && $authUser->hasPermissionTo('manage_lga_agents')) {
            return $this->isSameAdministrativeUnit($authUser, $targetUser)
                && $targetUser->hasRole('Enrollment Agent');
        }

        return false;
    }

    /**
     * Determine if the user can restore a deleted user.
     */
    public function restore(User $authUser, User $targetUser): bool
    {
        return $this->delete($authUser, $targetUser);
    }

    /**
     * Determine if the user can permanently delete a user.
     */
    public function forceDelete(User $authUser, User $targetUser): bool
    {
        // Only Super Admin can permanently delete
        return $authUser->hasRole('Super Admin') && !$targetUser->hasRole('Super Admin');
    }

    /**
     * Determine if the user can change another user's role.
     */
    public function changeRole(User $authUser, User $targetUser): bool
    {
        // Only Super Admin can change roles
        if ($authUser->hasRole('Super Admin')) {
            // Cannot change another Super Admin's role
            return !$targetUser->hasRole('Super Admin') || $authUser->id === $targetUser->id;
        }

        return false;
    }

    /**
     * Determine if the user can change another user's status.
     */
    public function changeStatus(User $authUser, User $targetUser): bool
    {
        // Super Admin can change any status
        if ($authUser->hasRole('Super Admin')) {
            return true;
        }

        // LGA Admin can change status of Enrollment Agents in their LGA
        if ($authUser->hasRole('LGA Admin') && $authUser->hasPermissionTo('manage_lga_agents')) {
            return $this->isSameAdministrativeUnit($authUser, $targetUser)
                && $targetUser->hasRole('Enrollment Agent');
        }

        return false;
    }

    /**
     * Check if two users belong to the same administrative unit.
     */
    protected function isSameAdministrativeUnit(User $user1, User $user2): bool
    {
        return $user1->administrative_type === $user2->administrative_type
            && $user1->administrative_id === $user2->administrative_id;
    }

    /**
     * Scope query to users the authenticated user can access.
     */
    public static function scopeAccessible($query, User $authUser)
    {
        // Super Admin sees everyone
        if ($authUser->hasRole('Super Admin')) {
            return $query;
        }

        // LGA Admin sees only users in their LGA
        if ($authUser->hasRole('LGA Admin')) {
            return $query->where('administrative_type', $authUser->administrative_type)
                        ->where('administrative_id', $authUser->administrative_id);
        }

        // State Admin sees users in their department/agency
        if ($authUser->hasRole('State Admin')) {
            return $query->where('administrative_type', $authUser->administrative_type)
                        ->where('administrative_id', $authUser->administrative_id);
        }

        // Default: only see yourself
        return $query->where('id', $authUser->id);
    }
}