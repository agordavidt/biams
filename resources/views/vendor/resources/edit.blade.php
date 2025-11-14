@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Resource Proposal</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.resources.index') }}">Resources</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Status Alert -->
            @if($resource->status === 'rejected' && $resource->rejection_reason)
            <div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
                <i class="ri-error-warning-line me-3 align-middle fs-16"></i>
                <strong>Rejection Reason:</strong> {{ $resource->rejection_reason }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <!-- Information Alert -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading"><i class="ri-information-line me-2"></i>Resubmission Process</h5>
                        <p class="mb-0">
                            After editing and resubmitting, your proposal will return to "Proposed" status and 
                            will be reviewed again by the State Admin. Make sure to address any feedback from 
                            the previous review.
                        </p>
                    </div>

                    <form action="{{ route('vendor.resources.update', $resource) }}" method="POST" x-data="vendorResourceForm()">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <h5 class="mb-3">Resource Information</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Resource Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $resource->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Resource Type *</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        name="type" x-model="resourceType" required>
                                    <option value="">Select Type</option>
                                    <option value="seed" {{ old('type', $resource->type) === 'seed' ? 'selected' : '' }}>Seeds</option>
                                    <option value="fertilizer" {{ old('type', $resource->type) === 'fertilizer' ? 'selected' : '' }}>Fertilizer</option>
                                    <option value="equipment" {{ old('type', $resource->type) === 'equipment' ? 'selected' : '' }}>Equipment</option>
                                    <option value="pesticide" {{ old('type', $resource->type) === 'pesticide' ? 'selected' : '' }}>Pesticide</option>
                                    <option value="training" {{ old('type', $resource->type) === 'training' ? 'selected' : '' }}>Training</option>
                                    <option value="service" {{ old('type', $resource->type) === 'service' ? 'selected' : '' }}>Service</option>
                                    <option value="tractor_service" {{ old('type', $resource->type) === 'tractor_service' ? 'selected' : '' }}>Tractor Service</option>
                                    <option value="other" {{ old('type', $resource->type) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="4" required>{{ old('description', $resource->description) }}</textarea>
                            <small class="text-muted">Provide detailed description (minimum 20 characters)</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pricing -->
                        <h5 class="mb-3 mt-4 border-top pt-3">Pricing Information</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Your Price (₦) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       name="price" step="0.01" min="0" 
                                       value="{{ old('price', $resource->original_price) }}" required>
                                <small class="text-muted">Your selling price per unit</small>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($resource->subsidized_price)
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            <strong>Current Subsidized Price:</strong> ₦{{ number_format($resource->subsidized_price, 2) }}<br>
                            <small>This was set by the State Admin. It will be reviewed again after resubmission.</small>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="ri-alert-line me-2"></i>
                            <strong>Note:</strong> The State Admin will set the subsidized price for farmers during review. 
                            You will receive your full price as reimbursement from the Ministry.
                        </div>
                        @endif

                        <!-- Stock Management (Only for physical resources) -->
                        <div x-show="requiresQuantity" x-transition>
                            <h5 class="mb-3 mt-4 border-top pt-3">Stock Information</h5>
                            
                            @if($resource->requires_quantity && ($resource->total_stock - $resource->available_stock) > 0)
                            <div class="alert alert-warning">
                                <i class="ri-alert-line me-2"></i>
                                <strong>Note:</strong> {{ number_format($resource->total_stock - $resource->available_stock) }} 
                                {{ $resource->unit }} have been allocated to applications. You can only increase the total stock, 
                                not decrease it below the allocated amount.
                            </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Unit of Measurement *</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                           name="unit" value="{{ old('unit', $resource->unit) }}"
                                           placeholder="e.g., Kg, Bags, Pieces"
                                           x-bind:required="requiresQuantity">
                                    <small class="text-muted">How you measure this resource</small>
                                    @error('unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Total Available Stock *</label>
                                    <input type="number" class="form-control @error('total_stock') is-invalid @enderror" 
                                           name="total_stock" 
                                           min="{{ $resource->total_stock - $resource->available_stock }}" 
                                           value="{{ old('total_stock', $resource->total_stock) }}"
                                           x-bind:required="requiresQuantity">
                                    <small class="text-muted">
                                        @if($resource->requires_quantity)
                                            Min: {{ number_format($resource->total_stock - $resource->available_stock) }} (allocated)
                                        @else
                                            Total quantity you can supply
                                        @endif
                                    </small>
                                    @error('total_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Maximum Per Farmer *</label>
                                    <input type="number" class="form-control @error('max_per_farmer') is-invalid @enderror" 
                                           name="max_per_farmer" min="1" 
                                           value="{{ old('max_per_farmer', $resource->max_per_farmer) }}"
                                           x-bind:required="requiresQuantity">
                                    <small class="text-muted">Max a farmer can request</small>
                                    @error('max_per_farmer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if($resource->requires_quantity)
                            <!-- Current Stock Status -->
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Current Stock Status</h6>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <h4 class="text-primary">{{ number_format($resource->total_stock) }}</h4>
                                            <p class="text-muted mb-0 small">Total Stock</p>
                                        </div>
                                        <div class="col-4">
                                            <h4 class="text-success">{{ number_format($resource->available_stock) }}</h4>
                                            <p class="text-muted mb-0 small">Available</p>
                                        </div>
                                        <div class="col-4">
                                            <h4 class="text-warning">{{ number_format($resource->total_stock - $resource->available_stock) }}</h4>
                                            <p class="text-muted mb-0 small">Allocated</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between gap-2 mt-4 border-top pt-3">
                            <div>
                                <a href="{{ route('vendor.resources.show', $resource) }}" class="btn btn-light">
                                    Cancel
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                     Delete
                                </button>
                                <button type="submit" class="btn btn-primary">
                                     Update & Resubmit
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Hidden Delete Form -->
                    <form id="delete-form" action="{{ route('vendor.resources.destroy', $resource) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function vendorResourceForm() {
        return {
            resourceType: '{{ old("type", $resource->type) }}',

            get requiresQuantity() {
                return !['service', 'training'].includes(this.resourceType);
            }
        };
    }

    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "This resource proposal will be permanently deleted. This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endpush