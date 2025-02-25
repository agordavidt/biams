<?php

namespace App\Http\Controllers;

use App\Models\Market\MarketplaceListing;
use App\Models\Market\MarketplaceMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceMessageController extends Controller
{
    /**
     * Show messages for a specific listing
     */
    public function showConversation(MarketplaceListing $listing, $partner_id = null)
    {
        $user = Auth::user();
        
        // Check if user is either the listing owner or has sent a message to this listing
        $isOwner = $listing->user_id === $user->id;
        
        if (!$isOwner) {
            // If not the owner, check if user has sent a message
            $hasSentMessage = MarketplaceMessage::where('listing_id', $listing->id)
                ->where('sender_id', $user->id)
                ->exists();
            
            if (!$hasSentMessage) {
                return redirect()->route('marketplace.show', $listing)
                    ->with('error', 'You cannot access this conversation.');
            }
        }
        
        // Get all unique users that have messaged about this listing
        if ($isOwner) {
            // For the owner, show all senders
            $conversationPartners = MarketplaceMessage::where('listing_id', $listing->id)
                ->where('receiver_id', $user->id)
                ->select('sender_id')
                ->distinct()
                ->with('sender')
                ->get()
                ->pluck('sender');
        } else {
            // For buyers, only show their conversation with the owner
            $conversationPartners = collect([$listing->user]);
            $partner_id = $listing->user_id; // Force partner to be the listing owner
        }
        
        // Get messages for the selected conversation
        $messages = collect([]);
        if ($partner_id) {
            $messages = MarketplaceMessage::where('listing_id', $listing->id)
                ->where(function($query) use ($user, $partner_id) {
                    $query->where(function($q) use ($user, $partner_id) {
                        $q->where('sender_id', $user->id)
                          ->where('receiver_id', $partner_id);
                    })->orWhere(function($q) use ($user, $partner_id) {
                        $q->where('sender_id', $partner_id)
                          ->where('receiver_id', $user->id);
                    });
                })
                ->orderBy('created_at')
                ->get();
                
            // Mark messages as read
            MarketplaceMessage::where('listing_id', $listing->id)
                ->where('receiver_id', $user->id)
                ->where('sender_id', $partner_id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
        
        return view('marketplace.messages', compact('listing', 'conversationPartners', 'messages', 'partner_id'));
    }
    
    /**
     * Send a new message
     */
    public function sendMessage(Request $request, MarketplaceListing $listing)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|exists:users,id',
        ]);
        
        $user = Auth::user();
        $receiver_id = $request->receiver_id;
        
        // Verify the receiver is either the listing owner or has previously messaged about this listing
        $isOwner = $listing->user_id === $receiver_id;
        $canMessage = $isOwner || 
            MarketplaceMessage::where('listing_id', $listing->id)
                ->where(function($query) use ($user, $receiver_id) {
                    $query->where('sender_id', $user->id)
                          ->where('receiver_id', $receiver_id);
                })->orWhere(function($query) use ($user, $receiver_id) {
                    $query->where('sender_id', $receiver_id)
                          ->where('receiver_id', $user->id);
                })->exists();
        
        // Alternative check if no messages exist yet:
        // Allow new buyer to contact seller (owner)
        if (!$canMessage && $listing->user_id === $receiver_id) {
            $canMessage = true;
        }
        
        if (!$canMessage) {
            return back()->with('error', 'You cannot send a message to this user.');
        }
        
        $message = new MarketplaceMessage();
        $message->listing_id = $listing->id;
        $message->sender_id = $user->id;
        $message->receiver_id = $receiver_id;
        $message->message = $request->message;
        $message->save();
        
        return back()->with('success', 'Message sent successfully.');
    }
    
    /**
     * Show all conversations for the current user across all listings
     */
    public function inbox()
    {
        $user = Auth::user();
        
        // Get all listings where the user is either the owner or has messages
        $listingsAsOwner = $user->marketplaceListings()
            ->whereHas('messages')
            ->with(['messages' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->get();
            
        $listingsAsParticipant = MarketplaceListing::whereHas('messages', function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
        })
        ->where('user_id', '!=', $user->id)
        ->with(['messages' => function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id)
                  ->orderBy('created_at', 'desc');
        }])
        ->get();
        
        // Count unread messages
        $unreadCount = MarketplaceMessage::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        return view('marketplace.inbox', compact('listingsAsOwner', 'listingsAsParticipant', 'unreadCount'));
    }
}