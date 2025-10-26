@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Application Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}">Applications</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Application Information -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="card-title mb-1">{{ $application->resource->name }}</h4>
                        <p class="text-muted mb-0">Application #{{ $application->id }}</p>
                    </div>
                    <div>
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
                        <span class="badge badge-soft-{{ $statusColors[$application->status] ?? 'secondary' }} font-size-14">
                            {{ ucwords(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 35%;">Resource:</th>
                                <td><strong>{{ $application->resource->name }}</strong></td>
                            </tr>
                            <tr>
                                <th scope="row">Vendor:</th>
                                <td>{{ $application->resource->vendor->legal_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Unit Price:</th>
                                <td>₦{{ number_format($application->unit_price, 2) }} per {{ $application->resource->unit }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Quantity Requested:</th>
                                <td><strong class="text-primary">{{ $application->quantity_requested }} {{ $application->resource->unit }}</strong></td>
                            </tr>
                            @if($application->quantity_approved)
                            <tr>
                                <th scope="row">Quantity Approved:</th>
                                <td><strong class="text-success">{{ $application->quantity_approved }} {{ $application->resource->unit }}</strong></td>
                            </tr>
                            @endif
                            @if($application->total_amount)
                            <tr>
                                <th scope="row">Total Amount:</th>
                                <td><strong class="text-info">₦{{ number_format($application->total_amount, 2) }}</strong></td>
                            </tr>
                            @endif
                            @if($application->amount_paid)
                            <tr>
                                <th scope="row">Amount Paid:</th>
                                <td><strong class="text-success">₦{{ number_format($application->amount_paid, 2) }}</strong></td>
                            </tr>
                            @endif
                            @if($application->payment_reference)
                            <tr>
                                <th scope="row">Payment Reference:</th>
                                <td><code>{{ $application->payment_reference }}</code></td>
                            </tr>
                            @endif
                            @if($application->paid_at)
                            <tr>
                                <th scope="row">Paid At:</th>
                                <td>{{ $application->paid_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            @endif
                            @if($application->quantity_fulfilled)
                            <tr>
                                <th scope="row">Quantity Fulfilled:</th>
                                <td><strong class="text-success">{{ $application->quantity_fulfilled }} {{ $application->resource->unit }}</strong></td>
                            </tr>
                            @endif
                            @if($application->fulfilled_at)
                            <tr>
                                <th scope="row">Fulfilled At:</th>
                                <td>{{ $application->fulfilled_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            @endif
                            @if($application->fulfilledBy)
                            <tr>
                                <th scope="row">Fulfilled By:</th>
                                <td>{{ $application->fulfilledBy->name }}</td>
                            </tr>
                            @endif
                            @if($application->fulfillment_notes)
                            <tr>
                                <th scope="row">Fulfillment Notes:</th>
                                <td>{{ $application->fulfillment_notes }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th scope="row">Applied On:</th>
                                <td>{{ $application->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                            @if($application->reviewed_at)
                            <tr>
                                <th scope="row">Reviewed On:</th>
                                <td>
                                    {{ $application->reviewed_at->format('M d, Y h:i A') }}
                                    @if($application->reviewedBy)
                                    <br><small class="text-muted">By: {{ $application->reviewedBy->name }}</small>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @if($application->rejection_reason)
                            <tr>
                                <th scope="row">Rejection Reason:</th>
                                <td>
                                    <div class="alert alert-danger mb-0">
                                        {{ $application->rejection_reason }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Farmer Information -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Farmer Information</h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Name</p>
                        <p class="mb-3"><strong>{{ $application->farmer->first_name }} {{ $application->farmer->middle_name }} {{ $application->farmer->surname }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Farmer ID</p>
                        <p class="mb-3"><code>{{ $application->farmer->farmer_id }}</code></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">NIN</p>
                        <p class="mb-3">{{ $application->farmer->nin }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1">Phone</p>
                        <p class="mb-3">{{ $application->farmer->phone_number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Actions Card -->
        @if($application->status === 'pending')
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Approve Application</h5>

                <form action="{{ route('admin.applications.approve', $application) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="quantity_approved" class="form-label">Quantity to Approve <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('quantity_approved') is-invalid @enderror" 
                               id="quantity_approved" 
                               name="quantity_approved" 
                               min="1" 
                               max="{{ $application->quantity_requested }}" 
                               value="{{ old('quantity_approved', $application->quantity_requested) }}" 
                               required>
                        <small class="text-muted">Max: {{ $application->quantity_requested }} {{ $application->resource->unit }}</small>
                        @error('quantity_approved')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ri-check-line me-1"></i> Approve Application
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="ri-close-line me-1"></i> Reject Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if(in_array($application->status, ['approved', 'paid', 'fulfilled']))
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Application Timeline</h5>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <i class="ri-check-line text-success"></i>
                        <div>
                            <p class="mb-0"><strong>Applied</strong></p>
                            <small class="text-muted">{{ $application->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>

                    @if($application->reviewed_at)
                    <div class="timeline-item">
                        <i class="ri-check-line text-success"></i>
                        <div>
                            <p class="mb-0"><strong>Approved</strong></p>
                            <small class="text-muted">{{ $application->reviewed_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                    @endif

                    @if($application->paid_at)
                    <div class="timeline-item">
                        <i class="ri-check-line text-success"></i>
                        <div>
                            <p class="mb-0"><strong>Paid</strong></p>
                            <small class="text-muted">{{ $application->paid_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                    @endif

                    @if($application->fulfilled_at)
                    <div class="timeline-item">
                        <i class="ri-check-line text-success"></i>
                        <div>
                            <p class="mb-0"><strong>Fulfilled</strong></p>
                            <small class="text-muted">{{ $application->fulfilled_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary w-100">
                    <i class="ri-arrow-left-line me-1"></i> Back to Applications
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.applications.reject', $application) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Reject Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="4" maxlength="500" required 
                                  placeholder="Provide clear feedback to the farmer..."></textarea>
                        <small class="text-muted">Maximum 500 characters</small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="ri-information-line me-2"></i>
                        The farmer will be notified of the rejection with your feedback.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
    display: flex;
    gap: 15px;
    align-items: start;
}
.timeline-item i {
    font-size: 20px;
}
</style>
@endsection