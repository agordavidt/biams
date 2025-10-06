@extends('layouts.admin')

@section('content')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Resource Applications</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Resource Applications</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Resource Statistics Cards -->
    <!-- @if($resourceStats->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3">
                <i class="ri-bar-chart-box-line me-1"></i> 
                Applications Summary by Resource
            </h5>
        </div>
        @foreach($resourceStats as $resource)
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-truncate mb-3" title="{{ $resource->name }}">
                        {{ $resource->name }}
                    </h6>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <h5 class="mb-1 text-success">{{ $resource->approved_count }}</h5>
                            <p class="text-muted mb-0 small">Granted</p>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-1 text-danger">{{ $resource->rejected_count }}</h5>
                            <p class="text-muted mb-0 small">Declined</p>
                        </div>
                    </div>
                    <div class="text-center mt-3 pt-2 border-top">
                        <small class="text-muted">
                            <strong>{{ $resource->total_applications }}</strong> Total | 
                            <strong class="text-warning">{{ $resource->pending_count }}</strong> Pending
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif -->

    <!-- Applications Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form action="{{ route('resources.applications.index') }}" method="GET" class="row mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="form-control" 
                                placeholder="Search by user or resource...">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                @foreach(\App\Models\ResourceApplication::getStatusOptions() as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-filter-2-line align-middle me-1"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('resources.applications.export', request()->all()) }}" 
                                class="btn btn-success w-100">
                                <i class="ri-file-excel-line me-1"></i> Export
                            </a>
                        </div>
                    </form>

                    <!-- Applications Table -->
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Resource</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                    <tr>
                                        <td>#{{ $application->id }}</td>
                                        <td>
                                            <div>{{ $application->user->name }}</div>
                                            <small class="text-muted">{{ $application->user->email }}</small>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $application->resource->name }}">
                                                {{ $application->resource->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $application->status === 'approved' ? 'success' : ($application->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ $application->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($application->resource->requires_payment)
                                                <span class="badge bg-{{ $application->payment_status === 'verified' ? 'success' : ($application->payment_status === 'failed' ? 'danger' : ($application->payment_status === 'paid' ? 'primary' : 'warning')) }}">
                                                    {{ $application->getPaymentStatusLabel() }}
                                                </span>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $application->created_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $application->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('resources.applications.show', $application) }}" 
                                                class="btn btn-sm btn-primary">
                                                <i class="ri-eye-line align-middle"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="ri-inbox-line display-4 text-muted d-block mb-2"></i>
                                            <p class="text-muted mb-0">No applications found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <p class="text-muted mb-0">
                                Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} 
                                of {{ $applications->total() }} applications
                            </p>
                        </div>
                        <div>
                            {{ $applications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection