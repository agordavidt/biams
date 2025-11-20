@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Application Details</h4>
                    <p class="text-muted mb-0">Review and fulfill farmer application</p>
                </div>
                <div>
                    <a href="{{ route('vendor.distribution.resource-applications', $application->resource) }}" class="btn btn-light">
                        Back to Applications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="mb-4 text-center">
                        @php
                            $statusConfig = [
                                'pending' => ['color' => 'warning', 'icon' => 'time-line', 'text' => 'Pending Review'],
                                'payment_pending' => ['color' => 'info', 'icon' => 'money-dollar-circle-line', 'text' => 'Payment Pending'],
                                'paid' => ['color' => 'success', 'icon' => 'checkbox-circle-line', 'text' => 'Payment Verified'],
                                'approved' => ['color' => 'primary', 'icon' => 'check-line', 'text' => 'Approved'],
                                'fulfilled' => ['color' => 'success', 'icon' => 'check-double-line', 'text' => 'Fulfilled'],
                                'rejected' => ['color' => 'danger', 'icon' => 'close-circle-line', 'text' => 'Rejected'],
                            ];
                            $config = $statusConfig[$application->status] ?? ['color' => 'secondary', 'icon' => 'information-line', 'text' => ucfirst($application->status)];
                        @endphp
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-soft-{{ $config['color'] }} text-{{ $config['color'] }} display-4 rounded-circle">
                                <i class="ri-{{ $config['icon'] }}"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">{{ $config['text'] }}</h5>
                    </div>

                    <!-- Farmer Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            Farmer Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th width="120">Name</th>
                                            <td>{{ $application->farmer ? $application->farmer->full_name : $application->user->name }}</td>
                                        </tr>                                        
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $application->user->email }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tbody>
                                        @if($application->farmer)
                                            <tr>
                                                <th>Phone</th>
                                                <td>{{ $application->farmer->phone_number ?? ($application->user->phone ?? 'N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>LGA</th>
                                                <td>{{ $application->farmer->lga['code'] ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIN</th>
                                                <td>{{ $application->farmer->nin ?? 'N/A' }}</td>
                                            </tr>
                                        @endif                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Resource Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            Resource Information
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th width="120">Resource</th>
                                            <td>{{ $application->resource->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td class="text-capitalize">{{ str_replace('_', ' ', $application->resource->type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Unit Price</th>
                                            <td>₦{{ number_format($application->unit_price, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if($application->resource->requires_quantity)
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <th width="140">Qty Requested</th>
                                                <td>{{ $application->quantity_requested }} {{ $application->resource->unit }}</td>
                                            </tr>
                                            @if($application->quantity_approved)
                                                <tr>
                                                    <th>Qty Approved</th>
                                                    <td class="text-success fw-bold">{{ $application->quantity_approved }} {{ $application->resource->unit }}</td>
                                                </tr>
                                            @endif
                                            @if($application->quantity_fulfilled)
                                                <tr>
                                                    <th>Qty Fulfilled</th>
                                                    <td class="text-info fw-bold">{{ $application->quantity_fulfilled }} {{ $application->resource->unit }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if($application->resource->requires_payment && $application->payment)
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                Payment Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3">Payment Status</h6>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm">
                                                        <span class="avatar-title bg-soft-success text-success rounded">
                                                            <i class="ri-check-line font-size-18"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-1">₦{{ number_format($application->payment->transAmount, 2) }}</h5>
                                                    <p class="text-muted mb-0">Payment Verified</p>
                                                </div>
                                            </div>
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th width="100">Reference</th>
                                                        <td><code>{{ $application->payment_reference }}</code></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <td>{{ $application->paid_at ? $application->paid_at->format('M d, Y h:i A') : 'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3">Amount Breakdown</h6>
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th>Unit Price</th>
                                                        <td class="text-end">₦{{ number_format($application->unit_price, 2) }}</td>
                                                    </tr>
                                                    @if($application->resource->requires_quantity)
                                                        <tr>
                                                            <th>Quantity</th>
                                                            <td class="text-end">{{ $application->quantity_requested }} {{ $application->resource->unit }}</td>
                                                        </tr>
                                                    @endif
                                                    <tr class="table-active">
                                                        <th>Total Amount</th>
                                                        <th class="text-end">₦{{ number_format($application->amount_paid ?? ($application->unit_price * $application->quantity_requested), 2) }}</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($application->admin_notes || $application->fulfillment_notes)
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                Notes
                            </h5>
                            @if($application->admin_notes)
                                <div class="alert alert-info">
                                    <h6>Vendor Notes</h6>
                                    <p class="mb-0">{{ $application->admin_notes }}</p>
                                </div>
                            @endif
                            @if($application->fulfillment_notes)
                                <div class="alert alert-success">
                                    <h6>Fulfillment Notes</h6>
                                    <p class="mb-0">{{ $application->fulfillment_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            @if(in_array($application->status, ['paid', 'approved']))
                <div class="card">
                    <div class="card-header" style="background: #ccc;">
                        <h5 class="card-title mb-0">
                           Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn w-100" onclick="showFulfillModal()" style="background: #66bb6a; color: #fff;">
                            Mark as Fulfilled
                        </button>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <strong>Submitted:</strong> {{ $application->created_at->format('M d, Y h:i A') }}
                        </li>
                        @if($application->paid_at)
                            <li class="mb-2">
                                <strong>Paid:</strong> {{ $application->paid_at->format('M d, Y h:i A') }}
                            </li>
                        @endif
                        @if($application->reviewed_at)
                            <li class="mb-2">
                                <strong>{{ ucfirst($application->status) }}:</strong> {{ $application->reviewed_at->format('M d, Y h:i A') }}
                            </li>
                        @endif
                        @if($application->fulfilled_at)
                            <li class="mb-2">
                                <strong>Delivered:</strong> {{ $application->fulfilled_at->format('M d, Y h:i A') }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fulfill Modal -->
<div class="modal fade" id="fulfillModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: #66bb6a;">
                <h5 class="modal-title">Confirm Fulfillment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vendor.distribution.mark-fulfilled', $application) }}" method="POST" onsubmit="return confirmFulfill(event)">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        Confirm that you have delivered this resource to the farmer.
                    </div>
                    
                    @if($application->resource->requires_quantity)
                        <div class="mb-3">
                            <label class="form-label">Quantity Fulfilled</label>
                            <input type="number" class="form-control" name="quantity_fulfilled" 
                                   value="{{ $application->quantity_paid ?? $application->quantity_approved }}" 
                                   min="1" max="{{ $application->quantity_paid ?? $application->quantity_approved }}" required>
                            <small class="text-muted">Max: {{ $application->quantity_paid ?? $application->quantity_approved }} {{ $application->resource->unit }}</small>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">Fulfillment Notes (Optional)</label>
                        <textarea class="form-control" name="fulfillment_notes" rows="2" 
                                  placeholder="Add delivery notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" id="fulfillBtn" style="background: #66bb6a; color: #fff;">
                        Confirm Fulfillment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showFulfillModal() {
    const modal = new bootstrap.Modal(document.getElementById('fulfillModal'));
    modal.show();
}

function confirmFulfill(event) {
    const btn = document.getElementById('fulfillBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
    return true;
}
</script>
@endpush

@endsection