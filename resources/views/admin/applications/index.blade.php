@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resource Applications Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Applications</li>
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
                    <div class="avatar-sm rounded-circle bg-primary align-self-center">
                        <span class="avatar-title rounded-circle bg-primary">
                            <i class="ri-file-list-3-line font-size-24"></i>
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
                        <p class="text-muted fw-medium mb-2">Pending</p>
                        <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-warning align-self-center">
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
                        <p class="text-muted fw-medium mb-2">Approved</p>
                        <h4 class="mb-0">{{ $stats['approved'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-success align-self-center">
                        <span class="avatar-title rounded-circle bg-success">
                            <i class="ri-check-line font-size-24"></i>
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
                        <p class="text-muted fw-medium mb-2">Paid</p>
                        <h4 class="mb-0">{{ $stats['paid'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-primary align-self-center">
                        <span class="avatar-title rounded-circle bg-primary">
                            <i class="ri-money-dollar-circle-line font-size-24"></i>
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
                        <p class="text-muted fw-medium mb-2">Fulfilled</p>
                        <h4 class="mb-0">{{ $stats['fulfilled'] }}</h4>
                    </div>
                    <div class="avatar-sm rounded-circle bg-success align-self-center">
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
                    <div class="avatar-sm rounded-circle bg-danger align-self-center">
                        <span class="avatar-title rounded-circle bg-danger">
                            <i class="ri-close-line font-size-24"></i>
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
                           href="{{ route('admin.applications.index', ['status' => 'all']) }}">
                            All Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
                           href="{{ route('admin.applications.index', ['status' => 'pending']) }}">
                            Pending ({{ $stats['pending'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
                           href="{{ route('admin.applications.index', ['status' => 'approved']) }}">
                            Approved ({{ $stats['approved'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'paid' ? 'active' : '' }}" 
                           href="{{ route('admin.applications.index', ['status' => 'paid']) }}">
                            Paid ({{ $stats['paid'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'fulfilled' ? 'active' : '' }}" 
                           href="{{ route('admin.applications.index', ['status' => 'fulfilled']) }}">
                            Fulfilled ({{ $stats['fulfilled'] }})
                        </a>
                    </li>
                </ul>

                <div class="table-responsive">
                    <table id="applicationsTable" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Farmer</th>
                                <th>Resource</th>
                                <th>Vendor</th>
                                <th>Qty Requested</th>
                                <th>Qty Approved</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $application)
                            <tr>
                                <td>
                                    @if($application->status === 'pending')
                                    <input type="checkbox" class="application-checkbox" value="{{ $application->id }}">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $application->farmer->full_name }}</strong>
                                    <br><small class="text-muted">{{ $application->farmer->nin }}</small>
                                </td>
                                <td>{{ $application->resource->name }}</td>
                                <td>{{ $application->resource->vendor->legal_name }}</td>
                                <td>{{ $application->quantity_requested }} {{ $application->resource->unit }}</td>
                                <td>
                                    @if($application->quantity_approved)
                                        {{ $application->quantity_approved }} {{ $application->resource->unit }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->total_amount)
                                        <strong>₦{{ number_format($application->total_amount, 2) }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
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
                                    <a href="{{ route('admin.applications.show', $application) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No applications found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($status === 'pending' && $applications->where('status', 'pending')->count() > 0)
                <div class="mt-3">
                    <button type="button" class="btn btn-success" onclick="bulkApprove()">
                        <i class="ri-check-double-line me-1"></i> Approve Selected
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bulk Approve Form -->
<form id="bulkApproveForm" action="{{ route('admin.applications.bulk-approve') }}" method="POST" style="display: none;">
    @csrf
    <div id="bulkApplicationIds"></div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#applicationsTable').DataTable({
            responsive: true,
            order: [[8, 'desc']]
        });

        // Select all checkbox
        $('#selectAll').on('change', function() {
            $('.application-checkbox').prop('checked', $(this).prop('checked'));
        });
    });

    function bulkApprove() {
        const selected = [];
        $('.application-checkbox:checked').each(function() {
            selected.push($(this).val());
        });

        if (selected.length === 0) {
            Swal.fire('Error', 'Please select at least one application', 'error');
            return;
        }

        Swal.fire({
            title: 'Bulk Approve Applications?',
            text: `You are about to approve ${selected.length} application(s) with full requested quantity.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, approve all'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('bulkApproveForm');
                const container = document.getElementById('bulkApplicationIds');
                container.innerHTML = '';
                
                selected.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = id;
                    container.appendChild(input);
                });
                
                form.submit();
            }
        });
    }
</script>
@endpush