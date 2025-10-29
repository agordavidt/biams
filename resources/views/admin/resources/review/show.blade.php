@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Review Vendor Resource</h4>
                    <p class="text-muted mb-0">{{ $resource->vendor->legal_name }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.resources.review.index') }}" class="btn btn-light">
                       Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Resource Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Resource Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Resource Name</label>
                            <p class="text-muted">{{ $resource->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Type</label>
                            <p class="text-muted text-capitalize">{{ str_replace('_', ' ', $resource->type) }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <p>
                                <span class="badge bg-{{ $resource->status === 'proposed' ? 'warning' : ($resource->status === 'approved' ? 'success' : 'info') }}">
                                    {{ ucfirst($resource->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <p class="text-muted">{{ $resource->description }}</p>
                    </div>

                    @if($resource->requires_quantity)
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Unit</label>
                                <p class="text-muted">{{ $resource->unit }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Total Stock</label>
                                <p class="text-muted">{{ number_format($resource->total_stock) }} {{ $resource->unit }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Max Per Farmer</label>
                                <p class="text-muted">{{ number_format($resource->max_per_farmer) }} {{ $resource->unit }}</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">                           
                            This is a service/training resource and does not require quantity management.
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Vendor's Original Price</label>
                        <p class="text-muted fs-5 text-success">₦{{ number_format($resource->original_price, 2) }} per {{ $resource->unit ?? 'unit' }}</p>
                    </div>

                    @if($resource->admin_notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Admin Notes</label>
                            <p class="text-muted">{{ $resource->admin_notes }}</p>
                        </div>
                    @endif

                    @if($resource->rejection_reason)
                        <div class="alert alert-danger">
                            <strong>Previous Rejection Reason:</strong><br>
                            {{ $resource->rejection_reason }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Review Actions</h5>
                </div>
                <div class="card-body">
                    @if(in_array($resource->status, ['proposed', 'under_review']))
                        <!-- Mark Under Review -->
                        @if($resource->status === 'proposed')
                            <form action="{{ route('admin.resources.review.mark-under-review', $resource) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-info w-100">
                                    Mark as Under Review
                                </button>
                            </form>
                        @endif

                        <!-- Approve Resource -->
                        <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                            Approve & Set Subsidy
                        </button>

                        <!-- Reject Resource -->
                        <button type="button" class="btn btn-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                             Reject Proposal
                        </button>

                        <!-- Edit Resource -->
                        <a href="{{ route('admin.resources.review.edit', $resource) }}" class="btn btn-light w-100">
                           Edit Details
                        </a>

                    @elseif($resource->status === 'approved')
                        <!-- Publish Resource -->
                        <form action="{{ route('admin.resources.review.publish', $resource) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Publish this resource? It will be visible to farmers.')">
                                Publish Resource
                            </button>
                        </form>
                        
                        <div class="alert alert-success mt-3">                           
                            This resource is approved. Click "Publish" to make it available to farmers.
                        </div>

                    @elseif($resource->status === 'active')
                        <!-- Unpublish Resource -->
                        <form action="{{ route('admin.resources.review.unpublish', $resource) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Unpublish this resource? It will be hidden from farmers.')">
                                Unpublish Resource
                            </button>
                        </form>
                        
                        <div class="alert alert-info mt-3">
                            
                            This resource is currently active and visible to farmers.
                        </div>

                    @elseif($resource->status === 'rejected')
                        <div class="alert alert-danger">
                            
                            This resource has been rejected. The vendor can edit and resubmit.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vendor Information -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Vendor Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Name:</strong><br>{{ $resource->vendor->legal_name }}</p>
                    <p class="mb-2"><strong>Email:</strong><br>{{ $resource->vendor->email }}</p>
                    <p class="mb-0"><strong>Phone:</strong><br>{{ $resource->vendor->phone_number }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.resources.review.approve', $resource) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Resource & Set Subsidy</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Vendor's Price:</strong> ₦{{ number_format($resource->original_price, 2) }} per {{ $resource->unit ?? 'unit' }}
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Subsidized Price for Farmers (₦) *</label>
                        <input type="number" class="form-control" name="subsidized_price" 
                               step="0.01" min="0" max="{{ $resource->original_price }}" required>
                        <small class="text-muted">Amount farmers will pay (must be ≤ vendor price)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vendor Reimbursement (₦) *</label>
                        <input type="number" class="form-control" name="vendor_reimbursement" 
                               step="0.01" min="0" value="{{ $resource->original_price }}" required>
                        <small class="text-muted">Amount vendor will receive from Ministry</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" name="admin_notes" rows="3" 
                                  placeholder="Additional notes about pricing or approval"></textarea>
                    </div>                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                       Approve Resource
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.resources.review.reject', $resource) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Resource Proposal</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason *</label>
                        <textarea class="form-control" name="rejection_reason" rows="4" 
                                  required minlength="10" 
                                  placeholder="Provide detailed reason for rejection (minimum 10 characters)"></textarea>
                        <small class="text-muted">The vendor will see this message</small>
                    </div>

                    <div class="alert alert-warning">
                       
                        The vendor will be notified and can edit their proposal to resubmit.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        Reject Proposal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
