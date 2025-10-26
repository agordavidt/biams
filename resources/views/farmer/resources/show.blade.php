@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resource Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmer.resources.index') }}">Resources</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Resource Information -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="card-title mb-1">{{ $resource->name }}</h4>
                        <p class="text-muted mb-0">{{ $resource->vendor->legal_name }}</p>
                    </div>
                    <span class="badge badge-soft-success">Available</span>
                </div>

                <div class="mb-4">
                    <h6 class="mb-2">Description</h6>
                    <p class="text-muted">{{ $resource->description }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 40%;">Resource Type:</th>
                                <td>{{ ucfirst($resource->type) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Unit of Measurement:</th>
                                <td>{{ $resource->unit }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Farmer Co-Payment:</th>
                                <td><strong class="text-primary">₦{{ number_format($resource->price, 2) }}</strong> per {{ $resource->unit }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Government Subsidy:</th>
                                <td><strong class="text-success">₦{{ number_format($resource->vendor_reimbursement_price, 2) }}</strong> per {{ $resource->unit }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Total Value Per Unit:</th>
                                <td><strong>₦{{ number_format($resource->price + $resource->vendor_reimbursement_price, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th scope="row">Available Stock:</th>
                                <td>{{ number_format($resource->available_stock) }} {{ $resource->unit }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Maximum Per Farmer:</th>
                                <td><strong class="text-info">{{ $resource->max_per_farmer }} {{ $resource->unit }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Vendor Information -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Vendor Information</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Vendor Name</p>
                        <p class="mb-3"><strong>{{ $resource->vendor->legal_name }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Contact Person</p>
                        <p class="mb-3">{{ $resource->vendor->contact_person_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Phone</p>
                        <p class="mb-3">{{ $resource->vendor->contact_person_phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Email</p>
                        <p class="mb-3">{{ $resource->vendor->contact_person_email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($existingApplication)
        <!-- Existing Application -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Your Application</h5>
                
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    You have already applied for this resource.
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Status</p>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'payment_pending' => 'info',
                            'paid' => 'primary',
                            'fulfilled' => 'success',
                            'cancelled' => 'secondary'
                        ];
                    @endphp
                    <span class="badge badge-soft-{{ $statusColors[$existingApplication->status] ?? 'secondary' }}">
                        {{ ucwords(str_replace('_', ' ', $existingApplication->status)) }}
                    </span>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Quantity Requested</p>
                    <p class="mb-0"><strong>{{ $existingApplication->quantity_requested }} {{ $resource->unit }}</strong></p>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Applied On</p>
                    <p class="mb-0">{{ $existingApplication->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <a href="{{ route('farmer.resources.application-details', $existingApplication) }}" 
                   class="btn btn-info w-100">
                    <i class="ri-eye-line me-1"></i> View Application Details
                </a>
            </div>
        </div>
        @else
        <!-- Application Form -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Apply for This Resource</h5>

                <form action="{{ route('farmer.resources.apply', $resource) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="quantity_requested" class="form-label">
                            Quantity Requested <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('quantity_requested') is-invalid @enderror" 
                               id="quantity_requested" 
                               name="quantity_requested" 
                               min="1" 
                               max="{{ $resource->max_per_farmer }}" 
                               value="{{ old('quantity_requested') }}" 
                               required>
                        <small class="text-muted">
                            Maximum: {{ $resource->max_per_farmer }} {{ $resource->unit }}
                        </small>
                        @error('quantity_requested')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Important Information</h6>
                        <ul class="mb-0 ps-3">
                            <li>Your application will be reviewed by the State Admin</li>
                            <li>If approved, you'll receive a payment notification</li>
                            <li>Payment must be completed before collection</li>
                            <li>Collection is one-time only after payment</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                I understand and agree to the terms and conditions
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-send-plane-line me-1"></i> Submit Application
                        </button>
                        <a href="{{ route('farmer.resources.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back to Resources
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Calculation Preview -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">Estimated Cost</h6>
                <p class="text-muted small mb-2">Based on requested quantity:</p>
                <div id="costCalculation">
                    <p class="text-muted mb-0">Enter quantity to see cost</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('quantity_requested')?.addEventListener('input', function() {
        const quantity = parseInt(this.value) || 0;
        const unitPrice = {{ $resource->price }};
        const total = quantity * unitPrice;
        
        const calculationDiv = document.getElementById('costCalculation');
        if (quantity > 0) {
            calculationDiv.innerHTML = `
                <div class="d-flex justify-content-between mb-2">
                    <span class="small">Quantity:</span>
                    <strong class="small">${quantity} {{ $resource->unit }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small">Unit Price:</span>
                    <strong class="small">₦${unitPrice.toLocaleString()}</strong>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between">
                    <span><strong>Total You Pay:</strong></span>
                    <strong class="text-primary">₦${total.toLocaleString()}</strong>
                </div>
            `;
        } else {
            calculationDiv.innerHTML = '<p class="text-muted mb-0">Enter quantity to see cost</p>';
        }
    });
</script>
@endpush