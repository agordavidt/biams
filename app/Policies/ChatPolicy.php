<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    /**
     * Determine if user can view the chat
     */
    public function view(User $user, Chat $chat): bool
    {
        // Farmers can only view their own chats
        if ($user->hasRole('User')) {
            return $user->farmerProfile && $user->farmerProfile->id === $chat->farmer_id;
        }

        // State-level admins can view all chats
        if ($user->hasAnyRole(['Super Admin', 'Governor', 'State Admin'])) {
            return true;
        }

        // LGA-level admins can only view chats in their LGA
        if ($user->hasAnyRole(['LGA Admin', 'Enrollment Agent'])) {
            $userLga = $user->administrativeUnit;
            return $userLga && $userLga->id === $chat->lga_id;
        }

        return false;
    }

    /**
     * Determine if user can send messages in the chat
     */
    public function sendMessage(User $user, Chat $chat): bool
    {
        // Must be able to view the chat first
        if (!$this->view($user, $chat)) {
            return false;
        }

        // Cannot send messages to closed chats
        if ($chat->status === 'closed') {
            return false;
        }

        return true;
    }

    /**
     * Determine if user can assign the chat
     */
    public function assign(User $user, Chat $chat): bool
    {
        // Only admins can assign
        if ($user->hasRole('User')) {
            return false;
        }

        // Must be in correct LGA or be state-level
        return $this->view($user, $chat);
    }

    /**
     * Determine if user can resolve the chat
     */
    public function resolve(User $user, Chat $chat): bool
    {
        // Only admins can resolve
        if ($user->hasRole('User')) {
            return false;
        }

        // Must be in correct LGA or be state-level
        return $this->view($user, $chat);
    }
}