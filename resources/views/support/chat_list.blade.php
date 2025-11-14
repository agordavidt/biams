@extends($scope['type'] === 'farmer' ? 'layouts.farmer' : ($scope['type'] === 'lga' ? 'layouts.lga_admin' : 'layouts.admin'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">
                @if($scope['type'] === 'farmer')
                    My Support Chats
                @elseif($scope['type'] === 'lga')
                    {{ $scope['lga_name'] }} Support Queue
                @else
                    State-Wide Support Queue
                @endif
            </h4>

            @if($scope['type'] === 'farmer')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createChatModal">
                     New Support Request
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Active Chats</option>
                            <option value="open" {{ $currentStatus === 'open' ? 'selected' : '' }}>Unassigned</option>
                            <option value="resolved" {{ $currentStatus === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $currentStatus === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    @if($scope['type'] === 'state')
                        <div class="col-md-3">
                            <label class="form-label">LGA Filter</label>
                            <select name="lga_id" class="form-select" onchange="this.form.submit()">
                                <option value="">All LGAs</option>
                                @foreach($lgas as $lga)
                                    <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>
                                        {{ $lga->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Chat List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($chats->isEmpty())
                    <div class="text-center py-5">
                        <i class="ri-message-3-line display-4 text-muted"></i>
                        <p class="mt-3 text-muted">No chats found</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    @if($scope['type'] !== 'farmer')
                                        <th>Farmer</th>
                                    @endif
                                    @if($scope['type'] === 'state')
                                        <th>LGA</th>
                                    @endif
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    @if($scope['type'] !== 'farmer')
                                        <th>Assigned To</th>
                                    @endif
                                    <th>Last Activity</th>
                                    <th>Unread</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chats as $chat)
                                    <tr>
                                        @if($scope['type'] !== 'farmer')
                                            <td>
                                                <strong>{{ $chat->farmer->full_name }}</strong><br>
                                                <small class="text-muted">{{ $chat->farmer->phone_primary }}</small>
                                            </td>
                                        @endif
                                        
                                        @if($scope['type'] === 'state')
                                            <td>{{ $chat->lga->name }}</td>
                                        @endif
                                        
                                        <td>
                                            {{ $chat->subject ?: 'Support Request' }}
                                            @if($chat->latestMessage)
                                                <br><small class="text-muted">{{ Str::limit($chat->latestMessage->body, 50) }}</small>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-{{ $chat->status === 'open' ? 'warning' : ($chat->status === 'resolved' ? 'success' : 'primary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $chat->status)) }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-{{ $chat->priority === 'urgent' ? 'danger' : ($chat->priority === 'high' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($chat->priority) }}
                                            </span>
                                        </td>
                                        
                                        @if($scope['type'] !== 'farmer')
                                            <td>
                                                @if($chat->assignedAdmin)
                                                    {{ $chat->assignedAdmin->name }}
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                        @endif
                                        
                                        <td>{{ $chat->last_message_at?->diffForHumans() }}</td>
                                        
                                        <td>
                                            @if($chat->unread_count > 0)
                                                <span class="badge bg-danger rounded-pill">{{ $chat->unread_count }}</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <a href="{{ route($scope['type'] === 'farmer' ? 'farmer.support.show' : ($scope['type'] === 'lga' ? 'lga_admin.support.show' : 'admin.support.show'), $chat) }}" 
                                               class="btn btn-sm btn-primary">
                                                 View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $chats->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Chat Modal (Farmer Only) -->
@if($scope['type'] === 'farmer')
    <div class="modal fade" id="createChatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('farmer.support.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Support Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Subject (Optional)</label>
                            <input type="text" name="subject" class="form-control" placeholder="Brief description of your issue">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="5" required placeholder="Describe your issue in detail..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Auto-refresh for new chats (optional - comment out if using Laravel Echo)
    setInterval(() => {
        if (document.hidden) return; // Don't refresh if tab is hidden
        window.location.reload();
    }, 60000); // Refresh every minute
</script>
@endpush