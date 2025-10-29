@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $resource->name }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmer.resources.index') }}">Resources</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if (session('error'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

@if (session('success'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<div class="row">
    <!-- Resource Details -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Resource Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="card-title mb-3">Description</h5>
                    <p class="text-muted">{{ $resource->description }}</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5 class="card-title mb-3">Basic Information</h5>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                            <i class="ri-file-list-3-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Resource Type</p>
                                    <h6 class="font-size-14 mb-0 text-capitalize">
                                        {{ str_replace('_', ' ', $resource->type) }}
                                    </h6>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                            <i class="ri-money-naira-circle-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Cost</p>
                                    <h6 class="font-size-14 mb-0">
                                        @if($resource->requires_payment)
                                            ₦{{ number_format($resource->price, 2) }} 
                                            <small class="text-muted">per {{ $resource->unit ?? 'unit' }}</small>
                                        @else
                                            <span class="text-success">Free</span>
                                        @endif
                                    </h6>
                                </div>
                            </div>

                            @if($resource->vendor_reimbursement && $resource->requires_payment)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-success">
                                            <i class="ri-government-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Government Subsidy</p>
                                    <h6 class="font-size-14 mb-0 text-success">
                                        ₦{{ number_format($resource->vendor_reimbursement - $resource->price, 2) }}
                                    </h6>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5 class="card-title mb-3">Availability</h5>
                            
                            @if($resource->requires_quantity)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-info">
                                            <i class="ri-stack-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Available Stock</p>
                                    <h6 class="font-size-14 mb-0">
                                        {{ number_format($resource->available_stock) }} {{ $resource->unit }}
                                    </h6>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-warning">
                                            <i class="ri-user-settings-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Maximum Per Farmer</p>
                                    <h6 class="font-size-14 mb-0">
                                        {{ number_format($resource->max_per_farmer) }} {{ $resource->unit }}
                                    </h6>
                                </div>
                            </div>
                            @else
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-info">
                                            <i class="ri-service-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Service Type</p>
                                    <h6 class="font-size-14 mb-0 text-capitalize">
                                        {{ str_replace('_', ' ', $resource->type) }}
                                    </h6>
                                </div>
                            </div>
                            @endif

                            @if($resource->start_date || $resource->end_date)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                            <i class="ri-calendar-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Availability Period</p>
                                    <h6 class="font-size-14 mb-0">
                                        @if($resource->start_date && $resource->end_date)
                                            {{ $resource->start_date->format('M d, Y') }} - {{ $resource->end_date->format('M d, Y') }}
                                        @elseif($resource->start_date)
                                            From {{ $resource->start_date->format('M d, Y') }}
                                        @elseif($resource->end_date)
                                            Until {{ $resource->end_date->format('M d, Y') }}
                                        @else
                                            Ongoing
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Application Status -->
                @if($existingApplication)
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="ri-information-line me-3 fs-16"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Application Submitted</h6>
                                <p class="mb-0">You have already applied for this resource on 
                                    <strong>{{ $existingApplication->created_at->format('M d, Y') }}</strong>. 
                                    Current status: 
                                    <span class="badge bg-{{ $existingApplication->status === 'approved' ? 'success' : ($existingApplication->status === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($existingApplication->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('farmer.resources.track') }}" class="btn btn-info">
                            <i class="ri-file-check-line me-1"></i> Track Application
                        </a>
                        @if($existingApplication->canBeCancelled())
                        <form action="{{ route('farmer.resources.cancel', $existingApplication) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to cancel this application?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="ri-close-line me-1"></i> Cancel Application
                            </button>
                        </form>
                        @endif
                    </div>
                @else
                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 flex-wrap">
                        @if($resource->requires_payment && !$hasPaid)
                            <a href="{{ route('farmer.resources.apply', $resource) }}" 
                               class="btn btn-primary">
                                <i class="ri-money-naira-circle-line me-1"></i> Pay & Apply
                            </a>
                            <a href="{{ route('farmer.resources.apply', $resource) }}" 
                               class="btn btn-outline-primary">
                                <i class="ri-information-line me-1"></i> View Requirements
                            </a>
                        @else
                            <a href="{{ route('farmer.resources.apply', $resource) }}" 
                               class="btn btn-success">
                               Apply Now
                            </a>
                        @endif
                        
                        <a href="{{ route('farmer.resources.index') }}" class="btn btn-light">
                           Back to Resources
                        </a>
                    </div>

                    @if($resource->requires_payment && $hasPaid)
                        <div class="alert alert-success mt-3">
                            <i class="ri-checkbox-circle-line me-2"></i>
                            Payment completed! You can now submit your application.
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Additional Information -->
        @if($resource->vendor || $resource->partner)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Provider Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($resource->vendor)
                    <div class="col-md-6">
                        <h6 class="mb-3">Vendor</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $resource->vendor->legal_name }}</p>
                        <p class="mb-1"><strong>Contact:</strong> {{ $resource->vendor->phone_number ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Email:</strong> {{ $resource->vendor->email ?? 'N/A' }}</p>
                    </div>
                    @endif
                    
                    @if($resource->partner)
                    <div class="col-md-6">
                        <h6 class="mb-3">Partner Organization</h6>
                        <p class="mb-1"><strong>Name:</strong> {{ $resource->partner->legal_name }}</p>
                        <p class="mb-0"><strong>Type:</strong> {{ $resource->partner->organization_type ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-xl-4">
        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Info</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-soft-{{ $resource->requires_payment ? 'warning' : 'success' }} text-{{ $resource->requires_payment ? 'warning' : 'success' }} display-4 rounded-circle">
                            <i class="ri-{{ $resource->requires_payment ? 'money-naira-circle-line' : 'gift-line' }}"></i>
                        </div>
                    </div>
                    <h5 class="mb-1">{{ $resource->requires_payment ? 'Paid Resource' : 'Free Resource' }}</h5>
                    <p class="text-muted">Available for Application</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-checkbox-circle-line me-2 text-success"></i>Status
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-success">Active</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-user-line me-2"></i>Applications
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ $resource->applications_count ?? 0 }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-time-line me-2"></i>Updated
                                </td>
                                <td class="text-end">
                                    {{ $resource->updated_at->diffForHumans() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush