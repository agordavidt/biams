@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Resource Applications</h4>
                    <p class="text-muted mb-0">Manage all applications for your resources</p>
                </div>
                <div>
                    <a href="{{ route('vendor.resources.index') }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Back to Resources
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total</p>
                            <h4 class="mb-0">{{ $stats->total ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary text-primary rounded">
                                <i class="ri-file-list-line font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Pending</p>
                            <h4 class="mb-0 text-warning">{{ $stats->pending ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning text-warning rounded">
                                <i class="ri-time-line font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Paid</p>
                            <h4 class="mb-0 text-success">{{ $stats->paid ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success text-success rounded">
                                <i class="ri-money-dollar-circle-line font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Fulfilled</p>
                            <h4 class="mb-0 text-info">{{ $stats->fulfilled ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info text-info rounded">
                                <i class="ri-checkbox-circle-line font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Rejected</p>
                            <h4 class="mb-0 text-danger">{{ $stats->rejected ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger text-danger rounded">
                                <i class="ri-close-circle-line font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Revenue</p>
                            <h4 class="mb-0">₦{{ number_format($stats->total_revenue ?? 0, 0) }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary text-primary rounded">
                                <i class="ri-wallet-3-line font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('vendor.resources.all-applications') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search Farmer</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="Name, Phone, Email, NIN...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Resource</label>
                            <select name="resource_id" class="form-select">
                                <option value="">All Resources</option>
                                @foreach($resources as $resource)
                                    <option value="{{ $resource->id }}" {{ request('resource_id') == $resource->id ? 'selected' : '' }}>
                                        {{ $resource->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="fulfilled" {{ request('status') == 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">All</option>
                                <option value="verified" {{ request('payment_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-search-line me-1"></i> Filter
                            </button>
                            <a href="{{ route('vendor.resources.all-applications') }}" class="btn btn-light">
                                <i class="ri-refresh-line me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Applications List</h5>
                        <div>
                            <span class="text-muted">{{ $applications->total() }} applications found</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Farmer Details</th>
                                    <th>Resource</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $app)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $app->farmer ? $app->farmer->full_name : $app->user->name }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="ri-phone-line me-1"></i>{{ $app->farmer ? $app->farmer->phone_number : ($app->user->phone ?? 'N/A') }}<br>
                                                    
                                                    <i class="ri-mail-line me-1"></i>{{ $app->user->email }}
                                                </small>
                                                <!-- @if($app->farmer && $app->farmer->nin)
                                                    <br><small class="text-muted">NIN: {{ $app->farmer->nin }}</small>
                                                @endif -->
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $app->resource->name }}</strong><br>
                                            <small class="text-muted text-capitalize">{{ str_replace('_', ' ', $app->resource->type) }}</small>
                                        </td>
                                        <td>
                                            @if($app->resource->requires_quantity)
                                                <div>
                                                    <strong>Requested:</strong> {{ $app->quantity_requested }} {{ $app->resource->unit }}<br>
                                                    @if($app->quantity_approved)
                                                        <span class="text-success"><strong>Approved:</strong> {{ $app->quantity_approved }} {{ $app->resource->unit }}</span><br>
                                                    @endif
                                                    @if($app->quantity_fulfilled)
                                                        <span class="text-info"><strong>Fulfilled:</strong> {{ $app->quantity_fulfilled }} {{ $app->resource->unit }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($app->amount_paid)
                                                <strong class="text-success">₦{{ number_format($app->amount_paid, 2) }}</strong>
                                            @else
                                                <span class="text-muted">₦{{ number_format($app->unit_price ?? 0, 2) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($app->payment_reference)
                                                <span class="badge bg-{{ $app->payment_status == 'verified' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($app->payment_status ?? 'pending') }}
                                                </span><br>
                                                <small class="text-muted">{{ substr($app->payment_reference, 0, 20) }}...</small>
                                            @else
                                                <span class="text-muted">No payment</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'payment_pending' => 'info',
                                                    'paid' => 'success',
                                                    'approved' => 'primary',
                                                    'fulfilled' => 'success',
                                                    'rejected' => 'danger',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$app->status] ?? 'secondary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $app->created_at->format('M d, Y') }}<br>{{ $app->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('vendor.resources.application.show', $app) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                
                                                @if(in_array($app->status, ['pending', 'payment_pending']))
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            onclick="approveApplication({{ $app->id }})" title="Verify & Approve">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                @endif
                                                
                                                @if(in_array($app->status, ['paid', 'approved']))
                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                            onclick="fulfillApplication({{ $app->id }})" title="Mark Fulfilled">
                                                        <i class="ri-checkbox-circle-line"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="ri-inbox-line display-4 d-block mb-2"></i>
                                            No applications found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Verify Payment & Approve Application</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        Please verify that payment has been received before approving.
                    </div>
                    
                    <div class="mb-3" id="quantityApprovedContainer" style="display: none;">
                        <label class="form-label">Quantity to Approve</label>
                        <input type="number" class="form-control" id="quantityApproved" name="quantity_approved" min="1">
                        <small class="text-muted" id="quantityHelp"></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="approveNotes" name="admin_notes" rows="2" 
                                  placeholder="Add any notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="confirmApproveBtn">
                        <i class="ri-check-line me-1"></i> Approve Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Fulfill Modal -->
<div class="modal fade" id="fulfillModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Confirm Resource Fulfillment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="fulfillForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i>
                        Confirm that you have delivered this resource to the farmer.
                    </div>
                    
                    <div class="mb-3" id="quantityFulfilledContainer" style="display: none;">
                        <label class="form-label">Quantity Fulfilled</label>
                        <input type="number" class="form-control" id="quantityFulfilled" name="quantity_fulfilled" min="1">
                        <small class="text-muted" id="fulfillQuantityHelp"></small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fulfillment Notes (Optional)</label>
                        <textarea class="form-control" id="fulfillmentNotes" name="fulfillment_notes" rows="2" 
                                  placeholder="Add delivery notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmFulfillBtn">
                        <i class="ri-checkbox-circle-line me-1"></i> Confirm Fulfillment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
let currentApplicationId = null;
let currentApplication = null;

function approveApplication(applicationId) {
    currentApplicationId = applicationId;
    
    // Fetch application details
    fetch(`/vendor/resources/applications/${applicationId}`)
        .then(response => response.json())
        .then(data => {
            currentApplication = data;
            
            if (data.requires_quantity) {
                document.getElementById('quantityApprovedContainer').style.display = 'block';
                document.getElementById('quantityApproved').value = data.quantity_requested;
                document.getElementById('quantityApproved').max = data.quantity_requested;
                document.getElementById('quantityHelp').textContent = `Max: ${data.quantity_requested} ${data.unit}`;
            } else {
                document.getElementById('quantityApprovedContainer').style.display = 'none';
            }
            
            const modal = new bootstrap.Modal(document.getElementById('approveModal'));
            modal.show();
        });
}

function fulfillApplication(applicationId) {
    currentApplicationId = applicationId;
    
    // Fetch application details
    fetch(`/vendor/resources/applications/${applicationId}`)
        .then(response => response.json())
        .then(data => {
            currentApplication = data;
            
            if (data.requires_quantity) {
                document.getElementById('quantityFulfilledContainer').style.display = 'block';
                const maxQuantity = data.quantity_paid || data.quantity_approved;
                document.getElementById('quantityFulfilled').value = maxQuantity;
                document.getElementById('quantityFulfilled').max = maxQuantity;
                document.getElementById('fulfillQuantityHelp').textContent = `Max: ${maxQuantity} ${data.unit}`;
            } else {
                document.getElementById('quantityFulfilledContainer').style.display = 'none';
            }
            
            const modal = new bootstrap.Modal(document.getElementById('fulfillModal'));
            modal.show();
        });
}

document.getElementById('approveForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('confirmApproveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(`/vendor/resources/applications/${currentApplicationId}/verify-approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
            toastr.success(data.message || 'Application approved successfully');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            toastr.error(data.error || 'Failed to approve application');
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-check-line me-1"></i> Approve Application';
        }
    } catch (error) {
        toastr.error('An error occurred');
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-check-line me-1"></i> Approve Application';
    }
});

document.getElementById('fulfillForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('confirmFulfillBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(`/vendor/resources/applications/${currentApplicationId}/fulfill`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
            toastr.success(data.message || 'Application fulfilled successfully');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            toastr.error(data.error || 'Failed to fulfill application');
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-checkbox-circle-line me-1"></i> Confirm Fulfillment';
        }
    } catch (error) {
        toastr.error('An error occurred');
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-checkbox-circle-line me-1"></i> Confirm Fulfillment';
    }
});
</script>
@endpush
@endsection