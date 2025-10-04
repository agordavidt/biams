<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
| Register broadcast channel authorization for private channels
| File: routes/channels.php
*/


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// Individual Chat Channel - Both farmer and assigned admins can access
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);
    
    if (!$chat) {
        return false;
    }
    
    // Farmer can access their own chat
    if ($user->farmerProfile && $user->farmerProfile->id === $chat->farmer_id) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'farmer'];
    }
    
    // State-level admins can access all chats
    if ($user->hasAnyRole(['Super Admin', 'Governor', 'State Admin'])) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'admin'];
    }
    
    // LGA-level admins can access chats in their LGA
    if ($user->hasAnyRole(['LGA Admin', 'Enrollment Agent'])) {
        $userLga = $user->administrativeUnit;
        if ($userLga && $userLga->id === $chat->lga_id) {
            return ['id' => $user->id, 'name' => $user->name, 'type' => 'admin'];
        }
    }
    
    return false;
});

// LGA Support Channel - For notifying LGA admins of new chats
Broadcast::channel('lga-support.{lgaId}', function ($user, $lgaId) {
    // State-level admins can subscribe to all LGA channels
    if ($user->hasAnyRole(['Super Admin', 'Governor', 'State Admin'])) {
        return ['id' => $user->id, 'name' => $user->name];
    }
    
    // LGA-level admins can only subscribe to their own LGA channel
    if ($user->hasAnyRole(['LGA Admin', 'Enrollment Agent'])) {
        $userLga = $user->administrativeUnit;
        if ($userLga && $userLga->id == $lgaId) {
            return ['id' => $user->id, 'name' => $user->name];
        }
    }
    
    return false;
});

