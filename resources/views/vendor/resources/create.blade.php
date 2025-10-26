@extends('layouts.vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Propose New Resource</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.resources.index') }}">Resources</a></li>
                    <li class="breadcrumb-item active">Propose</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('vendor.resources.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Resource Information</h4>

                    <div class="mb-3">
                        <label for="name" class="form-label">Resource Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="e.g., Certified Maize Seeds (Oba Super 2)" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Resource Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="seed" {{ old('type') === 'seed' ? 'selected' : '' }}>Seed</option>
                                <option value="fertilizer" {{ old('type') === 'fertilizer' ? 'selected' : '' }}>Fertilizer</option>
                                <option value="equipment" {{ old('type') === 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="pesticide" {{ old('type') === 'pesticide' ? 'selected' : '' }}>Pesticide</option>
                                <option value="training" {{ old('type') === 'training' ? 'selected' : '' }}>Training</option>
                                <option value="tractor_service" {{ old('type') === 'tractor_service' ? 'selected' : '' }}>Tractor Service</option>
                                <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="unit" class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                   id="unit" name="unit" value="{{ old('unit') }}" 
                                   placeholder="e.g., kg, bag, piece, hectare" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        <small class="text-muted">Provide detailed information about the resource</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Pricing & Stock Information</h4>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="co_payment_price" class="form-label">Farmer Co-Payment Price (₦) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('co_payment_price') is-invalid @enderror" 
                                   id="co_payment_price" name="co_payment_price" value="{{ old('co_payment_price') }}" required>
                            <small class="text-muted">Amount farmer pays per unit</small>
                            @error('co_payment_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vendor_reimbursement_price" class="form-label">Vendor Reimbursement (₦) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('vendor_reimbursement_price') is-invalid @enderror" 
                                   id="vendor_reimbursement_price" name="vendor_reimbursement_price" value="{{ old('vendor_reimbursement_price') }}" required>
                            <small class="text-muted">Government subsidy per unit</small>
                            @error('vendor_reimbursement_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_stock" class="form-label">Total Stock Available <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('total_stock') is-invalid @enderror" 
                                   id="total_stock" name="total_stock" value="{{ old('total_stock') }}" required>
                            @error('total_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="max_per_farmer" class="form-label">Maximum Per Farmer <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_per_farmer') is-invalid @enderror" 
                                   id="max_per_farmer" name="max_per_farmer" value="{{ old('max_per_farmer') }}" required>
                            <small class="text-muted">Allocation ceiling per farmer</small>
                            @error('max_per_farmer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>Pricing Example:</strong> If co-payment is ₦10,000 and reimbursement is ₦15,000, 
                        the total resource value is ₦25,000 per unit (farmer pays ₦10,000, government subsidizes ₦15,000).
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Submission Guidelines</h5>
                    
                    <div class="mb-3">
                        <h6><i class="ri-check-line text-success me-1"></i> What Happens Next?</h6>
                        <ol class="ps-3">
                            <li>Your proposal is submitted with status "Proposed"</li>
                            <li>State Admin reviews your submission</li>
                            <li>Admin may approve, edit parameters, or reject</li>
                            <li>If approved, resource becomes "Active" for farmers</li>
                            <li>If rejected, you can edit and resubmit</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <h6><i class="ri-lightbulb-line text-warning me-1"></i> Tips</h6>
                        <ul class="ps-3">
                            <li>Provide clear, detailed descriptions</li>
                            <li>Ensure pricing is competitive and accurate</li>
                            <li>Set realistic stock quantities</li>
                            <li>Consider farmer capacity for max allocation</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-send-plane-line me-1"></i> Submit Proposal
                        </button>
                        <a href="{{ route('vendor.resources.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection