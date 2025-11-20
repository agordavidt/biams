@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Application Details</h4>
                    <p class="text-muted mb-0">Review and manage farmer application</p>
                </div>
                <div>
                    <a href="{{ route('vendor.resources.all-applications') }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Back to Applications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <!-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-check-line me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif -->

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
                            <i class="ri-user-line me-2"></i>Farmer Information
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
                                                <td>{{ $application->farmer ? $application->farmer->phone_number : ($application->user->phone ?? 'N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>LGA</th>
                                                <td>{{ $application->farmer->lga['code'] ?? 'N/A' }}</td>
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
                            <i class="ri-box-3-line me-2"></i>Resource Information
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
                    @if($application->resource->requires_payment)
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="ri-money-dollar-circle-line me-2"></i>Payment Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="mb-3">Payment Status</h6>
                                            @if($application->payment)
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
                                                        <tr>
                                                            <th>Channel</th>
                                                            <td>{{ $application->payment->channelId ?? 'N/A' }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="ri-alert-line me-2"></i>
                                                    Payment not yet verified
                                                </div>
                                                @if($application->payment_reference)
                                                    <p><strong>Reference:</strong> <code>{{ $application->payment_reference }}</code></p>
                                                @endif
                                            @endif
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

                    <!-- Application Form Data -->
                    @if($application->form_data && count($application->form_data) > 0)
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="ri-file-text-line me-2"></i>Application Form Data
                            </h5>
                            @foreach($application->form_data as $key => $value)
                                <div class="mb-3">
                                    <h6 class="text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}</h6>
                                    @if(is_array($value) && isset($value['path']))
                                        @php
                                            $isImage = in_array(strtolower(pathinfo($value['path'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                                        @endphp
                                        <div class="border p-2 rounded">
                                            @if($isImage)
                                                <img src="{{ asset('storage/' . $value['path']) }}" alt="{{ $value['original_name'] }}" class="img-fluid rounded">
                                            @else
                                                <a href="{{ asset('storage/' . $value['path']) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="ri-download-line me-1"></i>Download {{ $value['original_name'] }}
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <p class="border-bottom pb-2">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Notes & Reasons -->
                    @if($application->admin_notes || $application->rejection_reason || $application->fulfillment_notes)
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="ri-sticky-note-line me-2"></i>Notes
                            </h5>
                            @if($application->admin_notes)
                                <div class="alert alert-info">
                                    <h6>Vendor Notes</h6>
                                    <p class="mb-0">{{ $application->admin_notes }}</p>
                                </div>
                            @endif
                            @if($application->rejection_reason)
                                <div class="alert alert-danger">
                                    <h6>Rejection Reason</h6>
                                    <p class="mb-0">{{ $application->rejection_reason }}</p>
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
            @if(in_array($application->status, ['pending', 'payment_pending', 'paid', 'approved']))
                <div class="card">
                    <div class="card-header" style="background: #ccc;">
                        <h5 class="card-title mb-0">
                           Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(in_array($application->status, ['pending', 'payment_pending']))
                            <!-- Verify & Approve Button -->
                            <button type="button" class="btn btn-success w-100 mb-2" onclick="showApproveModal()">
                                 Verify Payment & Approve
                            </button>
                            
                            <!-- Reject Button -->
                            <button type="button" class="btn btn-danger w-100" onclick="showRejectModal()">
                                Reject Application
                            </button>
                        @endif

                        @if(in_array($application->status, ['paid', 'approved']))
                            <!-- Fulfill Button -->
                            <button type="button" class="btn w-100" onclick="showFulfillModal()" style="background: #66bb6a; color: #fff;">
                                 Mark as Fulfilled
                            </button>
                        @endif
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Verify Payment & Approve</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vendor.resources.application.verify-approve', $application) }}" method="POST" onsubmit="return confirmApprove(event)">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        Please confirm that payment has been received before approving.
                    </div>
                    
                    @if($application->resource->requires_quantity)
                        <div class="mb-3">
                            <label class="form-label">Quantity to Approve</label>
                            <input type="number" class="form-control" name="quantity_approved" 
                                   value="{{ $application->quantity_requested }}" 
                                   min="1" max="{{ $application->quantity_requested }}" required>
                            <small class="text-muted">Max: {{ $application->quantity_requested }} {{ $application->resource->unit }}</small>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" name="admin_notes" rows="2" 
                                  placeholder="Add any notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="approveBtn">
                        <i class="ri-check-line me-1"></i> Approve Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject Application</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vendor.resources.application.reject', $application) }}" method="POST" onsubmit="return confirmReject(event)">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i>
                        This action cannot be undone. Please provide a clear reason.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="rejection_reason" rows="3" 
                                  placeholder="Provide a clear reason for rejection..." required minlength="10"></textarea>
                        <small class="text-muted">Minimum 10 characters</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="rejectBtn">
                        <i class="ri-close-line me-1"></i> Reject Application
                    </button>
                </div>
            </form>
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
            <form action="{{ route('vendor.resources.application.fulfill', $application) }}" method="POST" onsubmit="return confirmFulfill(event)">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ri-alert-line me-2"></i>
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
                    <button type="submit" class="btn" id="fulfillBtn" style="background: #66bb6a;">
                        Confirm Fulfillment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApproveModal() {
    const modal = new bootstrap.Modal(document.getElementById('approveModal'));
    modal.show();
}

function showRejectModal() {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

function showFulfillModal() {
    const modal = new bootstrap.Modal(document.getElementById('fulfillModal'));
    modal.show();
}

function confirmApprove(event) {
    const btn = document.getElementById('approveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
    return true; // Allow form submission
}

function confirmReject(event) {
    const btn = document.getElementById('rejectBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
    return true; // Allow form submission
}

function confirmFulfill(event) {
    const btn = document.getElementById('fulfillBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
    return true; // Allow form submission
}
</script>
@endpush

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    padding-left: 10px;
}
</style>
@endpush
@endsection