@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">{{ $resource->name }} - Applications</h4>
                    <p class="text-muted mb-0">Manage fulfillment for this resource</p>
                </div>
                <div>
                    <a href="{{ route('vendor.distribution.resources') }}" class="btn btn-light">
                        Back to Resources
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-title bg-soft-primary text-primary rounded fs-3">
                                <i class="ri-file-list-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Total Applications</p>
                            <h4 class="mb-0">{{ $applicationStats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-title bg-soft-success text-success rounded fs-3">
                                <i class="ri-checkbox-circle-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Ready for Fulfillment</p>
                            <h4 class="mb-0">{{ $applicationStats['paid'] + $applicationStats['approved'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-title bg-soft-info text-info rounded fs-3">
                                <i class="ri-check-double-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Fulfilled</p>
                            <h4 class="mb-0">{{ $applicationStats['fulfilled'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-title bg-soft-warning text-warning rounded fs-3">
                                <i class="ri-hourglass-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Pending</p>
                            <h4 class="mb-0">{{ $applicationStats['paid'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('vendor.distribution.resource-applications', $resource) }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search Farmers</label>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Name, Phone, NIN, Email..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Ready to Fulfill</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="fulfilled" {{ request('status') == 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    Search
                                </button>
                                <a href="{{ route('vendor.distribution.resource-applications', $resource) }}" class="btn btn-light">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Farmer</th>
                                    <th>Contact</th>
                                    @if($resource->requires_quantity)
                                        <th>Quantity</th>
                                    @endif
                                    @if($resource->requires_payment)
                                        <th>Amount</th>
                                        <th>Payment Ref</th>
                                    @endif
                                    <th>Status</th>
                                    <th>Applied</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $app)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        {{ substr($app->farmer ? $app->farmer->full_name : $app->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $app->farmer ? $app->farmer->full_name : $app->user->name }}</h6>
                                                    @if($app->farmer && $app->farmer->nin)
                                                        <small class="text-muted">NIN: {{ $app->farmer->nin }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <small class="d-block">
                                                    <i class="ri-phone-line me-1"></i>
                                                    {{ $app->farmer ? $app->farmer->phone_number : ($app->user->phone ?? 'N/A') }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i class="ri-mail-line me-1"></i>
                                                    {{ $app->user->email }}
                                                </small>
                                            </div>
                                        </td>
                                        @if($resource->requires_quantity)
                                            <td>
                                                <div>
                                                    @if($app->quantity_fulfilled)
                                                        <span class="badge bg-soft-success text-success">
                                                            {{ $app->quantity_fulfilled }}/{{ $app->quantity_approved ?? $app->quantity_requested }} {{ $resource->unit }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-soft-warning text-warning">
                                                            {{ $app->quantity_approved ?? $app->quantity_requested }} {{ $resource->unit }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                        @if($resource->requires_payment)
                                            <td>
                                                <strong>₦{{ number_format($app->amount_paid ?? $app->total_amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                @if($app->payment_reference)
                                                    <code class="small">{{ substr($app->payment_reference, 0, 12) }}...</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'paid' => ['color' => 'success', 'text' => 'Ready'],
                                                    'approved' => ['color' => 'primary', 'text' => 'Approved'],
                                                    'fulfilled' => ['color' => 'info', 'text' => 'Fulfilled'],
                                                ];
                                                $config = $statusConfig[$app->status] ?? ['color' => 'secondary', 'text' => ucfirst($app->status)];
                                            @endphp
                                            <span class="badge bg-soft-{{ $config['color'] }} text-{{ $config['color'] }}">
                                                {{ $config['text'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $app->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('vendor.distribution.application.show', $app) }}" 
                                               class="btn btn-sm btn-light">
                                                View Details
                                            </a>
                                            @if(in_array($app->status, ['paid', 'approved']))
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="quickFulfill({{ $app->id }}, '{{ $app->farmer ? $app->farmer->full_name : $app->user->name }}')">
                                                    Fulfill
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="py-4">
                                                <i class="ri-file-list-line display-4 text-muted"></i>
                                                <p class="text-muted mt-3">No applications found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($applications->hasPages())
                        <div class="mt-3">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Fulfill Modal -->
<div class="modal fade" id="quickFulfillModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: #66bb6a;">
                <h5 class="modal-title">Quick Fulfillment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickFulfillForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Farmer:</strong> <span id="farmerName"></span>
                    </div>
                    
                    @if($resource->requires_quantity)
                        <div class="mb-3">
                            <label class="form-label">Quantity Fulfilled</label>
                            <input type="number" class="form-control" name="quantity_fulfilled" 
                                   id="quantityInput" min="1" required>
                            <small class="text-muted">{{ $resource->unit }}</small>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">Fulfillment Notes (Optional)</label>
                        <textarea class="form-control" name="fulfillment_notes" rows="2" 
                                  placeholder="Add delivery notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background: #66bb6a; color: #fff;">
                        Confirm Fulfillment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function quickFulfill(appId, farmerName) {
    const modal = new bootstrap.Modal(document.getElementById('quickFulfillModal'));
    document.getElementById('farmerName').textContent = farmerName;
    document.getElementById('quickFulfillForm').action = `/vendor/distribution/applications/${appId}/fulfill`;
    modal.show();
}
</script>
@endpush

@endsection