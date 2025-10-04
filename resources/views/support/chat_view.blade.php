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
                    @if(!$chat->assigned_admin_id || $chat->assigned_admin_id !== auth()->id())
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="assignChat({{ $chat->id }})">
                            <i class="ri-user-add-line"></i> Assign to Me
                        </button>
                    @endif
                    
                    <button type="button" class="btn btn-sm btn-success" onclick="resolveChat({{ $chat->id }})">
                        <i class="ri-check-line"></i> Mark Resolved
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
                <!-- Messages Container -->
                <div id="messagesContainer" style="flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column-reverse;">
                    <div id="messagesList">
                        @foreach($chat->messages->reverse() as $message)
                            <div class="message-item mb-3 {{ $message->sender_type === 'farmer' ? 'text-start' : 'text-end' }}">
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
                        @endforeach
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
                                ></textarea>
                                <button type="submit" class="btn btn-primary" id="sendBtn">
                                    <i class="ri-send-plane-fill"></i> Send
                                </button>
                            </div>
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

// Send Message
async function sendMessage(event) {
    event.preventDefault();
    
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    
    try {
        const response = await fetch(`/${routePrefix}/support/${chatId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        
        if (data.success) {
            messageInput.value = '';
            appendMessage(data.message);
        }
    } catch (error) {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    } finally {
        sendBtn.disabled = false;
    }
}

// Append new message to chat
function appendMessage(message) {
    const messagesList = document.getElementById('messagesList');
    const isFarmer = message.sender_type === 'farmer';
    const isCurrentUser = message.sender.id === currentUserId;
    
    const messageHtml = `
        <div class="message-item mb-3 ${isFarmer ? 'text-start' : 'text-end'}">
            <div class="d-inline-block" style="max-width: 70%;">
                <div class="card mb-1 ${isFarmer ? 'bg-light' : 'bg-primary text-white'}">
                    <div class="card-body p-2">
                        <p class="mb-1" style="white-space: pre-wrap;">${message.body}</p>
                        <small class="${isFarmer ? 'text-muted' : 'text-white-50'}">
                            ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                        </small>
                    </div>
                </div>
                <small class="${isFarmer ? 'text-muted' : 'text-end'}">
                    ${message.sender.name}
                </small>
            </div>
        </div>
    `;
    
    messagesList.insertAdjacentHTML('afterbegin', messageHtml);
    
    // Scroll to bottom
    const container = document.getElementById('messagesContainer');
    container.scrollTop = 0;
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
        }
    } catch (error) {
        console.error('Error resolving chat:', error);
        alert('Failed to resolve chat. Please try again.');
    }
}

// Poll for new messages (fallback if not using WebSockets)
setInterval(async () => {
    if (document.hidden) return;
    
    try {
        const response = await fetch(`/${routePrefix}/support/${chatId}`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        
        // This would require an API endpoint that returns JSON
        // For now, just reload if needed
    } catch (error) {
        console.error('Error polling messages:', error);
    }
}, 5000); // Poll every 5 seconds
</script>
@endpush