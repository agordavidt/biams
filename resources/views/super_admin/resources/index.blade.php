@extends('layouts.super_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resources Management</h4>
            <div>
                <a href="{{ route('super_admin.resources.analytics') }}" class="btn btn-info">Analytics</a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Total Resources</p>
                <h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Ministry</p>
                <h4 class="mb-0">{{ number_format($stats['ministry']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Vendor</p>
                <h4 class="mb-0">{{ number_format($stats['vendor']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Active</p>
                <h4 class="mb-0">{{ number_format($stats['active']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Under Review</p>
                <h4 class="mb-0">{{ number_format($stats['under_review']) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Applications</p>
                <h4 class="mb-0">{{ number_format($stats['total_applications']) }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('super_admin.resources.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search resources..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="source" class="form-select">
                                <option value="">All Sources</option>
                                <option value="ministry" {{ request('source') === 'ministry' ? 'selected' : '' }}>Ministry</option>
                                <option value="vendor" {{ request('source') === 'vendor' ? 'selected' : '' }}>Vendor</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="proposed" {{ request('status') === 'proposed' ? 'selected' : '' }}>Proposed</option>
                                <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="seed" {{ request('type') === 'seed' ? 'selected' : '' }}>Seed</option>
                                <option value="fertilizer" {{ request('type') === 'fertilizer' ? 'selected' : '' }}>Fertilizer</option>
                                <option value="equipment" {{ request('type') === 'equipment' ? 'selected' : '' }}>Equipment</option>
                                <option value="pesticide" {{ request('type') === 'pesticide' ? 'selected' : '' }}>Pesticide</option>
                                <option value="training" {{ request('type') === 'training' ? 'selected' : '' }}>Training</option>
                                <option value="service" {{ request('type') === 'service' ? 'selected' : '' }}>Service</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('super_admin.resources.index') }}" class="btn btn-secondary">Reset</a>
                            <a href="{{ route('super_admin.resources.export', request()->query()) }}" class="btn btn-success">Export</a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Applications</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resources as $resource)
                            <tr>
                                <td>
                                    <a href="{{ route('super_admin.resources.show', $resource) }}">
                                        {{ $resource->name }}
                                    </a>
                                </td>
                                <td>{{ ucfirst($resource->type) }}</td>
                                <td>
                                    @if($resource->vendor_id)
                                        <a href="{{ route('super_admin.vendors.show', $resource->vendor) }}">
                                            {{ $resource->vendor->legal_name }}
                                        </a>
                                    @else
                                        Ministry
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $resource->status === 'active' ? 'success' : ($resource->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                                    </span>
                                </td>
                                <td>{{ $resource->requires_payment ? 'Required' : 'Free' }}</td>
                                <td>{{ $resource->price ? '₦' . number_format($resource->price, 2) : 'Free' }}</td>
                                <td>
                                    @if($resource->requires_quantity)
                                        {{ $resource->available_stock ?? 0 }} / {{ $resource->total_stock ?? 0 }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $resource->applications_count }}</td>
                                <td>
                                    <a href="{{ route('super_admin.resources.show', $resource) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No resources found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $resources->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection