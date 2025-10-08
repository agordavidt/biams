@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Marketplace Subscriptions</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.marketplace.dashboard') }}">Marketplace</a></li>
                    <li class="breadcrumb-item active">Subscriptions</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Paid</p>
                        <h4 class="mb-2">{{ $stats['total_paid'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-user-star-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Active Now</p>
                        <h4 class="mb-2">{{ $stats['active'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-shield-check-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Expiring Soon</p>
                        <h4 class="mb-2">{{ $stats['expiring_soon'] }}</h4>
                        <small class="text-muted">Within 30 days</small>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-alarm-warning-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Expired</p>
                        <h4 class="mb-2">{{ $stats['expired'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-danger rounded-3">
                            <i class="ri-close-circle-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subscriptions Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-10">
                        <form method="GET" action="{{ route('admin.marketplace.subscriptions') }}" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by name, email, or reference..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-search-line"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('admin.marketplace.subscriptions.export') }}" class="btn btn-success">
                            <i class="ri-download-line"></i> Export
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="subscriptionsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Farmer</th>
                                <th>Transaction Ref</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days Remaining</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $subscription)
                            <tr>
                                <td>
                                    <h6 class="mb-0">{{ $subscription->user->name }}</h6>
                                    <small class="text-muted">{{ $subscription->user->email }}</small>
                                </td>
                                <td><code>{{ $subscription->transaction_reference }}</code></td>
                                <td><strong>₦{{ number_format($subscription->amount, 2) }}</strong></td>
                                <td>
                                    @if($subscription->start_date)
                                        {{ $subscription->start_date->format('d M, Y') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subscription->end_date)
                                        {{ $subscription->end_date->format('d M, Y') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subscription->is_active)
                                        @if($subscription->days_remaining <= 30)
                                            <span class="badge bg-warning">{{ $subscription->days_remaining }} days</span>
                                        @else
                                            <span class="badge bg-success">{{ $subscription->days_remaining }} days</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($subscription->status === 'paid')
                                        @if($subscription->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Expired</span>
                                        @endif
                                    @elseif($subscription->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($subscription->status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst($subscription->payment_method ?? 'Credo') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="ri-inbox-line" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="mt-3 text-muted">No subscriptions found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-sm-6">
                        <div>
                            <p class="mb-sm-0">
                                Showing {{ $subscriptions->firstItem() ?? 0 }} to {{ $subscriptions->lastItem() ?? 0 }} 
                                of {{ $subscriptions->total() }} entries
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-end">
                            {{ $subscriptions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#subscriptionsTable').DataTable({
        "paging": false,
        "searching": false,
        "info": false,
        "ordering": true,
        "order": [[3, "desc"]]
    });
});
</script>
@endpush
@endsection