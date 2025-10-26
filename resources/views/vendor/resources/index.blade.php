@extends('layouts.vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resource Proposals</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resources</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">My Resource Proposals</h4>
                    <a href="{{ route('vendor.resources.create') }}" class="btn btn-primary">
                        <i class="ri-add-circle-line me-1"></i> Propose New Resource
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="resourcesTable" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Resource Name</th>
                                <th>Type</th>
                                <th>Co-Payment Price</th>
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
                                <td>{{ ucfirst($resource->type) }}</td>
                                <td>₦{{ number_format($resource->price, 2) }}</td>
                                <td>₦{{ number_format($resource->vendor_reimbursement_price, 2) }}</td>
                                <td>
                                    <span class="badge badge-soft-info">{{ number_format($resource->total_stock) }}</span>
                                </td>
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
                                <td>{{ $resource->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('vendor.resources.show', $resource) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        
                                        @if(in_array($resource->status, ['proposed', 'rejected']))
                                        <a href="{{ route('vendor.resources.edit', $resource) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $resource->id }})"
                                                title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        
                                        <form id="delete-form-{{ $resource->id }}" 
                                              action="{{ route('vendor.resources.destroy', $resource) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No resource proposals yet. Create your first proposal!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Legend -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Status Guide</h5>
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-2"><span class="badge badge-soft-warning">Proposed</span> - Awaiting State Admin review</p>
                        <p class="mb-2"><span class="badge badge-soft-info">Under Review</span> - Being reviewed by State Admin</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-2"><span class="badge badge-soft-primary">Approved</span> - Approved but not yet published</p>
                        <p class="mb-2"><span class="badge badge-soft-success">Active</span> - Published and available to farmers</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-2"><span class="badge badge-soft-danger">Rejected</span> - Not approved (can be edited and resubmitted)</p>
                        <p class="mb-2"><span class="badge badge-soft-secondary">Inactive</span> - Temporarily disabled</p>
                    </div>
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
            order: [[7, 'desc']]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This resource proposal will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush