<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Farmer;
use App\Models\LGA;
use App\Events\MessageSent;
use App\Events\ChatCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display chat list based on user role
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Determine scope based on role
        $scope = $this->getUserScope($user);
        
        // Build query with role-based filtering
        $chatsQuery = Chat::with(['farmer.user', 'assignedAdmin', 'lga', 'latestMessage'])
            ->select('chats.*')
            ->selectSub(function ($query) use ($user) {
                $query->from('messages')
                    ->whereColumn('messages.chat_id', 'chats.id')
                    ->where('sender_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->selectRaw('COUNT(*)');
            }, 'unread_count');

        // CRITICAL FIX: For farmers, filter by their farmer record
        if ($scope['type'] === 'farmer') {
            $farmer = Farmer::where('user_id', $user->id)->first();
            
            if (!$farmer) {
                abort(403, 'No farmer profile found for this user');
            }
            
            $chatsQuery->where('farmer_id', $farmer->id);
        }
        
        // Apply geographic filter for LGA-level users
        if ($scope['type'] === 'lga') {
            $chatsQuery->where('lga_id', $scope['lga_id']);
        }

        // Apply status filter
        $status = $request->get('status', 'active');
        if ($status === 'active') {
            $chatsQuery->active();
        } elseif ($status === 'open') {
            $chatsQuery->open();
        } elseif (in_array($status, ['resolved', 'closed'])) {
            $chatsQuery->where('status', $status);
        }

        // Apply LGA filter for state-level users
        if ($scope['type'] === 'state' && $request->filled('lga_id')) {
            $chatsQuery->where('lga_id', $request->lga_id);
        }

        $chats = $chatsQuery->orderBy('last_message_at', 'desc')
                           ->paginate(20);

        // Get LGA list for state-level users
        $lgas = $scope['type'] === 'state' ? LGA::orderBy('name')->get() : null;

        return view('support.chat_list', [
            'chats' => $chats,
            'scope' => $scope,
            'lgas' => $lgas,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Show individual chat
     */
    public function show(Chat $chat)
    {
        $user = auth()->user();
        
        // Authorization check
        $this->authorize('view', $chat);
        
        // Load chat with messages
        $chat->load(['farmer.user', 'assignedAdmin', 'lga', 'messages.sender']);
        
        // Mark messages as read for current user
        $chat->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return view('support.chat_view', [
            'chat' => $chat,
            'scope' => $this->getUserScope($user),
        ]);
    }

    /**
     * Create new chat (Farmer only)
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // CRITICAL FIX: Find the farmer record by user_id
        $farmer = Farmer::where('user_id', $user->id)->first();
        
        if (!$farmer) {
            return back()->withErrors(['error' => 'No farmer profile found. Please contact support.']);
        }

        $validated = $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'priority' => 'sometimes|in:low,normal,high,urgent',
        ]);

        $chat = DB::transaction(function () use ($farmer, $validated, $user) {
            // Create chat
            $chat = Chat::create([
                'farmer_id' => $farmer->id,
                'lga_id' => $farmer->lga_id,
                'subject' => $validated['subject'] ?? 'Support Request',
                'status' => 'open',
                'priority' => $validated['priority'] ?? 'normal',
                'last_message_at' => now(),
            ]);

            // Create first message
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $user->id,
                'body' => $validated['message'],
                'sender_type' => 'farmer',
            ]);

            return $chat->load(['farmer.user', 'lga', 'latestMessage']);
        });

        // Broadcast to LGA admins (optional - if you have broadcasting set up)
        // broadcast(new ChatCreated($chat))->toOthers();

        return redirect()->route('farmer.support.show', $chat)
            ->with('success', 'Support chat created successfully');
    }

    /**
     * Send message in existing chat
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        $this->authorize('sendMessage', $chat);

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $user = auth()->user();
        $senderType = $user->hasRole('User') ? 'farmer' : 'admin';

        $message = DB::transaction(function () use ($chat, $validated, $user, $senderType) {
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $user->id,
                'body' => $validated['message'],
                'sender_type' => $senderType,
            ]);

            // Update chat timestamp and status
            $chat->update([
                'last_message_at' => now(),
                'status' => $senderType === 'admin' ? 'pending_farmer' : 'in_progress',
            ]);

            // Auto-assign if admin sends first message
            if ($senderType === 'admin' && !$chat->assigned_admin_id) {
                $chat->markAsAssigned($user);
            }

            return $message->load('sender');
        });

        // Broadcast message (optional)
        // broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * Assign chat to admin
     */
    public function assign(Chat $chat)
    {
        $this->authorize('assign', $chat);
        
        $user = auth()->user();
        $chat->markAsAssigned($user);

        return response()->json([
            'success' => true,
            'message' => 'Chat assigned successfully',
        ]);
    }

    /**
     * Resolve chat
     */
    public function resolve(Chat $chat)
    {
        $this->authorize('resolve', $chat);
        
        $chat->markAsResolved();

        return response()->json([
            'success' => true,
            'message' => 'Chat marked as resolved',
        ]);
    }

    /**
     * Determine user's scope for chat access
     */
    private function getUserScope($user): array
    {
        // Farmer scope
        if ($user->hasRole('User')) {
            return [
                'type' => 'farmer',
                'role' => 'Farmer',
            ];
        }

        // State-level scope (Super Admin, Governor, State Admin)
        if ($user->hasAnyRole(['Super Admin', 'Governor', 'State Admin'])) {
            return [
                'type' => 'state',
                'role' => $user->roles->first()->name,
            ];
        }

        // LGA-level scope (LGA Admin, Enrollment Agent)
        if ($user->hasAnyRole(['LGA Admin', 'Enrollment Agent'])) {
            $lga = $user->administrativeUnit;
            
            return [
                'type' => 'lga',
                'role' => $user->roles->first()->name,
                'lga_id' => $lga?->id,
                'lga_name' => $lga?->name,
            ];
        }

        abort(403, 'Unauthorized role for chat access');
    }
}