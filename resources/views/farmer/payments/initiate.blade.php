@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Make Payment</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmer.resources.my-applications') }}">Applications</a></li>
                    <li class="breadcrumb-item active">Payment</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Application Details -->
        <div class="card">
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="ri-check-circle-line me-2"></i>
                    <strong>Congratulations!</strong> Your application has been approved. Please complete payment to proceed with collection.
                </div>

                <h5 class="card-title mb-4">Application Details</h5>

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th style="width: 35%;">Resource:</th>
                                <td><strong>{{ $application->resource->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Vendor:</th>
                                <td>{{ $application->resource->vendor->legal_name }}</td>
                            </tr>
                            <tr>
                                <th>Quantity Approved:</th>
                                <td><strong class="text-primary">{{ $application->quantity_approved }} {{ $application->resource->unit }}</strong></td>
                            </tr>
                            <tr>
                                <th>Unit Price:</th>
                                <td>₦{{ number_format($application->unit_price, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Payment Information</h5>

                <form action="{{ route('farmer.payments.process', $application) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="quantity_to_pay" class="form-label">
                            Quantity to Pay For <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control form-control-lg @error('quantity_to_pay') is-invalid @enderror" 
                               id="quantity_to_pay" 
                               name="quantity_to_pay" 
                               min="1" 
                               max="{{ $application->quantity_approved }}" 
                               value="{{ old('quantity_to_pay', $application->quantity_approved) }}" 
                               required>
                        <small class="text-muted">Maximum: {{ $application->quantity_approved }} {{ $application->resource->unit }}</small>
                        @error('quantity_to_pay')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg @error('payment_method') is-invalid @enderror" 
                                id="payment_method" 
                                name="payment_method" 
                                required>
                            <option value="">Select Payment Method</option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card Payment (Debit/Credit)</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="ussd" {{ old('payment_method') === 'ussd' ? 'selected' : '' }}>USSD</option>
                            <option value="mobile_money" {{ old('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="mb-3">Payment Summary</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Quantity:</span>
                                <strong id="summary_quantity">{{ $application->quantity_approved }} {{ $application->resource->unit }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Unit Price:</span>
                                <strong>₦{{ number_format($application->unit_price, 2) }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0">Total Amount:</h5>
                                <h5 class="text-primary mb-0" id="total_amount">₦{{ number_format($application->total_amount, 2) }}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <h6 class="alert-heading">Important Information</h6>
                        <ul class="mb-0 ps-3">
                            <li>Payment must be completed to proceed with resource collection</li>
                            <li>You can choose to pay for less than the approved quantity</li>
                            <li><strong class="text-danger">Critical:</strong> Once you collect your resources, your entitlement is fully consumed even if you paid for less than the approved quantity</li>
                            <li>This is a one-time collection process</li>
                        </ul>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="agree_payment" required>
                        <label class="form-check-label" for="agree_payment">
                            I understand the payment terms and collection process
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="ri-secure-payment-line me-1"></i> Proceed to Payment
                        </button>
                        <a href="{{ route('farmer.resources.my-applications') }}" class="btn btn-secondary">
                            <i class="ri-close-line me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('quantity_to_pay').addEventListener('input', function() {
        const quantity = parseInt(this.value) || 0;
        const unitPrice = {{ $application->unit_price }};
        const unit = "{{ $application->resource->unit }}";
        const total = quantity * unitPrice;
        
        document.getElementById('summary_quantity').textContent = quantity + ' ' + unit;
        document.getElementById('total_amount').textContent = '₦' + total.toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    });
</script>
@endpush