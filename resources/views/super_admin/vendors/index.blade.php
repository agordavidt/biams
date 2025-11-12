@extends('layouts.super_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Vendors Management</h4>
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
                        <p class="text-muted mb-2">Total Vendors</p>
                        <h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
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
                        <p class="text-muted mb-2">Active Vendors</p>
                        <h4 class="mb-0">{{ number_format($stats['active']) }}</h4>
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
                        <p class="text-muted mb-2">Total Resources</p>
                        <h4 class="mb-0">{{ number_format($stats['total_resources']) }}</h4>
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
                        <p class="text-muted mb-2">Active Resources</p>
                        <h4 class="mb-0">{{ number_format($stats['active_resources']) }}</h4>
                    </div>
                </div>
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
                <form method="GET" action="{{ route('super_admin.vendors.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search vendors..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="organization_type" class="form-select">
                                <option value="">All Organizations</option>
                                <option value="private_company" {{ request('organization_type') === 'private_company' ? 'selected' : '' }}>Private Company</option>
                                <option value="cooperative" {{ request('organization_type') === 'cooperative' ? 'selected' : '' }}>Cooperative</option>
                                <option value="ngo" {{ request('organization_type') === 'ngo' ? 'selected' : '' }}>NGO</option>
                                <option value="government_agency" {{ request('organization_type') === 'government_agency' ? 'selected' : '' }}>Government Agency</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('super_admin.vendors.index') }}" class="btn btn-secondary">Reset</a>
                            <a href="{{ route('super_admin.vendors.export', request()->query()) }}" class="btn btn-success">Export</a>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Legal Name</th>
                                <th>Organization Type</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Users</th>
                                <th>Resources</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $vendor)
                            <tr>
                                <td>
                                    <a href="{{ route('super_admin.vendors.show', $vendor) }}">
                                        {{ $vendor->legal_name }}
                                    </a>
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $vendor->organization_type)) }}</td>
                                <td>{{ $vendor->contact_person_name }}</td>
                                <td>{{ $vendor->contact_person_email }}</td>
                                <td>
                                    @if($vendor->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $vendor->users_count }}</td>
                                <td>{{ $vendor->resources_count }}</td>
                                <td>
                                    <a href="{{ route('super_admin.vendors.show', $vendor) }}" class="btn btn-sm btn-primary">View</a>
                                    <form action="{{ route('super_admin.vendors.toggle-status', $vendor) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-warning">
                                            {{ $vendor->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No vendors found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $vendors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection