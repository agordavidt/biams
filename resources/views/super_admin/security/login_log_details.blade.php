@extends('layouts.super_admin')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Login Log Details</h4>
                    <div class="page-title-right">
                        <a href="{{ route('super_admin.login_logs') }}" class="btn btn-secondary btn-sm">
                            <i class="ri-arrow-left-line me-1"></i> Back to Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Log Details -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Login Attempt Details</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Log ID:</td>
                                        <td>#{{ $loginLog->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email:</td>
                                        <td>{{ $loginLog->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">User:</td>
                                        <td>
                                            @if($loginLog->user)
                                                <span class="text-success">{{ $loginLog->user->name }}</span>
                                                <br><small class="text-muted">ID: {{ $loginLog->user->id }}</small>
                                            @else
                                                <span class="text-muted">Not registered</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">IP Address:</td>
                                        <td>
                                            {{ $loginLog->ip_address }}
                                            @if($loginLog->is_suspicious)
                                                <span class="badge bg-danger ms-2">Suspicious</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status:</td>
                                        <td>
                                            @if($loginLog->status == 'success')
                                                <span class="badge bg-success">Success</span>
                                            @elseif($loginLog->status == 'failed')
                                                <span class="badge bg-warning">Failed</span>
                                            @elseif($loginLog->status == 'blocked')
                                                <span class="badge bg-danger">Blocked</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($loginLog->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Timestamp:</td>
                                        <td>{{ $loginLog->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Device Type:</td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="ri-{{ $loginLog->device_type == 'mobile' ? 'smartphone' : ($loginLog->device_type == 'tablet' ? 'tablet' : 'computer') }}-line me-1"></i>
                                                {{ ucfirst($loginLog->device_type) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Browser:</td>
                                        <td>{{ $loginLog->browser ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Platform:</td>
                                        <td>{{ $loginLog->platform ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Location:</td>
                                        <td>{{ $loginLog->location ?? 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Suspicious Activity:</td>
                                        <td>
                                            @if($loginLog->is_suspicious)
                                                <span class="badge bg-danger">
                                                    <i class="ri-alert-line me-1"></i>Yes
                                                </span>
                                            @else
                                                <span class="badge bg-success">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($loginLog->failure_reason)
                                    <tr>
                                        <td class="fw-bold">Failure Reason:</td>
                                        <td class="text-danger">{{ $loginLog->failure_reason }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <!-- User Agent Details -->
                        @if($loginLog->user_agent)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>User Agent Details</h5>
                                <div class="bg-light p-3 rounded">
                                    <code class="text-break">{{ $loginLog->user_agent }}</code>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Metadata -->
                        @if($loginLog->metadata)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Additional Metadata</h5>
                                <div class="bg-light p-3 rounded">
                                    <pre class="mb-0">{{ json_encode($loginLog->metadata, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Security Actions</h5>
                                <div class="d-flex gap-2">
                                    @if($loginLog->is_suspicious)
                                        <button class="btn btn-danger" onclick="blockIP('{{ $loginLog->ip_address }}')">
                                            <i class="ri-shield-check-line me-1"></i>Block IP Address
                                        </button>
                                    @endif
                                    
                                    @if($loginLog->user)
                                        <a href="{{ route('super_admin.users.edit', $loginLog->user) }}" class="btn btn-warning">
                                            <i class="ri-user-settings-line me-1"></i>Manage User
                                        </a>
                                    @endif
                                    
                                    <button class="btn btn-info" onclick="investigateIP('{{ $loginLog->ip_address }}')">
                                        <i class="ri-search-line me-1"></i>Investigate IP
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Login Attempts -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Related Login Attempts</h4>
                        
                        @php
                            $relatedLogs = \App\Models\LoginLog::where('email', $loginLog->email)
                                ->orWhere('ip_address', $loginLog->ip_address)
                                ->where('id', '!=', $loginLog->id)
                                ->latest()
                                ->take(10)
                                ->get();
                        @endphp
                        
                        @if($relatedLogs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-centered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Timestamp</th>
                                            <th>Email</th>
                                            <th>IP Address</th>
                                            <th>Status</th>
                                            <th>Suspicious</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($relatedLogs as $log)
                                            <tr>
                                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>{{ $log->email }}</td>
                                                <td>{{ $log->ip_address }}</td>
                                                <td>
                                                    @if($log->status == 'success')
                                                        <span class="badge bg-success">Success</span>
                                                    @elseif($log->status == 'failed')
                                                        <span class="badge bg-warning">Failed</span>
                                                    @elseif($log->status == 'blocked')
                                                        <span class="badge bg-danger">Blocked</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($log->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($log->is_suspicious)
                                                        <span class="badge bg-danger">Yes</span>
                                                    @else
                                                        <span class="badge bg-success">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No related login attempts found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Block IP Modal -->
<div class="modal fade" id="blockIPModal" tabindex="-1" aria-labelledby="blockIPModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="blockIPModalLabel">Block IP Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('super_admin.block_ip') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ip_address" class="form-label">IP Address</label>
                        <input type="text" class="form-control" id="ip_address" name="ip_address" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Blocking</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Block IP</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function blockIP(ipAddress) {
    document.getElementById('ip_address').value = ipAddress;
    new bootstrap.Modal(document.getElementById('blockIPModal')).show();
}

function investigateIP(ipAddress) {
    // Open IP investigation in new tab
    window.open(`https://www.abuseipdb.com/check/${ipAddress}`, '_blank');
}
</script>
@endsection 