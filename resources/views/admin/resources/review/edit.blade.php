@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Resource Proposal</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.resources.review.index') }}">Resources</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.resources.review.update', $resource) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info">                       
                        <strong>Vendor:</strong> {{ $resource->vendor->legal_name }} | 
                        <strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $resource->status)) }}
                    </div>

                    <h4 class="card-title mb-4">Resource Information</h4>

                    <div class="mb-3">
                        <label for="name" class="form-label">Resource Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $resource->name) }}" required>
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
                                <option value="seed" {{ old('type', $resource->type) === 'seed' ? 'selected' : '' }}>Seed</option>
                                <option value="fertilizer" {{ old('type', $resource->type) === 'fertilizer' ? 'selected' : '' }}>Fertilizer</option>
                                <option value="equipment" {{ old('type', $resource->type) === 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="pesticide" {{ old('type', $resource->type) === 'pesticide' ? 'selected' : '' }}>Pesticide</option>
                                <option value="training" {{ old('type', $resource->type) === 'training' ? 'selected' : '' }}>Training</option>
                                <option value="tractor_service" {{ old('type', $resource->type) === 'tractor_service' ? 'selected' : '' }}>Tractor Service</option>
                                <option value="other" {{ old('type', $resource->type) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="unit" class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                   id="unit" name="unit" value="{{ old('unit', $resource->unit) }}" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" required>{{ old('description', $resource->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h5 class="mb-3">Pricing & Stock</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Farmer Co-Payment (₦) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $resource->price) }}" required>
                            <small class="text-muted">Amount farmer pays per unit</small>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vendor_reimbursement_price" class="form-label">Vendor Reimbursement (₦) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('vendor_reimbursement_price') is-invalid @enderror" 
                                   id="vendor_reimbursement_price" name="vendor_reimbursement_price" 
                                   value="{{ old('vendor_reimbursement_price', $resource->vendor_reimbursement_price) }}" required>
                            <small class="text-muted">Government subsidy per unit</small>
                            @error('vendor_reimbursement_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_stock" class="form-label">Total Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('total_stock') is-invalid @enderror" 
                                   id="total_stock" name="total_stock" value="{{ old('total_stock', $resource->total_stock) }}" required>
                            @error('total_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="max_per_farmer" class="form-label">Maximum Per Farmer <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_per_farmer') is-invalid @enderror" 
                                   id="max_per_farmer" name="max_per_farmer" 
                                   value="{{ old('max_per_farmer', $resource->max_per_farmer) }}" required>
                            @error('max_per_farmer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Admin Notes</h5>
                    <div class="alert alert-warning">                       
                        As State Admin, you can adjust pricing, stock levels, and allocation limits to align with state subsidy policies.
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                             Save Changes
                        </button>
                        <a href="{{ route('admin.resources.review.show', $resource) }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection