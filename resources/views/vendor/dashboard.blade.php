@extends('layouts.vendor')
@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-1">{{ $vendor->legal_name}}</h4>
            <p class="text-muted mb-0">Welcome back!</p>
        </div>
    </div>

    <!-- Stats Cards (Simplified, 4 columns) -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-1">Total Resources</p>
                            <h4 class="mb-0">{{ $resourceStats['total'] }}</h4>
                            <small class="text-success">{{ $resourceStats['active'] }} active</small>
                        </div>
                        <div class="ms-3">
                            <div class="bg-light text-success rounded p-2">
                                <i class="ri-box-3-line font-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-1">Total Applications</p>
                            <h4 class="mb-0">{{ $applicationStats->total ?? 0 }}</h4>
                            <small class="text-secondary">All time</small>
                        </div>
                        <div class="ms-3">
                            <div class="bg-light text-secondary rounded p-2">
                                <i class="ri-file-list-line font-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-1">Paid Applications</p>
                            <h4 class="mb-0">{{ $applicationStats->paid ?? 0 }}</h4>
                            <small class="text-success">{{ $applicationStats->fulfilled ?? 0 }} fulfilled</small>
                        </div>
                        <div class="ms-3">
                            <div class="bg-light text-success rounded p-2">
                                <i class="ri-money-dollar-circle-line font-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted small mb-1">Total Revenue</p>
                            <h4 class="mb-0">₦{{ number_format($applicationStats->total_revenue ?? 0, 0) }}</h4>
                            <small class="text-secondary">All time</small>
                        </div>
                        <div class="ms-3">
                            <div class="bg-light text-secondary rounded p-2">
                                <i class="ri-wallet-3-line font-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two-Column Section: Fulfillment + Pending -->
    <div class="row">
        <!-- Ready for Fulfillment -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Ready for Fulfillment ({{ $readyForFulfillment->count() }})</h6>
                        <a href="{{ route('vendor.resources.all-applications', ['status' => 'paid']) }}" class="text-muted small">
                            View All →
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($readyForFulfillment as $app)
                        <div class="border-bottom p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $app->farmer ? $app->farmer->full_name : $app->user->name }}</strong><br>
                                    <small class="text-muted">{{ $app->resource->name }}</small>
                                </div>
                                <div class="text-end">
                                    @if($app->resource->requires_quantity)
                                        <small class="d-block">{{ $app->quantity_approved }} {{ $app->resource->unit }}</small>
                                    @endif
                                    <strong class="text-success">₦{{ number_format($app->amount_paid, 0) }}</strong><br>
                                    <a href="{{ route('vendor.resources.application.show', $app) }}" class="btn btn-sm btn-success mt-1">
                                        Fulfill
                                    </a>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Paid {{ $app->paid_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="ri-checkbox-circle-line font-32 d-block mb-2"></i>
                            No pending fulfillments.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Pending Verification ({{ $pendingApplications->count() }})</h6>
                        <a href="{{ route('vendor.resources.all-applications', ['status' => 'pending']) }}" class="text-muted small">
                            View All →
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($pendingApplications as $app)
                        <div class="border-bottom p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $app->farmer ? $app->farmer->full_name : $app->user->name }}</strong><br>
                                    <small class="text-muted">{{ $app->resource->name }}</small>
                                </div>
                                <div class="text-end">
                                    @if($app->resource->requires_quantity)
                                        <small class="d-block">{{ $app->quantity_requested }} {{ $app->resource->unit }}</small>
                                    @endif
                                    <span class="badge bg-warning text-dark small mb-1">
                                        {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                    </span><br>
                                    <a href="{{ route('vendor.resources.application.show', $app) }}" class="btn btn-sm btn-primary mt-1">
                                        Review
                                    </a>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Applied {{ $app->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="ri-inbox-line font-32 d-block mb-2"></i>
                            No pending applications.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Top Resources Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">Top Performing Resources</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Resource</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Applications</th>
                                    <th>Status</th>
                                    <th>Stock</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topResources as $resource)
                                    <tr>
                                        <td><strong>{{ $resource->name }}</strong></td>
                                        <td><small class="text-capitalize text-muted">{{ str_replace('_', ' ', $resource->type) }}</small></td>
                                        <td>₦{{ number_format($resource->price, 0) }}</td>
                                        <td><span class="badge bg-primary small">{{ $resource->applications_count }}</span></td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'approved' => 'primary',
                                                    'proposed' => 'warning',
                                                    'rejected' => 'danger',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$resource->status] ?? 'secondary' }} small">
                                                {{ ucfirst($resource->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($resource->requires_quantity)
                                                {{ $resource->available_stock }} / {{ $resource->total_stock }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('vendor.resources.show', $resource) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('vendor.resources.applications', $resource) }}" class="btn btn-sm btn-outline-secondary" title="Applications">
                                                <i class="ri-file-list-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No resources yet. <a href="{{ route('vendor.resources.create') }}">Create one</a>
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
</div>
@endsection