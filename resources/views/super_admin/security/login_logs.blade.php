@extends('layouts.super_admin')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Login Security Logs</h4>
                    <div class="page-title-right">
                        <a href="{{ route('super_admin.export_login_logs') }}" class="btn btn-success btn-sm">
                            <i class="ri-download-line me-1"></i> Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Attempts</p>
                                <h4 class="mb-2">{{ $stats['total_attempts'] }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-login-circle-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Successful</p>
                                <h4 class="mb-2 text-success">{{ $stats['successful_logins'] }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="ri-check-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Failed</p>
                                <h4 class="mb-2 text-warning">{{ $stats['failed_attempts'] }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-warning rounded-3">
                                    <i class="ri-close-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Blocked</p>
                                <h4 class="mb-2 text-danger">{{ $stats['blocked_attempts'] }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-danger rounded-3">
                                    <i class="ri-shield-check-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Suspicious</p>
                                <h4 class="mb-2 text-danger">{{ $stats['suspicious_attempts'] }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-danger rounded-3">
                                    <i class="ri-alert-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Unique IPs</p>
                                <h4 class="mb-2 text-info">{{ $stats['unique_ips'] }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-info rounded-3">
                                    <i class="ri-global-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('super_admin.login_logs') }}" class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All</option>
                                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" value="{{ request('email') }}" placeholder="Search email">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">IP Address</label>
                                <input type="text" name="ip_address" class="form-control" value="{{ request('ip_address') }}" placeholder="Search IP">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Suspicious</label>
                                <select name="suspicious" class="form-select">
                                    <option value="">All</option>
                                    <option value="1" {{ request('suspicious') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ request('suspicious') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('super_admin.login_logs') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Logs Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Login Attempts</h4>
                        <div class="table-responsive">
                            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Email</th>
                                        <th>User</th>
                                        <th>IP Address</th>
                                        <th>Device</th>
                                        <th>Browser</th>
                                        <th>Status</th>
                                        <th>Suspicious</th>
                                        <th>Timestamp</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr>
                                            <td>
                                                <span class="text-body fw-bold">{{ $log->email }}</span>
                                            </td>
                                            <td>
                                                @if($log->user)
                                                    <span class="text-success">{{ $log->user->name }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-body">{{ $log->ip_address }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="ri-{{ $log->device_type == 'mobile' ? 'smartphone' : ($log->device_type == 'tablet' ? 'tablet' : 'computer') }}-line me-1"></i>
                                                    {{ ucfirst($log->device_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-body">{{ $log->browser }}</span>
                                            </td>
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
                                                    <span class="badge bg-danger">
                                                        <i class="ri-alert-line me-1"></i>Suspicious
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">Normal</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-body">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('super_admin.login_log_details', $log) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No login logs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Insights -->
        <div class="row">
            <!-- Top Failed Emails -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Top Failed Login Attempts</h4>
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Email</th>
                                        <th>Failed Attempts</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topFailedEmails as $email)
                                        <tr>
                                            <td>{{ $email->email }}</td>
                                            <td>
                                                <span class="badge bg-warning">{{ $email->attempt_count }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger" onclick="blockEmail('{{ $email->email }}')">
                                                    <i class="ri-shield-check-line me-1"></i>Block
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No failed attempts found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Suspicious IPs -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Top Suspicious IP Addresses</h4>
                        <div class="table-responsive">
                            <table class="table table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>IP Address</th>
                                        <th>Attempts</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topSuspiciousIPs as $ip)
                                        <tr>
                                            <td>{{ $ip->ip_address }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $ip->attempt_count }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger" onclick="blockIP('{{ $ip->ip_address }}')">
                                                    <i class="ri-shield-check-line me-1"></i>Block IP
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No suspicious IPs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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

function blockEmail(email) {
    // Implement email blocking functionality
    alert('Email blocking functionality to be implemented');
}
</script>
@endsection 