@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <div>
                <h4 class="page-title mb-1">My Applications</h4>
                <p class="text-muted mb-0">Track your resource applications</p>
            </div>
            <div>
                <a href="{{ route('farmer.resources.index') }}" class="btn btn-primary">
                   Apply for Resources
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Status</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                Pending Review
                            </option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>
                            <option value="payment_pending" {{ request('status') === 'payment_pending' ? 'selected' : '' }}>
                                Payment Pending
                            </option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>
                                Payment Confirmed
                            </option>
                            <option value="fulfilled" {{ request('status') === 'fulfilled' ? 'selected' : '' }}>
                                Fulfilled
                            </option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Applications List -->
<div class="row">
    <div class="col-12">
        @forelse($applications as $application)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Resource Info -->
                        <div class="col-md-4">
                            <h5 class="mb-1">{{ $application->resource->name }}</h5>
                            <p class="text-muted mb-1">
                                <small>
                                    <i class="ri-building-line me-1"></i>
                                    {{ $application->resource->vendor ? $application->resource->vendor->legal_name : 'Ministry of Agriculture' }}
                                </small>
                            </p>
                            <span class="badge bg-light text-dark">
                                {{ ucfirst(str_replace('_', ' ', $application->resource->type)) }}
                            </span>
                        </div>

                        <!-- Quantity & Amount -->
                        <div class="col-md-3">
                            @if($application->resource->requires_quantity)
                                <small class="text-muted d-block">Quantity</small>
                                <p class="mb-1">
                                    <strong>Requested:</strong> {{ $application->quantity_requested }} {{ $application->resource->unit }}
                                </p>
                                @if($application->quantity_approved)
                                    <p class="mb-0 text-success">
                                        <strong>Approved:</strong> {{ $application->quantity_approved }} {{ $application->resource->unit }}
                                    </p>
                                @endif
                            @endif

                            @if($application->resource->requires_payment)
                                <hr class="my-2">
                                <small class="text-muted d-block">Amount</small>
                                <p class="mb-0">
                                    <strong class="text-success">
                                        ₦{{ number_format($application->total_amount ?? ($application->quantity_requested * $application->unit_price), 2) }}
                                    </strong>
                                </p>
                                @if($application->amount_paid)
                                    <small class="text-success">
                                        <i class="ri-check-line me-1"></i>Paid: ₦{{ number_format($application->amount_paid, 2) }}
                                    </small>
                                @endif
                            @endif
                        </div>

                        <!-- Status & Date -->
                        <div class="col-md-3">
                            <div class="mb-2">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-warning',
                                        'approved' => 'bg-primary',
                                        'payment_pending' => 'bg-info',
                                        'paid' => 'bg-success',
                                        'fulfilled' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        'cancelled' => 'bg-secondary'
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$application->status] ?? 'bg-secondary' }} fs-6">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </div>
                            <small class="text-muted d-block">
                                <i class="ri-calendar-line me-1"></i>
                                Applied: {{ $application->created_at->format('M d, Y') }}
                            </small>
                            @if($application->reviewed_at)
                                <small class="text-muted d-block">
                                    <i class="ri-time-line me-1"></i>
                                    Reviewed: {{ $application->reviewed_at->format('M d, Y') }}
                                </small>
                            @endif
                        </div>

                        <!-- Actions -->
                        <!-- <div class="col-md-2 text-end">
                            <a href="{{ route('farmer.resources.applications.show', $application) }}" 
                               class="btn btn-light btn-sm mb-1 w-100">
                                <i class="ri-eye-line me-1"></i> View
                            </a>
                            
                            @if($application->status === 'pending' || $application->status === 'approved')
                                <form action="{{ route('farmer.resources.cancel', $application) }}" 
                                      method="POST" class="d-inline w-100"
                                      onsubmit="return confirm('Are you sure you want to cancel this application?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="ri-close-line me-1"></i> Cancel
                                    </button>
                                </form>
                            @endif
                        </div> -->
                    </div>

                    <!-- Additional Info -->
                    @if($application->admin_notes || $application->rejection_reason)
                        <hr>
                        <div class="alert alert-{{ $application->status === 'rejected' ? 'danger' : 'info' }} mb-0">
                            @if($application->rejection_reason)
                                <strong>Rejection Reason:</strong>
                                <p class="mb-0">{{ $application->rejection_reason }}</p>
                            @elseif($application->admin_notes)
                                <strong>Admin Notes:</strong>
                                <p class="mb-0">{{ $application->admin_notes }}</p>
                            @endif
                        </div>
                    @endif

                    <!-- Payment Reference -->
                    @if($application->payment_reference)
                        <hr>
                        <small class="text-muted">
                            Payment Reference: <code>{{ $application->payment_reference }}</code>
                        </small>
                    @endif
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-soft-primary text-primary display-4 rounded-circle">
                            <i class="ri-file-list-line"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">No Applications Yet</h5>
                    <p class="text-muted mb-4">
                        @if(request('status'))
                            No {{ request('status') }} applications found.
                        @else
                            You haven't applied for any resources yet. Browse available resources to get started.
                        @endif
                    </p>
                    <a href="{{ route('farmer.resources.index') }}" class="btn btn-primary">
                        <i class="ri-plant-line me-1"></i> Browse Resources
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

@if($applications->hasPages())
    <div class="row">
        <div class="col-12">
            {{ $applications->links() }}
        </div>
    </div>
@endif

@endsection