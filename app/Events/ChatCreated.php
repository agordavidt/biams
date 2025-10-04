<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class ChatCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     * Broadcast to LGA-specific channel so admins in that LGA are notified
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('lga-support.' . $this->chat->lga_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'chat.created';
    }

    /**
     * Data to broadcast
     */
    public function broadcastWith(): array
    {
        return [
            'chat' => [
                'id' => $this->chat->id,
                'subject' => $this->chat->subject,
                'priority' => $this->chat->priority,
                'farmer_name' => $this->chat->farmer->full_name,
                'created_at' => $this->chat->created_at->toISOString(),
            ],
        ];
    }
}