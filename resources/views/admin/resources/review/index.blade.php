@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resource Review & Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resource Review</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-2">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Total</p>
                        <h4 class="mb-0">{{ $stats['total'] }}</h4>
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

    <div class="col-md-2">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Proposed</p>
                        <h4 class="mb-0">{{ $stats['proposed'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-warning align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-warning">
                            <i class="ri-time-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Under Review</p>
                        <h4 class="mb-0">{{ $stats['under_review'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-info align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-info">
                            <i class="ri-eye-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Approved</p>
                        <h4 class="mb-0">{{ $stats['approved'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-primary align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-primary">
                            <i class="ri-checkbox-circle-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Active</p>
                        <h4 class="mb-0">{{ $stats['active'] }}</h4>
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

    <div class="col-md-2">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Rejected</p>
                        <h4 class="mb-0">{{ $stats['rejected'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-danger align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-danger">
                            <i class="ri-close-circle-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.review.index', ['status' => 'all']) }}">
                            All Resources
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'proposed' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.review.index', ['status' => 'proposed']) }}">
                            Proposed ({{ $stats['proposed'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'under_review' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.review.index', ['status' => 'under_review']) }}">
                            Under Review ({{ $stats['under_review'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.review.index', ['status' => 'approved']) }}">
                            Approved ({{ $stats['approved'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'active' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.review.index', ['status' => 'active']) }}">
                            Active ({{ $stats['active'] }})
                        </a>
                    </li>
                </ul>

                <div class="table-responsive">
                    <table id="resourcesTable" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Resource Name</th>
                                <th>Vendor</th>
                                <th>Type</th>
                                <th>Co-Payment</th>
                                <th>Reimbursement</th>
                                <th>Stock</th>
                                <th>Max/Farmer</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resources as $resource)
                            <tr>
                                <td><strong>{{ $resource->name }}</strong></td>
                                <td>{{ $resource->vendor->legal_name }}</td>
                                <td>{{ ucfirst($resource->type) }}</td>
                                <td>₦{{ number_format($resource->price, 2) }}</td>
                                <td>₦{{ number_format($resource->vendor_reimbursement_price, 2) }}</td>
                                <td>{{ number_format($resource->total_stock) }}</td>
                                <td>{{ $resource->max_per_farmer }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'proposed' => 'warning',
                                            'under_review' => 'info',
                                            'approved' => 'primary',
                                            'active' => 'success',
                                            'rejected' => 'danger',
                                            'inactive' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge badge-soft-{{ $statusColors[$resource->status] ?? 'secondary' }}">
                                        {{ ucwords(str_replace('_', ' ', $resource->status)) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $resource->created_at->format('M d, Y') }}
                                    @if($resource->reviewedBy)
                                    <br><small class="text-muted">By: {{ $resource->reviewedBy->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.resources.review.show', $resource) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        
                                        @if(in_array($resource->status, ['proposed', 'under_review', 'approved']))
                                        <a href="{{ route('admin.resources.review.edit', $resource) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No resources found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#resourcesTable').DataTable({
            responsive: true,
            order: [[8, 'desc']]
        });
    });
</script>
@endpush