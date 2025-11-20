@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Apply for Resource: {{ $resource->name }}</h4>
        </div>
    </div>
</div>

@if($resource->requires_payment && !$hasPaid)
<!-- Payment Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Payment Required</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    This resource requires payment before application.
                </div>

                <form action="{{ route('farmer.resources.payment.initiate', $resource) }}" method="POST">
                    @csrf
                    
                    @if($resource->requires_quantity)
                    <div class="mb-3">
                        <label class="form-label">Quantity *</label>
                        <input type="number" class="form-control" name="quantity" id="paymentQuantity"
                               value="1" min="1" max="{{ $resource->max_per_farmer }}" required>
                        <small class="text-muted">Maximum: {{ $resource->max_per_farmer }} {{ $resource->unit }}</small>
                    </div>
                    @endif

                    <div class="card border bg-light mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Unit Price:</small>
                                    <h5 class="mb-0">₦{{ number_format($resource->price, 2) }}</h5>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Total Amount:</small>
                                    <h4 class="mb-0 text-primary" id="paymentTotalAmount">
                                        ₦{{ number_format($resource->price * ($resource->requires_quantity ? 1 : 1), 2) }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="ri-money-naira-circle-line me-1"></i> Proceed to Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@else
<!-- Application Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Application Form</h5>
            </div>
            <div class="card-body">
                @if($resource->requires_payment && $hasPaid)
                <div class="alert alert-success">
                    <i class="ri-checkbox-circle-line me-2"></i>
                    Payment verified! Please complete your application.
                </div>
                @endif

                <form action="{{ route('farmer.resources.submit', $resource) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @if($resource->requires_quantity)
                    <div class="mb-3">
                        <label class="form-label">Quantity Requested *</label>
                        <input type="number" class="form-control" name="quantity_requested" 
                               value="1" min="1" max="{{ $resource->max_per_farmer }}" required>
                        <small class="text-muted">Maximum: {{ $resource->max_per_farmer }} {{ $resource->unit }}</small>
                    </div>
                    @endif

                    <!-- Dynamic Form Fields -->
                    @if($resource->form_fields)
                        @foreach($resource->form_fields as $field)
                        <div class="mb-3">
                            <label class="form-label">
                                {{ $field['label'] }}
                                @if($field['required'])<span class="text-danger">*</span>@endif
                            </label>
                            
                            @if($field['type'] === 'text')
                                <input type="text" class="form-control" 
                                       name="{{ Str::slug($field['label']) }}"
                                       @if($field['required']) required @endif>
                            
                            @elseif($field['type'] === 'number')
                                <input type="number" class="form-control" 
                                       name="{{ Str::slug($field['label']) }}"
                                       @if($field['required']) required @endif>
                            
                            @elseif($field['type'] === 'email')
                                <input type="email" class="form-control" 
                                       name="{{ Str::slug($field['label']) }}"
                                       @if($field['required']) required @endif>
                            
                            @elseif($field['type'] === 'textarea')
                                <textarea class="form-control" rows="3"
                                          name="{{ Str::slug($field['label']) }}"
                                          @if($field['required']) required @endif></textarea>
                            
                            @elseif($field['type'] === 'select' && isset($field['options']))
                                <select class="form-select" 
                                        name="{{ Str::slug($field['label']) }}"
                                        @if($field['required']) required @endif>
                                    <option value="">Select {{ $field['label'] }}</option>
                                    @php
                                        $options = is_array($field['options']) ? $field['options'] : explode(',', $field['options']);
                                    @endphp
                                    @foreach($options as $option)
                                        <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                    @endforeach
                                </select>
                            
                            @elseif($field['type'] === 'file')
                                <input type="file" class="form-control" 
                                       name="{{ Str::slug($field['label']) }}"
                                       @if($field['required']) required @endif
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Accepted formats: PDF, DOC, JPG, PNG (Max: 2MB)</small>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            No additional information required for this resource.
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                           Submit Application
                        </button>
                        <a href="{{ route('farmer.resources.show', $resource) }}" class="btn btn-light">
                           Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    @if($resource->requires_payment && !$hasPaid && $resource->requires_quantity)
    // Real-time cost calculation
    const unitPrice = {{ $resource->price }};
    const quantityInput = document.getElementById('paymentQuantity');
    const totalAmountDisplay = document.getElementById('paymentTotalAmount');
    
    if (quantityInput && totalAmountDisplay) {
        quantityInput.addEventListener('input', function() {
            const quantity = parseInt(this.value) || 1;
            const totalAmount = unitPrice * quantity;
            
            // Format with Nigerian Naira symbol and comma separators
            totalAmountDisplay.textContent = '₦' + totalAmount.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        });
    }
    @endif
</script>
@endpush

@endsection