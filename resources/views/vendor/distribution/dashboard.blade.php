@extends('layouts.vendor')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Distribution Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Distribution</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<!-- Vendor Info Card with Assignment Status -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-success rounded-circle font-size-20">
                                    <i class="ri-truck-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $vendor->legal_name }}</h5>
                            <p class="text-muted mb-0">Distribution Agent Portal</p>
                        </div>
                    </div>
                    
                    {{-- NEW: Assignment Status Badge --}}
                    <div>
                        @php
                            $hasAssignments = auth()->user()->hasResourceAssignments();
                        @endphp
                        
                        @if($hasAssignments)
                            <span>
                                Restricted Access
                            </span>
                            <p class="text-muted mb-0 mt-1">
                                <small>You can only access assigned resources</small>
                            </p>
                        @else
                            <span class="badge bg-success fs-6">
                                Full Access
                            </span>
                            <p class="text-muted mb-0 mt-1">
                                <small>You can access all vendor resources</small>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- NEW: Show Assigned Resources if Restricted --}}
@if($hasAssignments)
<div class="row">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header">
                <h6 class="mb-0">
                    Your Assigned Resources
                </h6>
            </div>
            <div class="card-body">
                <p class="mb-2">You are assigned to fulfill these resources only:</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(auth()->user()->assignedResources as $resource)
                        <span class="badge bg-primary fs-6">
                            <i class="ri-checkbox-circle-line me-1"></i>{{ $resource->name }}
                        </span>
                    @endforeach
                </div>
                <p class="text-muted mb-0 mt-2">
                    <!-- <small>Contact your vendor manager if you need access to additional resources</small> -->
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">
                            {{ $hasAssignments ? 'Your Assigned Resources' : 'All Resources' }}
                        </p>
                        <h4 class="mb-2">{{ $stats['assigned_resources'] }}</h4>
                        @if($hasAssignments)
                            <small class="text-muted">Specific resources assigned to you</small>
                        @else
                            <small class="text-muted">All vendor resources available</small>
                        @endif
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-list-check font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Fulfilled Today</p>
                        <h4 class="mb-2">{{ $stats['fulfilled_today'] }}</h4>
                        <small class="text-muted">Resources delivered today</small>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-check-double-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Pending Fulfillments</p>
                        <h4 class="mb-2">{{ $stats['pending_fulfillments'] }}</h4>
                        <small class="text-muted">Waiting for distribution</small>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-time-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Quick Actions</h4>
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('vendor.distribution.search') }}" class="btn btn-primary btn-block w-100 mb-2">
                             Search Farmer by NIN/ID
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('vendor.distribution.resources') }}" class="btn btn-info btn-block w-100 mb-2">
                            
                            {{ $hasAssignments ? 'View My Resources' : 'View All Resources' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Distribution Instructions</h4>
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading">How to Mark Fulfillments:</h5>
                    <ol class="mb-0">
                        <li>Search for the farmer using their NIN or Farmer ID</li>
                        <li>Verify the farmer's payment status shows "PAID"</li>
                        @if($hasAssignments)
                            <li><strong>Note:</strong> You can only fulfill resources assigned to you</li>
                        @endif
                        <li>Deliver the exact quantity of resources the farmer paid for</li>
                        <li>Click the "FULFILLED" button to complete the transaction</li>
                        <li><strong>Important:</strong> Fulfillment can only be marked once per resource allocation</li>
                    </ol>
                </div>
                
                @if($hasAssignments)
                <div class="alert alert-warning" role="alert">
                    <h6 class="alert-heading">
                        Resource Assignment Notice
                    </h6>
                    <p class="mb-0">
                        You have been assigned specific resources by your vendor manager. 
                        You can only view and fulfill applications for your assigned resources. 
                        If you need access to additional resources, please contact your vendor manager.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection