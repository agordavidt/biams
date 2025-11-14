@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Propose New Resource</h4>
                <p class="text-muted">Submit a resource proposal for State Admin review</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    <!-- Information Alert -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading"><i class="ri-information-line me-2"></i>Proposal Process</h5>
                        <p class="mb-0">
                            1. Submit your resource details with your pricing<br>
                            2. State Admin will review your proposal<br>
                            3. Admin will set the subsidized price for farmers<br>
                            4. Once approved and published, farmers can apply<br>
                            5. You will receive full reimbursement from the Ministry
                        </p>
                    </div>

                    <form action="{{ route('vendor.resources.store') }}" method="POST" x-data="vendorResourceForm()">
                        @csrf
                        
                        <!-- Basic Information -->
                        <h5 class="mb-3">Resource Information</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Resource Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Resource Type *</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        name="type" x-model="resourceType" required>
                                    <option value="">Select Type</option>
                                    <option value="seed">Seeds</option>
                                    <option value="fertilizer">Fertilizer</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="pesticide">Pesticide</option>
                                    <option value="training">Training</option>
                                    <option value="service">Service</option>
                                    <option value="tractor_service">Tractor Service</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      name="description" rows="4" required>{{ old('description') }}</textarea>
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
                                       name="price" step="0.01" min="0" value="{{ old('price') }}" required>
                                <small class="text-muted">Your selling price per unit</small>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="ri-alert-line me-2"></i>
                            <strong>Note:</strong> The State Admin will set the subsidized price for farmers during review. 
                            You will receive your full price as reimbursement from the Ministry.
                        </div>

                        <!-- Stock Management (Only for physical resources) -->
                        <div x-show="requiresQuantity" x-transition>
                            <h5 class="mb-3 mt-4 border-top pt-3">Stock Information</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Unit of Measurement *</label>
                                    <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                           name="unit" value="{{ old('unit') }}"
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
                                           name="total_stock" min="1" value="{{ old('total_stock') }}"
                                           x-bind:required="requiresQuantity">
                                    <small class="text-muted">Total quantity you can supply</small>
                                    @error('total_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Maximum Per Farmer *</label>
                                    <input type="number" class="form-control @error('max_per_farmer') is-invalid @enderror" 
                                           name="max_per_farmer" min="1" value="{{ old('max_per_farmer') }}"
                                           x-bind:required="requiresQuantity">
                                    <small class="text-muted">Max a farmer can request</small>
                                    @error('max_per_farmer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                            <a href="{{ route('vendor.resources.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                Submit Proposal
                            </button>
                        </div>
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
                resourceType: '{{ old("type") }}',

                get requiresQuantity() {
                    return !['service', 'training'].includes(this.resourceType);
                }
            };
        }
    </script>
@endpush