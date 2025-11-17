@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resource Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resources</li>
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
                        <p class="text-muted fw-medium mb-2">Ministry</p>
                        <h4 class="mb-0">{{ $stats['ministry'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-info align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-info">
                            <i class="ri-government-line font-size-24"></i>
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
                        <p class="text-muted fw-medium mb-2">Vendor</p>
                        <h4 class="mb-0">{{ $stats['vendor'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-warning align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-warning">
                            <i class="ri-store-3-line font-size-24"></i>
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
                        <p class="text-muted fw-medium mb-2">Pending Review</p>
                        <h4 class="mb-0">{{ $stats['pending_review'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-danger align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-danger">
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
                        <p class="text-muted fw-medium mb-2">Free</p>
                        <h4 class="mb-0">{{ $stats['free'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-secondary align-self-center mini-stat-icon">
                        <span class="avatar-title rounded-circle bg-secondary">
                            <i class="ri-money-dollar-circle-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Bar -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">All Resources</h5>
                        <p class="text-muted mb-0">Manage both Ministry and Vendor resources</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.resources.review.index') }}" class="btn btn-warning me-2">
                            Vendor Review Queue
                            @if($stats['pending_review'] > 0)
                                <span class="badge bg-danger ms-1">{{ $stats['pending_review'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                            Create Resource
                        </a>
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
                        <a class="nav-link {{ $source === 'all' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.index', ['source' => 'all']) }}">
                            All Resources
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $source === 'ministry' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.index', ['source' => 'ministry']) }}">
                            Ministry Resources
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $source === 'vendor' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.index', ['source' => 'vendor']) }}">
                            Vendor Resources
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'active' ? 'active' : '' }}" 
                           href="{{ route('admin.resources.index', ['status' => 'active']) }}">
                            Active Resources
                        </a>
                    </li>
                </ul>

                <!-- @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif -->

                <div class="table-responsive">
                    <table id="resourcesTable" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Resource Name</th>
                                <th>Source</th>
                                <th>Provider</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resources as $resource)
                            <tr>
                                <td>
                                    <strong>{{ $resource->name }}</strong>
                                    @if($resource->is_vendor_resource)
                                        <br><small class="text-muted">Vendor: {{ $resource->vendor->legal_name }}</small>
                                    @else
                                        <br><small class="text-muted">Ministry Resource</small>
                                    @endif
                                </td>
                                <td>
                                    @if($resource->is_vendor_resource)
                                        <span class="badge badge-soft-warning">Vendor</span>
                                    @else
                                        <span class="badge badge-soft-info">Ministry</span>
                                    @endif
                                </td>
                                <td>
                                    @if($resource->is_vendor_resource)
                                        {{ $resource->vendor->legal_name }}
                                    @else
                                        Ministry of Agriculture
                                    @endif
                                </td>
                                <td>{{ ucfirst($resource->type) }}</td>
                                <td>
                                    @if($resource->requires_payment)
                                        <span class="text-success">₦{{ number_format($resource->price, 2) }}</span>
                                        @if($resource->is_vendor_resource && $resource->vendor_reimbursement)
                                            <br><small class="text-muted">Reimb: ₦{{ number_format($resource->vendor_reimbursement, 2) }}</small>
                                        @endif
                                    @else
                                        <span class="text-success">Free</span>
                                    @endif
                                </td>
                                <td>
                                    @if($resource->requires_quantity)
                                        {{ number_format($resource->available_stock) }} / {{ number_format($resource->total_stock) }}
                                        <br><small class="text-muted">{{ $resource->unit }}</small>
                                    @else
                                        <span class="text-muted">Service</span>
                                    @endif
                                </td>
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
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($resource->is_vendor_resource)
                                            <!-- Vendor Resource Actions -->
                                            <a href="{{ route('admin.resources.review.show', $resource) }}" 
                                               class="btn btn-sm btn-info" title="Review">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            
                                            @if(in_array($resource->status, ['proposed', 'under_review', 'approved']))
                                            <a href="{{ route('admin.resources.review.edit', $resource) }}" 
                                               class="btn btn-sm btn-primary" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            @endif
                                        @else
                                            <!-- Ministry Resource Actions -->
                                            <a href="{{ route('admin.resources.edit', $resource) }}" 
                                               class="btn btn-sm btn-primary me-1" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            
                                            @if($resource->applications_count == 0)
                                            <form action="{{ route('admin.resources.destroy', $resource) }}" 
                                                  method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-inbox-line display-4"></i>
                                        <h5 class="mt-2">No resources found</h5>
                                        <p>Get started by creating a ministry resource or reviewing vendor submissions.</p>
                                        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary mt-2">
                                            <i class="ri-add-line me-1"></i> Create Resource
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $resources->firstItem() }} to {{ $resources->lastItem() }} of {{ $resources->total() }} entries
                    </div>
                    {{ $resources->links() }}
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
            order: [[7, 'desc']],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>tip',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search resources...",
            }
        });

        // Delete confirmation
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush