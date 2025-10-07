<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chat;
use App\Models\Farmer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the chat
     */
    public function view(User $user, Chat $chat): bool
    {
        // Super Admin and State Admin can view all chats
        if ($user->hasAnyRole(['Super Admin', 'State Admin', 'Governor'])) {
            return true;
        }

        // LGA Admin and Enrollment Agent can view chats in their LGA
        if ($user->hasAnyRole(['LGA Admin', 'Enrollment Agent'])) {
            $userLga = $user->administrativeUnit;
            return $userLga && $chat->lga_id === $userLga->id;
        }

        // Farmers can only view their own chats
        if ($user->hasRole('User')) {
            // CRITICAL FIX: Check if the chat's farmer has this user_id
            // This handles both activated and non-activated farmers
            return $chat->farmer && $chat->farmer->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can send messages in the chat
     */
    public function sendMessage(User $user, Chat $chat): bool
    {
        // Use same logic as view - if you can view it, you can send messages
        return $this->view($user, $chat);
    }

    /**
     * Determine if the user can assign the chat
     */
    public function assign(User $user, Chat $chat): bool
    {
        // Only admins can assign chats
        if ($user->hasAnyRole(['Super Admin', 'State Admin'])) {
            return true;
        }

        // LGA Admin can assign chats in their LGA
        if ($user->hasRole('LGA Admin')) {
            $userLga = $user->administrativeUnit;
            return $userLga && $chat->lga_id === $userLga->id;
        }

        return false;
    }

    /**
     * Determine if the user can resolve the chat
     */
    public function resolve(User $user, Chat $chat): bool
    {
        // Only admins can resolve chats
        if ($user->hasAnyRole(['Super Admin', 'State Admin'])) {
            return true;
        }

        // LGA Admin can resolve chats in their LGA
        if ($user->hasRole('LGA Admin')) {
            $userLga = $user->administrativeUnit;
            return $userLga && $chat->lga_id === $userLga->id;
        }

        // Assigned admin can resolve
        if ($chat->assigned_admin_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create chats
     */
    public function create(User $user): bool
    {
        // CRITICAL FIX: Only users with role 'User' who have a linked farmer profile can create chats
        // Check if a Farmer record exists with this user_id
        return $user->hasRole('User') && Farmer::where('user_id', $user->id)->exists();
    }
}