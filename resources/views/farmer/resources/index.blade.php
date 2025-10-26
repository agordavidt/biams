@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Available Resources</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resources</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Available Resources</p>
                        <h4 class="mb-0">{{ $resources->count() }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-primary">
                            <i class="ri-box-3-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">My Applications</p>
                        <h4 class="mb-0">{{ $myApplications->count() }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-info align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-info">
                            <i class="ri-file-list-3-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Approved</p>
                        <h4 class="mb-0">{{ $myApplications->where('status', 'approved')->count() }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-success align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-success">
                            <i class="ri-check-double-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Available Resources -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Available Resources</h4>

                @if($resources->count() > 0)
                <div class="row">
                    @foreach($resources as $resource)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $resource->name }}</h5>
                                        <p class="text-muted mb-0 small">{{ $resource->vendor->legal_name }}</p>
                                    </div>
                                    <span class="badge badge-soft-success">Active</span>
                                </div>

                                <p class="card-text small">{{ Str::limit($resource->description, 100) }}</p>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Type:</span>
                                        <strong class="small">{{ ucfirst($resource->type) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Unit:</span>
                                        <strong class="small">{{ $resource->unit }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Co-Payment:</span>
                                        <strong class="text-primary small">₦{{ number_format($resource->price, 2) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Max Per Farmer:</span>
                                        <strong class="small">{{ $resource->max_per_farmer }} {{ $resource->unit }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted small">Available:</span>
                                        <strong class="text-success small">{{ number_format($resource->available_stock) }} {{ $resource->unit }}</strong>
                                    </div>
                                </div>

                                <a href="{{ route('farmer.resources.show', $resource) }}" class="btn btn-primary btn-sm w-100">
                                    <i class="ri-eye-line me-1"></i> View Details & Apply
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="ri-inbox-line display-4 text-muted"></i>
                    <p class="text-muted mt-3">No resources available at the moment. Check back later!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- My Applications -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">My Recent Applications</h4>
                    <a href="{{ route('farmer.resources.my-applications') }}" class="btn btn-sm btn-outline-primary">
                        View All Applications
                    </a>
                </div>

                @if($myApplications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Resource</th>
                                <th>Vendor</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myApplications->take(5) as $application)
                            <tr>
                                <td><strong>{{ $application->resource->name }}</strong></td>
                                <td>{{ $application->resource->vendor->legal_name }}</td>
                                <td>{{ $application->quantity_requested }} {{ $application->resource->unit }}</td>
                                <td>
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
                                    <span class="badge badge-soft-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('farmer.resources.application-details', $application) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="ri-eye-line"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted">You haven't applied for any resources yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection