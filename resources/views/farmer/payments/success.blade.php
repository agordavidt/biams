@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Payment Successful</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Payment Success</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Success Message -->
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="avatar-xl mx-auto mb-4">
                    <span class="avatar-title bg-success rounded-circle font-size-24">
                        <i class="ri-check-line display-4"></i>
                    </span>
                </div>
                
                <h3 class="mb-3 text-success">Payment Successful!</h3>
                <p class="text-muted mb-4">Your payment has been processed successfully. Your resource is now ready for collection.</p>
                
                <div class="alert alert-success text-start">
                    <h5 class="alert-heading">Next Steps:</h5>
                    <ol class="mb-0 ps-3">
                        <li>Visit the collection point at your convenience</li>
                        <li>Bring your Farmer ID or NIN for verification</li>
                        <li>Present this payment reference to the distribution agent</li>
                        <li>Collect your resources</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Payment Details</h5>

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th style="width: 35%;">Payment Reference:</th>
                                <td><code class="font-size-16">{{ $application->payment_reference }}</code></td>
                            </tr>
                            <tr>
                                <th>Resource:</th>
                                <td><strong>{{ $application->resource->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Vendor:</th>
                                <td>{{ $application->resource->vendor->legal_name }}</td>
                            </tr>
                            <tr>
                                <th>Quantity Paid:</th>
                                <td><strong class="text-primary">{{ $application->quantity_paid }} {{ $application->resource->unit }}</strong></td>
                            </tr>
                            <tr>
                                <th>Amount Paid:</th>
                                <td><strong class="text-success">₦{{ number_format($application->amount_paid, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Payment Date:</th>
                                <td>{{ $application->paid_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><span class="badge badge-soft-primary">Paid - Ready for Collection</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Collection Information -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Collection Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Vendor Name</p>
                        <p class="mb-3"><strong>{{ $application->resource->vendor->legal_name }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Contact Person</p>
                        <p class="mb-3">{{ $application->resource->vendor->contact_person_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Phone</p>
                        <p class="mb-3">{{ $application->resource->vendor->contact_person_phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Address</p>
                        <p class="mb-3">{{ $application->resource->vendor->address }}</p>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="ri-error-warning-line me-2"></i>
                    <strong>Important Reminder:</strong> Collection is a one-time process. Once you collect your resources, your entitlement for this resource is fully consumed, even if you paid for less than the maximum allocation.
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('farmer.resources.my-applications') }}" class="btn btn-primary">
                <i class="ri-file-list-3-line me-1"></i> View My Applications
            </a>
            <a href="{{ route('farmer.resources.index') }}" class="btn btn-outline-primary">
                <i class="ri-box-3-line me-1"></i> Browse More Resources
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary">
                <i class="ri-printer-line me-1"></i> Print Receipt
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    .page-title-box, .btn, .alert-warning {
        display: none;
    }
    .card {
        border: 1px solid #000;
        box-shadow: none;
    }
}
</style>
@endsection