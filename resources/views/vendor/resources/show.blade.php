@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Resource Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.resources.index') }}">Resources</a></li>
                        <li class="breadcrumb-item active">{{ $resource->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if($resource->status === 'rejected' && $resource->rejection_reason)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-2"></i>
                <strong>Rejection Reason:</strong> {{ $resource->rejection_reason }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @if($resource->status === 'approved')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-2"></i>
                <strong>Approved!</strong> Your resource has been approved and is awaiting publication by the State Admin.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @if($resource->status === 'active')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-2"></i>
                <strong>Active!</strong> Your resource is now live and farmers can apply for it.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Resource Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Resource Information</h5>
                    @php
                        $statusColors = [
                            'proposed' => 'warning',
                            'under_review' => 'info',
                            'approved' => 'primary',
                            'active' => 'success',
                            'rejected' => 'danger',
                            'inactive' => 'secondary'
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$resource->status] ?? 'secondary' }} fs-6">
                        {{ ucwords(str_replace('_', ' ', $resource->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Resource Name</label>
                            <h5>{{ $resource->name }}</h5>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted mb-1">Type</label>
                            <h5><span class="badge bg-info">{{ ucfirst($resource->type) }}</span></h5>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted mb-1">Payment Required</label>
                            <h5>
                                @if($resource->requires_payment)
                                <span class="badge bg-success">Yes</span>
                                @else
                                <span class="badge bg-secondary">No</span>
                                @endif
                            </h5>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Description</label>
                        <p class="mb-0">{{ $resource->description }}</p>
                    </div>

                    <hr>

                    <!-- Pricing Information -->
                    <h6 class="mb-3">Pricing Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted mb-1">Your Price</label>
                            <h5 class="text-primary">₦{{ number_format($resource->original_price, 2) }}</h5>
                            <small class="text-muted">Your selling price</small>
                        </div>
                        @if($resource->subsidized_price)
                        <div class="col-md-4">
                            <label class="text-muted mb-1">Farmer Co-Payment</label>
                            <h5 class="text-success">₦{{ number_format($resource->subsidized_price, 2) }}</h5>
                            <small class="text-muted">What farmers pay</small>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted mb-1">Your Reimbursement</label>
                            <h5 class="text-info">₦{{ number_format($resource->vendor_reimbursement ?? $resource->original_price, 2) }}</h5>
                            <small class="text-muted">From Ministry</small>
                        </div>
                        @else
                        <div class="col-md-8">
                            <div class="alert alert-warning mb-0">
                                <i class="ri-information-line me-2"></i>
                                Subsidized price will be set by State Admin during approval
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($resource->requires_quantity)
                    <hr>

                    <!-- Stock Information -->
                    <h6 class="mb-3">Stock Information</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="text-muted mb-1">Unit</label>
                            <h5>{{ $resource->unit }}</h5>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted mb-1">Total Stock</label>
                            <h5>{{ number_format($resource->total_stock) }}</h5>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted mb-1">Available</label>
                            <h5 class="text-success">{{ number_format($resource->available_stock) }}</h5>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted mb-1">Max/Farmer</label>
                            <h5>{{ number_format($resource->max_per_farmer) }}</h5>
                        </div>
                    </div>

                    <!-- Stock Progress Bar -->
                    <div class="mt-3">
                        @php
                            $allocated = $resource->total_stock - $resource->available_stock;
                            $percentage = $resource->total_stock > 0 ? ($allocated / $resource->total_stock) * 100 : 0;
                        @endphp
                        <label class="text-muted mb-1">Stock Utilization</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $percentage }}%"
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($percentage, 1) }}% Allocated
                            </div>
                        </div>
                        <small class="text-muted">
                            {{ number_format($allocated) }} of {{ number_format($resource->total_stock) }} {{ $resource->unit }} allocated
                        </small>
                    </div>
                    @endif

                    <hr>

                    <!-- Review Information -->
                    @if($resource->reviewed_by)
                    <h6 class="mb-3">Review Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Reviewed By</label>
                            <p>{{ $resource->reviewedBy->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Reviewed At</label>
                            <p>{{ $resource->reviewed_at ? $resource->reviewed_at->format('M d, Y h:i A') : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($resource->admin_notes)
                    <div class="alert alert-info">
                        <strong>Admin Notes:</strong><br>
                        {{ $resource->admin_notes }}
                    </div>
                    @endif
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('vendor.resources.index') }}" class="btn btn-light">
                            <i class="ri-arrow-left-line me-1"></i> Back to List
                        </a>
                        
                        <div class="btn-group">
                            @if(in_array($resource->status, ['proposed', 'rejected']))
                            <a href="{{ route('vendor.resources.edit', $resource) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-1"></i> Edit
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="ri-delete-bin-line me-1"></i> Delete
                            </button>
                            @endif

                            @if(in_array($resource->status, ['active', 'approved']))
                            <a href="{{ route('vendor.resources.applications', $resource) }}" class="btn btn-info">
                                <i class="ri-file-list-line me-1"></i> View Applications
                            </a>
                            @endif
                        </div>
                    </div>

                    @if(in_array($resource->status, ['proposed', 'rejected']))
                    <form id="delete-form" action="{{ route('vendor.resources.destroy', $resource) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Sidebar -->
        <div class="col-lg-4">
            <!-- Application Statistics -->
            @if(in_array($resource->status, ['active', 'approved']))
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Application Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Applications</span>
                            <h4 class="mb-0">{{ $applicationStats['total'] ?? 0 }}</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Pending Review</span>
                            <span class="badge bg-warning">{{ $applicationStats['pending'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Approved</span>
                            <span class="badge bg-primary">{{ $applicationStats['approved'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Payment Pending</span>
                            <span class="badge bg-info">{{ $applicationStats['payment_pending'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Paid</span>
                            <span class="badge bg-success">{{ $applicationStats['paid'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Fulfilled</span>
                            <span class="badge bg-dark">{{ $applicationStats['fulfilled'] ?? 0 }}</span>
                        </div>
                    </div>

                    @if($applicationStats['total'] > 0)
                    <hr>
                    <a href="{{ route('vendor.resources.applications', $resource) }}" class="btn btn-soft-primary w-100">
                        <i class="ri-file-list-line me-1"></i> View All Applications
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-badge bg-primary">
                                <i class="ri-file-add-line"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Proposal Submitted</h6>
                                <small class="text-muted">{{ $resource->created_at->format('M d, Y h:i A') }}</small>
                                <p class="mb-0 text-muted">By {{ $resource->createdBy->name ?? 'System' }}</p>
                            </div>
                        </div>

                        @if($resource->reviewed_at)
                        <div class="timeline-item">
                            <div class="timeline-badge bg-{{ $resource->status === 'rejected' ? 'danger' : 'success' }}">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $resource->status === 'rejected' ? 'Rejected' : 'Reviewed' }}</h6>
                                <small class="text-muted">{{ $resource->reviewed_at->format('M d, Y h:i A') }}</small>
                                <p class="mb-0 text-muted">By {{ $resource->reviewedBy->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endif

                        @if($resource->status === 'active')
                        <div class="timeline-item">
                            <div class="timeline-badge bg-success">
                                <i class="ri-eye-line"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Published</h6>
                                <small class="text-muted">Available to farmers</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title"><i class="ri-information-line me-1"></i> Need Help?</h6>
                    <p class="text-muted small mb-2">
                        If you have questions about your resource proposal or need to make changes after approval, 
                        please contact the State Admin.
                    </p>
                    <div class="alert alert-info mb-0 small">
                        <strong>Payment Note:</strong> You will receive full reimbursement from the Ministry 
                        for all fulfilled applications, regardless of the farmer's co-payment amount.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
    border-left: 2px solid #e9ecef;
}

.timeline-item:last-child {
    border-left: none;
    padding-bottom: 0;
}

.timeline-badge {
    position: absolute;
    left: -42px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.timeline-content {
    padding-left: 15px;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Are you sure?',
        text: "This resource proposal will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endpush