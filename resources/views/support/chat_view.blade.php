@extends($scope['type'] === 'farmer' ? 'layouts.farmer' : ($scope['type'] === 'lga' ? 'layouts.lga_admin' : 'layouts.admin'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">
                <a href="{{ route($scope['type'] === 'farmer' ? 'farmer.support.index' : ($scope['type'] === 'lga' ? 'lga_admin.support.index' : 'admin.support.index')) }}" class="text-dark">
                    <i class="ri-arrow-left-line"></i>
                </a>
                {{ $chat->subject ?: 'Support Chat' }}
            </h4>
            
            <div class="d-flex gap-2">
                <span class="badge bg-{{ $chat->status === 'open' ? 'warning' : ($chat->status === 'resolved' ? 'success' : 'primary') }} fs-6">
                    {{ ucfirst(str_replace('_', ' ', $chat->status)) }}
                </span>
                
                @if($scope['type'] !== 'farmer' && $chat->status !== 'resolved')
                    <!-- @if(!$chat->assigned_admin_id || $chat->assigned_admin_id !== auth()->id())
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="assignChat({{ $chat->id }})">
                           Assign to Me
                        </button>
                    @endif -->
                    
                    <button type="button" class="btn btn-sm btn-success" onclick="resolveChat({{ $chat->id }})">
                         Mark Resolved
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chat Info Sidebar -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Chat Information</h5>
                
                @if($scope['type'] !== 'farmer')
                    <div class="mb-3">
                        <label class="text-muted small">Farmer</label>
                        <p class="mb-0"><strong>{{ $chat->farmer->full_name }}</strong></p>
                        <p class="mb-0 small">{{ $chat->farmer->phone_primary }}</p>
                        <p class="mb-0 small text-muted">{{ $chat->farmer->email }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Location</label>
                        <p class="mb-0">{{ $chat->lga->name }}</p>
                        <p class="mb-0 small text-muted">{{ $chat->farmer->ward }}</p>
                    </div>
                @endif
                
                <div class="mb-3">
                    <label class="text-muted small">Priority</label>
                    <p class="mb-0">
                        <span class="badge bg-{{ $chat->priority === 'urgent' ? 'danger' : ($chat->priority === 'high' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($chat->priority) }}
                        </span>
                    </p>
                </div>
                
                @if($chat->assignedAdmin)
                    <div class="mb-3">
                        <label class="text-muted small">Assigned To</label>
                        <p class="mb-0">{{ $chat->assignedAdmin->name }}</p>
                        <p class="mb-0 small text-muted">{{ $chat->assigned_at?->diffForHumans() }}</p>
                    </div>
                @endif
                
                <div class="mb-3">
                    <label class="text-muted small">Created</label>
                    <p class="mb-0">{{ $chat->created_at->format('M d, Y H:i') }}</p>
                </div>
                
                @if($chat->resolved_at)
                    <div class="mb-3">
                        <label class="text-muted small">Resolved</label>
                        <p class="mb-0">{{ $chat->resolved_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Chat Messages -->
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body" style="height: 600px; display: flex; flex-direction: column;">
                <!-- Messages Container - FIXED: Standard chat order (oldest to newest, scroll to bottom) -->
                <div id="messagesContainer" style="flex: 1; overflow-y: auto; padding: 1rem;">
                    <div id="messagesList">
                        @forelse($chat->messages as $message)
                            <div class="message-item mb-3 {{ $message->sender_type === 'farmer' ? 'text-start' : 'text-end' }}" data-message-id="{{ $message->id }}">
                                <div class="d-inline-block" style="max-width: 70%;">
                                    <div class="card mb-1 {{ $message->sender_type === 'farmer' ? 'bg-light' : 'bg-primary text-white' }}">
                                        <div class="card-body p-2">
                                            <p class="mb-1" style="white-space: pre-wrap;">{{ $message->body }}</p>
                                            <small class="{{ $message->sender_type === 'farmer' ? 'text-muted' : 'text-white-50' }}">
                                                {{ $message->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <small class="{{ $message->sender_type === 'farmer' ? 'text-muted' : 'text-end' }}">
                                        {{ $message->sender->name }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5" id="noMessagesPlaceholder">
                                <i class="ri-message-3-line display-4"></i>
                                <p class="mt-3">No messages yet. Start the conversation!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Message Input -->
                @if($chat->status !== 'closed')
                    <div class="border-top pt-3">
                        <form id="messageForm" onsubmit="sendMessage(event)">
                            @csrf
                            <div class="input-group">
                                <textarea 
                                    id="messageInput" 
                                    class="form-control" 
                                    rows="2" 
                                    placeholder="Type your message..."
                                    required
                                    onkeydown="handleKeyDown(event)"
                                ></textarea>
                                <button type="submit" class="btn btn-primary" id="sendBtn">
                                    <i class="ri-send-plane-fill"></i> Send
                                </button>
                            </div>
                            <small class="text-muted">Press Enter to send, Shift+Enter for new line</small>
                        </form>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        This chat has been closed and no longer accepts messages.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const chatId = {{ $chat->id }};
const routePrefix = '{{ $scope['type'] === 'farmer' ? 'farmer' : ($scope['type'] === 'lga' ? 'lga_admin' : 'admin') }}';
const currentUserId = {{ auth()->id() }};
const csrfToken = '{{ csrf_token() }}';
let isCurrentUserFarmer = {{ $scope['type'] === 'farmer' ? 'true' : 'false' }};
let isSending = false;

// Scroll to bottom on page load
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
});

// Scroll to bottom function
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
}

// Handle Enter key to send (Shift+Enter for new line)
function handleKeyDown(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage(event);
    }
}

// Send Message - FIXED: Allow multiple messages without waiting for response
async function sendMessage(event) {
    event.preventDefault();
    
    // Prevent duplicate sends while one is in progress
    if (isSending) {
        return;
    }
    
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    const sendBtn = document.getElementById('sendBtn');
    const originalBtnText = sendBtn.innerHTML;
    
    // Lock the send operation
    isSending = true;
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Sending...';
    
    // Store message and clear input immediately for better UX
    const messageToSend = message;
    messageInput.value = '';
    
    try {
        const response = await fetch(`/${routePrefix}/support/${chatId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: messageToSend })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Remove "no messages" placeholder if exists
            const placeholder = document.getElementById('noMessagesPlaceholder');
            if (placeholder) {
                placeholder.remove();
            }
            
            appendMessage(data.message);
            scrollToBottom();
        } else {
            // If failed, restore the message to input
            messageInput.value = messageToSend;
            alert('Failed to send message. Please try again.');
        }
    } catch (error) {
        console.error('Error sending message:', error);
        // Restore the message to input
        messageInput.value = messageToSend;
        alert('Failed to send message. Please check your connection and try again.');
    } finally {
        // Unlock send operation
        isSending = false;
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalBtnText;
        messageInput.focus();
    }
}

// Append new message to chat - FIXED: Append to bottom (standard chat behavior)
function appendMessage(message) {
    const messagesList = document.getElementById('messagesList');
    const isFarmer = message.sender_type === 'farmer';
    const isCurrentUser = message.sender_id === currentUserId;
    
    // Check if message already exists
    if (document.querySelector(`[data-message-id="${message.id}"]`)) {
        return;
    }
    
    const messageHtml = `
        <div class="message-item mb-3 ${isFarmer ? 'text-start' : 'text-end'}" data-message-id="${message.id}">
            <div class="d-inline-block" style="max-width: 70%;">
                <div class="card mb-1 ${isFarmer ? 'bg-light' : 'bg-primary text-white'}">
                    <div class="card-body p-2">
                        <p class="mb-1" style="white-space: pre-wrap;">${escapeHtml(message.body)}</p>
                        <small class="${isFarmer ? 'text-muted' : 'text-white-50'}">
                            ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                        </small>
                    </div>
                </div>
                <small class="${isFarmer ? 'text-muted' : 'text-end'}">
                    ${escapeHtml(message.sender.name)}
                </small>
            </div>
        </div>
    `;
    
    // Append to the END (bottom) of the messages list
    messagesList.insertAdjacentHTML('beforeend', messageHtml);
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Assign Chat
async function assignChat(chatId) {
    if (!confirm('Assign this chat to yourself?')) return;
    
    try {
        const response = await fetch(`/${routePrefix}/support/${chatId}/assign`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to assign chat. Please try again.');
        }
    } catch (error) {
        console.error('Error assigning chat:', error);
        alert('Failed to assign chat. Please try again.');
    }
}

// Resolve Chat
async function resolveChat(chatId) {
    if (!confirm('Mark this chat as resolved?')) return;
    
    try {
        const response = await fetch(`/${routePrefix}/support/${chatId}/resolve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to resolve chat. Please try again.');
        }
    } catch (error) {
        console.error('Error resolving chat:', error);
        alert('Failed to resolve chat. Please try again.');
    }
}

// Poll for new messages every 5 seconds (simple fallback without WebSockets)
let lastMessageId = {{ $chat->messages->last()->id ?? 0 }};

setInterval(async () => {
    if (document.hidden || isSending) return;
    
    try {
        const response = await fetch(`/${routePrefix}/support/${chatId}/poll?last_message_id=${lastMessageId}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.new_messages && data.new_messages.length > 0) {
                data.new_messages.forEach(message => {
                    appendMessage(message);
                    lastMessageId = Math.max(lastMessageId, message.id);
                });
                scrollToBottom();
            }
        }
    } catch (error) {
        console.error('Error polling messages:', error);
    }
}, 5000);
</script>
@endpush