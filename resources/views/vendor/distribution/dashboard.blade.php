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

<!-- Vendor Info Card -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
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
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Assigned Resources</p>
                        <h4 class="mb-2">{{ $stats['assigned_resources'] }}</h4>
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
                            <i class="ri-search-line me-1"></i> Search Farmer by NIN/ID
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('vendor.distribution.resources') }}" class="btn btn-info btn-block w-100 mb-2">
                            <i class="ri-list-check me-1"></i> View Assigned Resources
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
                        <li>Deliver the exact quantity of resources the farmer paid for</li>
                        <li>Click the "FULFILLED" button to complete the transaction</li>
                        <li><strong>Important:</strong> Fulfillment can only be marked once per resource allocation</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection