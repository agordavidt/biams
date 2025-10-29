@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Vendor Dashboard</h4>
                <p class="text-muted">Welcome back, {{ $vendor->business_name }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total Resources</p>
                            <h3 class="mb-0">{{ $resourceStats['total'] }}</h3>
                            <small class="text-success">
                                <i class="ri-arrow-up-line"></i> {{ $resourceStats['active'] }} active
                            </small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary text-primary rounded">
                                <i class="ri-box-3-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total Applications</p>
                            <h3 class="mb-0">{{ $applicationStats->total ?? 0 }}</h3>
                            <small class="text-info">
                                <i class="ri-file-list-line"></i> All time
                            </small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info text-info rounded">
                                <i class="ri-file-text-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Paid Applications</p>
                            <h3 class="mb-0">{{ $applicationStats->paid ?? 0 }}</h3>
                            <small class="text-success">
                                <i class="ri-checkbox-circle-line"></i> {{ $applicationStats->fulfilled ?? 0 }} fulfilled
                            </small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success text-success rounded">
                                <i class="ri-money-dollar-circle-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total Revenue</p>
                            <h3 class="mb-0">₦{{ number_format($applicationStats->total_revenue ?? 0, 0) }}</h3>
                            <small class="text-primary">
                                <i class="ri-wallet-3-line"></i> All time
                            </small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary text-primary rounded">
                                <i class="ri-line-chart-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="ri-flashlight-line me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('vendor.resources.all-applications') }}" class="btn btn-primary w-100 btn-lg">
                                <i class="ri-file-list-3-line me-2"></i>
                                Manage Applications
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('vendor.resources.create') }}" class="btn btn-success w-100 btn-lg">
                                <i class="ri-add-circle-line me-2"></i>
                                Add New Resource
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('vendor.resources.index') }}" class="btn btn-info w-100 btn-lg">
                                <i class="ri-list-check me-2"></i>
                                My Resources
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('vendor.resources.all-applications', ['status' => 'paid']) }}" class="btn btn-warning w-100 btn-lg">
                                <i class="ri-time-line me-2"></i>
                                Pending Fulfillment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Applications Ready for Fulfillment -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-time-line me-2"></i>Ready for Fulfillment ({{ $readyForFulfillment->count() }})
                        </h5>
                        <a href="{{ route('vendor.resources.all-applications', ['status' => 'paid']) }}" class="btn btn-sm btn-light">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($readyForFulfillment as $app)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $app->farmer ? $app->farmer->full_name : $app->user->name }}
                                    </h6>
                                    <p class="text-muted mb-1">
                                        <i class="ri-box-3-line me-1"></i>
                                        <strong>{{ $app->resource->name }}</strong>
                                    </p>
                                    <small class="text-muted">
                                        <i class="ri-phone-line me-1"></i>
                                        {{ $app->farmer ? $app->farmer->phone_number : $app->user->phone }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    @if($app->resource->requires_quantity)
                                        <p class="mb-1">
                                            <strong>{{ $app->quantity_approved }}</strong> {{ $app->resource->unit }}
                                        </p>
                                    @endif
                                    <p class="mb-2 text-success">
                                        <strong>₦{{ number_format($app->amount_paid, 2) }}</strong>
                                    </p>
                                    <a href="{{ route('vendor.resources.application.show', $app) }}" class="btn btn-sm btn-success">
                                        <i class="ri-check-line me-1"></i> Fulfill
                                    </a>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="ri-time-line me-1"></i>
                                    Paid {{ $app->paid_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="ri-checkbox-circle-line display-4 text-success mb-3"></i>
                            <p class="text-muted mb-0">All caught up! No pending fulfillments.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Applications Needing Action -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-notification-line me-2"></i>Pending Verification ({{ $pendingApplications->count() }})
                        </h5>
                        <a href="{{ route('vendor.resources.all-applications', ['status' => 'pending']) }}" class="btn btn-sm btn-light">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($pendingApplications as $app)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        {{ $app->farmer ? $app->farmer->full_name : $app->user->name }}
                                    </h6>
                                    <p class="text-muted mb-1">
                                        <i class="ri-box-3-line me-1"></i>
                                        <strong>{{ $app->resource->name }}</strong>
                                    </p>
                                    <small class="text-muted">
                                        <i class="ri-mail-line me-1"></i>
                                        {{ $app->user->email }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    @if($app->resource->requires_quantity)
                                        <p class="mb-1">
                                            <strong>{{ $app->quantity_requested }}</strong> {{ $app->resource->unit }}
                                        </p>
                                    @endif
                                    <span class="badge bg-{{ $app->status === 'payment_pending' ? 'warning' : 'info' }} mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                    <br>
                                    <a href="{{ route('vendor.resources.application.show', $app) }}" class="btn btn-sm btn-primary">
                                        <i class="ri-eye-line me-1"></i> Review
                                    </a>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="ri-time-line me-1"></i>
                                    Applied {{ $app->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="ri-inbox-line display-4 text-muted mb-3"></i>
                            <p class="text-muted mb-0">No pending applications at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Resources -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-bar-chart-line me-2"></i>Top Performing Resources
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Resource Name</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Total Applications</th>
                                    <th>Status</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topResources as $resource)
                                    <tr>
                                        <td>
                                            <strong>{{ $resource->name }}</strong>
                                        </td>
                                        <td class="text-capitalize">
                                            <span class="badge bg-light text-dark">
                                                {{ str_replace('_', ' ', $resource->type) }}
                                            </span>
                                        </td>
                                        <td>₦{{ number_format($resource->price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $resource->applications_count }} applications
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'approved' => 'primary',
                                                    'proposed' => 'warning',
                                                    'rejected' => 'danger',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$resource->status] ?? 'secondary' }}">
                                                {{ ucfirst($resource->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($resource->requires_quantity)
                                                <strong>{{ $resource->available_stock }}</strong> / {{ $resource->total_stock }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('vendor.resources.show', $resource) }}" 
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('vendor.resources.applications', $resource) }}" 
                                                   class="btn btn-sm btn-primary" title="Applications">
                                                    <i class="ri-file-list-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="ri-inbox-line display-4 d-block mb-2"></i>
                                            No resources yet. <a href="{{ route('vendor.resources.create') }}">Create your first resource</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resource Status Overview -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-pie-chart-line me-2"></i>Resource Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Active</span>
                            <strong class="text-success">{{ $resourceStats['active'] }}</strong>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $resourceStats['total'] > 0 ? ($resourceStats['active'] / $resourceStats['total']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending Review</span>
                            <strong class="text-warning">{{ $resourceStats['pending_review'] }}</strong>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ $resourceStats['total'] > 0 ? ($resourceStats['pending_review'] / $resourceStats['total']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Approved</span>
                            <strong class="text-primary">{{ $resourceStats['approved'] }}</strong>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $resourceStats['total'] > 0 ? ($resourceStats['approved'] / $resourceStats['total']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Rejected</span>
                            <strong class="text-danger">{{ $resourceStats['rejected'] }}</strong>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" 
                                 style="width: {{ $resourceStats['total'] > 0 ? ($resourceStats['rejected'] / $resourceStats['total']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-2"></i>Quick Tips
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="ri-lightbulb-line me-2"></i>Streamlined Workflow</h6>
                        <ul class="mb-0">
                            <li>You now have direct control over application approvals</li>
                            <li>Verify payments in your account before approving</li>
                            <li>Mark fulfillment immediately after resource delivery</li>
                            <li>Use the search feature at distribution points for quick farmer lookup</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="ri-checkbox-circle-line me-2"></i>Best Practices</h6>
                        <ul class="mb-0">
                            <li>Check pending applications daily</li>
                            <li>Verify farmer identity before distribution</li>
                            <li>Add notes when fulfilling applications</li>
                            <li>Keep your stock levels updated</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection