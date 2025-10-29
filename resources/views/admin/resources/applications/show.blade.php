{{-- resources/views/admin/resources/applications/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Application Details (Admin Oversight)</h4>
                    <p class="text-muted mb-0">View and monitor application status</p>
                </div>
                <div>
                    <a href="{{ route('admin.resources.applications.index') }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Back to Applications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ri-check-line me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="ri-alert-line me-1"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ri-error-warning-line me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Vendor Resource Warning -->
    @if($application->resource->vendor_id)
        <div class="alert alert-info border-info">
            <div class="d-flex align-items-center">
                <i class="ri-information-line me-3 fs-4"></i>
                <div>
                    <h6 class="mb-1">Vendor Managed Resource</h6>
                    <p class="mb-0">
                        This application is for a vendor-managed resource. The vendor 
                        <strong>{{ $application->resource->vendor->business_name }}</strong> 
                        should approve and fulfill this application. Admin actions should only be used for oversight or escalation.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <!-- Status Overview -->
                    <div class="mb-4 text-center">
                        @php
                            $statusConfig = [
                                'pending' => ['color' => 'warning', 'icon' => 'time-line', 'text' => 'Pending Review'],
                                'payment_pending' => ['color' => 'info', 'icon' => 'money-dollar-circle-line', 'text' => 'Payment Pending'],
                                'paid' => ['color' => 'success', 'icon' => 'checkbox-circle-line', 'text' => 'Payment Verified'],
                                'approved' => ['color' => 'primary', 'icon' => 'check-line', 'text' => 'Approved'],
                                'fulfilled' => ['color' => 'success', 'icon' => 'check-double-line', 'text' => 'Fulfilled'],
                                'rejected' => ['color' => 'danger', 'icon' => 'close-circle-line', 'text' => 'Rejected'],
                                'cancelled' => ['color' => 'secondary', 'icon' => 'close-line', 'text' => 'Cancelled'],
                            ];
                            $config = $statusConfig[$application->status] ?? ['color' => 'secondary', 'icon' => 'information-line', 'text' => ucfirst($application->status)];
                        @endphp
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-soft-{{ $config['color'] }} text-{{ $config['color'] }} display-4 rounded-circle">
                                <i class="ri-{{ $config['icon'] }}"></i>
                            </div>
                        </div>
                        <h5 class="mb-2">{{ $config['text'] }}</h5>
                        <p class="text-muted">Application #{{ $application->id }}</p>
                    </div>

                    <!-- User & Resource Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="ri-user-line me-2"></i>Farmer Information
                            </h5>
                            <div class="table-responsive">
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
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{ $application->farmer ? $application->farmer->phone_number : ($application->user->phone ?? 'N/A') }}</td>
                                        </tr>
                                        @if($application->farmer && $application->farmer->nin)
                                            <tr>
                                                <th>NIN</th>
                                                <td>{{ $application->farmer->nin }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Submitted</th>
                                            <td>{{ $application->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="ri-box-3-line me-2"></i>Resource Information
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th width="120">Name</th>
                                            <td>{{ $application->resource->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td class="text-capitalize">{{ str_replace('_', ' ', $application->resource->type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Provider</th>
                                            <td>
                                                @if($application->resource->vendor)
                                                    <strong>{{ $application->resource->vendor->business_name }}</strong>
                                                    <span class="badge bg-info ms-1">Vendor</span>
                                                @else
                                                    Ministry of Agriculture
                                                    <span class="badge bg-primary ms-1">Ministry</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Unit Price</th>
                                            <td>₦{{ number_format($application->unit_price, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity & Payment Info -->
                    @if($application->resource->requires_quantity || $application->resource->requires_payment)
                    <div class="row mb-4">
                        @if($application->resource->requires_quantity)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Quantity Information</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="140">Requested</th>
                                            <td>{{ $application->quantity_requested }} {{ $application->resource->unit }}</td>
                                        </tr>
                                        @if($application->quantity_approved)
                                        <tr>
                                            <th>Approved</th>
                                            <td class="text-success fw-bold">{{ $application->quantity_approved }} {{ $application->resource->unit }}</td>
                                        </tr>
                                        @endif
                                        @if($application->quantity_paid)
                                        <tr>
                                            <th>Paid For</th>
                                            <td>{{ $application->quantity_paid }} {{ $application->resource->unit }}</td>
                                        </tr>
                                        @endif
                                        @if($application->quantity_fulfilled)
                                        <tr>
                                            <th>Fulfilled</th>
                                            <td class="text-info fw-bold">{{ $application->quantity_fulfilled }} {{ $application->resource->unit }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        @if($application->resource->requires_payment)
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Payment Information</h6>
                            <div class="card border">
                                <div class="card-body">
                                    @if($application->payment && $paymentVerified)
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm">
                                                    <span class="avatar-title bg-soft-success text-success rounded">
                                                        <i class="ri-check-line font-size-18"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="mb-1">₦{{ number_format($application->amount_paid, 2) }}</h5>
                                                <p class="text-success mb-0">Payment Verified</p>
                                            </div>
                                        </div>
                                        <table class="table table-sm mb-0">
                                            <tbody>
                                                <tr>
                                                    <th width="100">Reference</th>
                                                    <td><code>{{ $application->payment_reference }}</code></td>
                                                </tr>
                                                <tr>
                                                    <th>Date</th>
                                                    <td>{{ $application->paid_at->format('M d, Y h:i A') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        <span class="badge bg-success">{{ ucfirst($application->payment_status) }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="ri-alert-line me-2"></i>
                                            Payment {{ $application->payment_reference ? 'pending verification' : 'not completed' }}
                                        </div>
                                        @if($application->payment_reference)
                                            <p class="mb-0 mt-2">
                                                <strong>Reference:</strong> <code>{{ $application->payment_reference }}</code>
                                            </p>
                                        @endif
                                    @endif
                                    
                                    @if($application->payment_reference)
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" 
                                                onclick="verifyPayment({{ $application->id }})">
                                            <i class="ri-refresh-line me-1"></i> Verify Payment Status
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Application Details -->
                    @if($application->form_data && count($application->form_data) > 0)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2 mb-3">
                            <i class="ri-file-text-line me-2"></i>Application Form Data
                        </h5>
                        
                        @foreach($application->form_data as $key => $value)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</h6>
                                
                                @if(is_array($value) && isset($value['path']))
                                    @php 
                                        $filePath = $value['path'];
                                        $fileName = $value['original_name'] ?? basename($filePath);
                                        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        $isPdf = strtolower($extension) === 'pdf';
                                        $fileUrl = asset('storage/' . $filePath);
                                    @endphp
                                    
                                    <div class="border p-3 rounded bg-light">
                                        <p class="mb-2">
                                            <i class="ri-file-{{ $isImage ? 'image' : ($isPdf ? 'pdf' : 'text') }}-line me-1"></i>
                                            <strong>{{ $fileName }}</strong>
                                        </p>
                                        
                                        @if($isImage)
                                            <div class="mb-3 text-center">
                                                <img src="{{ $fileUrl }}" alt="{{ $fileName }}" 
                                                    class="img-fluid border rounded" style="max-height: 400px;">
                                            </div>
                                        @elseif($isPdf)
                                            <div class="mb-3">
                                                <iframe src="{{ $fileUrl }}" class="w-100 border rounded" 
                                                    style="height: 500px;" title="{{ $fileName }}"></iframe>
                                            </div>
                                        @endif
                                        
                                        <div class="text-end">
                                            <a href="{{ $fileUrl }}" class="btn btn-sm btn-primary" download="{{ $fileName }}">
                                                <i class="ri-download-line me-1"></i> Download
                                            </a>
                                            <a href="{{ $fileUrl }}" class="btn btn-sm btn-info" target="_blank">
                                                <i class="ri-external-link-line me-1"></i> Open
                                            </a>
                                        </div>
                                    </div>
                                @elseif(is_array($value))
                                    <p class="border-bottom pb-2">{{ implode(', ', $value) }}</p>
                                @else
                                    <p class="border-bottom pb-2">{{ $value ?? 'N/A' }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Notes & Reasons -->
                    @if($application->admin_notes || $application->rejection_reason || $application->fulfillment_notes)
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="ri-sticky-note-line me-2"></i>Notes & Comments
                            </h5>
                            @if($application->admin_notes)
                                <div class="alert alert-info">
                                    <h6>{{ $application->resource->vendor_id ? 'Vendor' : 'Admin' }} Notes</h6>
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

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Admin Actions (with warnings for vendor resources) -->
            @if(in_array($application->status, ['pending', 'payment_pending', 'paid', 'approved']))
                <div class="card">
                    <div class="card-header bg-{{ $application->resource->vendor_id ? 'warning' : 'primary' }} text-white">
                        <h5 class="card-title mb-0">
                            <i class="ri-shield-check-line me-2"></i>Admin Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($application->resource->vendor_id)
                            <div class="alert alert-warning">
                                <i class="ri-alert-line me-2"></i>
                                <strong>Caution:</strong> This is a vendor resource. Only use admin actions for emergency intervention.
                            </div>
                        @endif

                        @if(in_array($application->status, ['pending', 'payment_pending']))
                            <form action="{{ route('admin.resources.applications.approve', $application) }}" method="POST" class="mb-3">
                                @csrf
                                @if($application->resource->requires_quantity)
                                    <div class="mb-3">
                                        <label class="form-label">Quantity to Approve</label>
                                        <input type="number" name="quantity_approved" class="form-control" 
                                            value="{{ $application->quantity_requested }}" 
                                            min="1" max="{{ $application->quantity_requested }}" required>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="form-label">Admin Notes</label>
                                    <textarea name="admin_notes" rows="2" class="form-control" 
                                        placeholder="Reason for admin approval..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100"
                                        onclick="return confirm('Are you sure you want to approve this {{ $application->resource->vendor_id ? 'vendor' : '' }} application?')">
                                    <i class="ri-check-line me-1"></i> Admin Override: Approve
                                </button>
                            </form>
                        @endif

                        @if(in_array($application->status, ['paid', 'approved']))
                            <form action="{{ route('admin.resources.applications.fulfill', $application) }}" method="POST" class="mb-3">
                                @csrf
                                @if($application->resource->requires_quantity)
                                    <div class="mb-3">
                                        <label class="form-label">Quantity Fulfilled</label>
                                        <input type="number" name="quantity_fulfilled" class="form-control" 
                                            value="{{ $application->quantity_paid ?? $application->quantity_approved }}" 
                                            min="1" max="{{ $application->quantity_paid ?? $application->quantity_approved }}" required>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="form-label">Fulfillment Notes</label>
                                    <textarea name="fulfillment_notes" rows="2" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100"
                                        onclick="return confirm('Are you sure you want to mark this as fulfilled?')">
                                    <i class="ri-checkbox-circle-line me-1"></i> Admin Override: Fulfill
                                </button>
                            </form>
                        @endif

                        @if(in_array($application->status, ['pending', 'payment_pending']))
                            <form action="{{ route('admin.resources.applications.reject', $application) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea name="rejection_reason" rows="3" class="form-control" 
                                        placeholder="Provide clear reason for rejection..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100"
                                        onclick="return confirm('Are you sure you want to reject this application?')">
                                    <i class="ri-close-line me-1"></i> Reject Application
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-time-line me-2"></i>Application Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Application Submitted</h6>
                                <p class="text-muted mb-0">{{ $application->created_at->format('M d, Y h:i A') }}</p>
                                <small class="text-muted">By: {{ $application->user->name }}</small>
                            </div>
                        </div>

                        @if($application->paid_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Payment Completed</h6>
                                <p class="text-muted mb-0">{{ $application->paid_at->format('M d, Y h:i A') }}</p>
                                <small class="text-muted">Amount: ₦{{ number_format($application->amount_paid, 2) }}</small>
                            </div>
                        </div>
                        @endif

                        @if($application->reviewed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $application->status === 'rejected' ? 'danger' : 'info' }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $application->status === 'rejected' ? 'Rejected' : 'Approved' }}</h6>
                                <p class="text-muted mb-0">{{ $application->reviewed_at->format('M d, Y h:i A') }}</p>
                                @if($application->reviewedBy)
                                <small class="text-muted">By: {{ $application->reviewedBy->name }}
                                    @if($application->resource->vendor_id)
                                        (Vendor)
                                    @else
                                        (Admin)
                                    @endif
                                </small>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($application->fulfilled_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Resource Delivered</h6>
                                <p class="text-muted mb-0">{{ $application->fulfilled_at->format('M d, Y h:i A') }}</p>
                                @if($application->fulfilledBy)
                                <small class="text-muted">By: {{ $application->fulfilledBy->name }}</small>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Processing Time</small>
                        <strong>
                            @if($application->reviewed_at)
                                {{ $application->created_at->diffForHumans($application->reviewed_at, true) }}
                            @else
                                Pending
                            @endif
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Time</small>
                        <strong>
                            @if($application->fulfilled_at)
                                {{ $application->created_at->diffForHumans($application->fulfilled_at, true) }}
                            @else
                                {{ $application->created_at->diffForHumans(now(), true) }}
                            @endif
                        </strong>
                    </div>
                    @if($application->amount_paid)
                        <div>
                            <small class="text-muted d-block">Total Value</small>
                            <strong class="text-success">₦{{ number_format($application->amount_paid, 2) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
async function verifyPayment(applicationId) {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Verifying...';
    
    try {
        const response = await fetch(`/admin/resources/applications/${applicationId}/verify-payment`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (data.verified) {
                toastr.success('Payment verified successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                toastr.warning('Payment not yet verified');
            }
        } else {
            toastr.error(data.message || 'Verification failed');
        }
    } catch (error) {
        toastr.error('Error verifying payment');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-refresh-line me-1"></i> Verify Payment Status';
    }
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