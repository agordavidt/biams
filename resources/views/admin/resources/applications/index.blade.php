@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Resource Applications</h4>
                <p class="text-muted">Manage farmer applications for resources</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-1 d-block">Total Applications</span>
                            <h4 class="mb-0">{{ number_format($stats['total']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-3">
                                    <i class="ri-file-list-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-1 d-block">Pending Review</span>
                            <h4 class="mb-0 text-warning">{{ number_format($stats['pending']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-warning text-warning rounded-circle fs-3">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-1 d-block">Approved</span>
                            <h4 class="mb-0 text-success">{{ number_format($stats['approved'] + $stats['paid']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-success text-success rounded-circle fs-3">
                                    <i class="ri-check-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-1 d-block">Fulfilled</span>
                            <h4 class="mb-0 text-info">{{ number_format($stats['fulfilled']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-info text-info rounded-circle fs-3">
                                    <i class="ri-checkbox-circle-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Applications List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-0">Applications List</h5>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.resources.applications.export', request()->query()) }}" 
                               class="btn btn-success btn-sm">
                                <i class="ri-download-line me-1"></i> Export CSV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="payment_pending" {{ request('status') === 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="fulfilled" {{ request('status') === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Resource</label>
                            <select name="resource_id" class="form-select">
                                <option value="">All Resources</option>
                                @foreach($resources as $res)
                                    <option value="{{ $res->id }}" {{ request('resource_id') == $res->id ? 'selected' : '' }}>
                                        {{ $res->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}"
                                   placeholder="Search by name or email">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-search-line me-1"></i> Filter
                            </button>
                        </div>
                    </form>

                    <!-- Applications Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </th>
                                    <th>Farmer</th>
                                    <th>Resource</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Applied</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $app)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input application-checkbox" 
                                                   value="{{ $app->id }}">
                                        </td>
                                        <td>
                                            <strong>{{ $app->user->name }}</strong><br>
                                            <small class="text-muted">{{ $app->user->email }}</small>
                                        </td>
                                        <td>
                                            {{ $app->resource->name }}<br>
                                            <small class="text-muted">
                                                {{ $app->resource->vendor ? $app->resource->vendor->legal_name : 'Ministry' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($app->resource->requires_quantity)
                                                <span class="badge bg-light text-dark">
                                                    {{ $app->quantity_requested ?? '-' }} {{ $app->resource->unit }}
                                                </span>
                                                @if($app->quantity_approved)
                                                    <br><small class="text-success">Approved: {{ $app->quantity_approved }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($app->resource->requires_payment)
                                                ₦{{ number_format($app->total_amount ?? ($app->quantity_requested * $app->unit_price), 2) }}
                                                @if($app->amount_paid)
                                                    <br><small class="text-success">Paid: ₦{{ number_format($app->amount_paid, 2) }}</small>
                                                @endif
                                            @else
                                                <span class="badge bg-success">Free</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $app->getStatusBadgeClass() }}">
                                                {{ $app->getStatusOptions()[$app->status] ?? ucfirst($app->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $app->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.resources.applications.show', $app) }}" 
                                               class="btn btn-sm btn-light">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            No applications found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="row mt-3" id="bulkActions" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center justify-content-between">
                                <div>
                                    <strong id="selectedCount">0</strong> application(s) selected
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm" 
                                            onclick="bulkAction('approve')">
                                        <i class="ri-check-line me-1"></i> Bulk Approve
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                            onclick="bulkAction('reject')">
                                        <i class="ri-close-line me-1"></i> Bulk Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($applications->hasPages())
                        <div class="mt-3">
                            {{ $applications->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="bulkActionForm" action="{{ route('admin.resources.applications.bulk-update') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkActionTitle">Bulk Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="bulkActionType">
                    <input type="hidden" name="applications[]" id="bulkApplicationIds">
                    
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3" id="bulkNotes"></textarea>
                        <small class="text-muted" id="notesHelp">Optional notes for this action</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="bulkSubmitBtn">Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
    // Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.application-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    // Individual Checkboxes
    document.querySelectorAll('.application-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checked = document.querySelectorAll('.application-checkbox:checked');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        
        if (checked.length > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = checked.length;
        } else {
            bulkActions.style.display = 'none';
        }
    }

    function bulkAction(action) {
        const checked = Array.from(document.querySelectorAll('.application-checkbox:checked'))
            .map(cb => cb.value);
        
        if (checked.length === 0) {
            toastr.warning('Please select at least one application');
            return;
        }

        document.getElementById('bulkActionType').value = action;
        document.getElementById('bulkApplicationIds').value = checked.join(',');
        
        const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
        const title = document.getElementById('bulkActionTitle');
        const submitBtn = document.getElementById('bulkSubmitBtn');
        const notesField = document.getElementById('bulkNotes');
        const notesHelp = document.getElementById('notesHelp');
        
        if (action === 'approve') {
            title.textContent = 'Bulk Approve Applications';
            submitBtn.className = 'btn btn-success';
            submitBtn.innerHTML = '<i class="ri-check-line me-1"></i> Approve Selected';
            notesField.required = false;
            notesHelp.textContent = 'Optional notes for this action';
        } else if (action === 'reject') {
            title.textContent = 'Bulk Reject Applications';
            submitBtn.className = 'btn btn-danger';
            submitBtn.innerHTML = '<i class="ri-close-line me-1"></i> Reject Selected';
            notesField.required = true;
            notesHelp.textContent = 'Required: Provide reason for rejection';
        }
        
        modal.show();
    }

    // Form submission
    document.getElementById('bulkActionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const applications = formData.get('applications[]').split(',');
        
        // Convert to proper array format
        formData.delete('applications[]');
        applications.forEach(id => formData.append('applications[]', id));
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message || 'Action completed successfully');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                toastr.error(data.message || 'Action failed');
            }
        })
        .catch(error => {
            toastr.error('An error occurred');
            console.error(error);
        });
    });
</script>
@endpush
@endsection