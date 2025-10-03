@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Farmer Enrollment Review Dashboard</h4>
            <div class="page-title-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="ri-download-line me-1"></i> Export
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('lga_admin.farmers.export', ['type' => 'pending']) }}">Pending Review</a>
                        <a class="dropdown-item" href="{{ route('lga_admin.farmers.export', ['type' => 'approved']) }}">Approved</a>
                        <a class="dropdown-item" href="{{ route('lga_admin.farmers.export', ['type' => 'active']) }}">Active</a>
                        <a class="dropdown-item" href="{{ route('lga_admin.farmers.export', ['type' => 'rejected']) }}">Rejected</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium font-size-14 mb-2">Pending Review</p>
                        <h4 class="mb-0 text-white">{{ $counts['pending'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light rounded-circle text-warning font-size-24">
                            <i class="ri-question-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium font-size-14 mb-2">Pending Activation</p>
                        <h4 class="mb-0 text-white">{{ $counts['pending_activation'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light rounded-circle text-info font-size-24">
                            <i class="ri-user-add-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium font-size-14 mb-2">Rejected</p>
                        <h4 class="mb-0 text-white">{{ $counts['rejected'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light rounded-circle text-danger font-size-24">
                            <i class="ri-close-circle-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium font-size-14 mb-2">Active Farmers</p>
                        <h4 class="mb-0 text-white">{{ $counts['active'] }}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-light rounded-circle text-success font-size-24">
                            <i class="ri-user-check-line"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

---

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Submissions Awaiting Review ({{ $pendingFarmers->total() }})</h4>
                    {{-- Bulk Approve functionality buttons removed --}}
                </div>

                <div class="table-responsive">
                    {{-- Bulk Approve form removed, but keeping the table structure --}}
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                {{-- Checkbox column removed --}}
                                <th>Farmer Name</th>
                                <th>NIN</th>
                                <th>Submitted By</th>
                                <th>Submission Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingFarmers as $farmer)
                            <tr>
                                {{-- Checkbox input removed --}}
                                <td>
                                    <a href="{{ route('lga_admin.farmers.show', $farmer) }}" class="text-primary fw-bold">
                                        {{ $farmer->full_name }}
                                    </a>
                                </td>
                                <td>{{ $farmer->nin }}</td>
                                <td>{{ $farmer->enrolledBy->name ?? 'N/A' }}</td>
                                <td>{{ $farmer->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('lga_admin.farmers.show', $farmer) }}" 
                                       class="btn btn-sm btn-primary waves-effect waves-light" 
                                       title="Review Profile">
                                        <i class="ri-eye-line"></i> Review
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-checkbox-multiple-blank-line display-4"></i>
                                        <h5 class="mt-2">No pending farmer profiles</h5>
                                        <p>All submissions have been reviewed.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($pendingFarmers->hasPages())
                <div class="mt-3">
                    {{ $pendingFarmers->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

---

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Reviewed Submissions</h4>
                    {{-- Bulk Activate functionality buttons removed --}}
                </div>

                <div class="table-responsive">
                    {{-- Bulk Activate form removed, but keeping the table structure --}}
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                {{-- Checkbox column removed --}}
                                <th>Farmer Name</th>
                                <th>Status</th>
                                <th>Reviewed By</th>
                                <th>Review Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviewedFarmers as $farmer)
                            <tr>
                                {{-- Checkbox input removed --}}
                                <td>{{ $farmer->full_name }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'rejected' => 'danger',
                                            'pending_activation' => 'info', 
                                            'active' => 'success'
                                        ][$farmer->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ ucwords(str_replace('_', ' ', $farmer->status)) }}
                                    </span>
                                </td>
                                <td>{{ $farmer->approvedBy->name ?? 'N/A' }}</td>
                                <td>{{ $farmer->approved_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('lga_admin.farmers.show', $farmer) }}" 
                                       class="btn btn-sm btn-outline-primary waves-effect waves-light">
                                        <i class="ri-eye-line"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-archive-line display-4"></i>
                                        <h5 class="mt-2">No reviewed submissions</h5>
                                        <p>Reviewed submissions will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($reviewedFarmers->hasPages())
                <div class="mt-3">
                    {{ $reviewedFarmers->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // All bulk approval and bulk activation JavaScript functionality has been removed.
    // The view is now configured for individual review and action only.
});
</script>
@endpush