@extends('layouts.enrollment_agent')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">All Farmer Submissions</h4>
            <div class="page-title-right">
                <a href="{{ route('enrollment.farmers.create') }}" class="btn btn-primary waves-effect waves-light">
                    Enroll New Farmer
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Farmer Enrollment List</h4>
                
                @if($farmers->isEmpty())
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        No farmer profiles have been submitted yet. Start by enrolling a new farmer!
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Farmer Name</th>
                                    <th>LGA</th>
                                    <th>Status</th>
                                    <th>Farmlands</th>
                                    <th>Submission Date</th>
                                    <th>Last Action</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($farmers as $farmer)
                                <tr>
                                    <td>
                                        <a href="{{ route('enrollment.farmers.show', $farmer) }}" class="text-primary fw-bold">
                                            {{ $farmer->full_name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $farmer->nin }}</small>
                                    </td>
                                    <td>{{ $farmer->lga->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'pending_lga_review' => ['class' => 'warning', 'icon' => 'ri-time-line'],
                                                'pending_activation' => ['class' => 'info', 'icon' => 'ri-user-add-line'],
                                                'active' => ['class' => 'success', 'icon' => 'ri-user-check-line'],
                                                'rejected' => ['class' => 'danger', 'icon' => 'ri-close-circle-line'],
                                                'suspended' => ['class' => 'secondary', 'icon' => 'ri-pause-circle-line'],
                                            ][$farmer->status] ?? ['class' => 'secondary', 'icon' => 'ri-question-line'];
                                        @endphp
                                        <span class="badge bg-{{ $statusConfig['class'] }}">
                                            <i class="{{ $statusConfig['icon'] }} me-1"></i>
                                            {{ ucwords(str_replace('_', ' ', $farmer->status)) }}
                                        </span>
                                        
                                        @if($farmer->status === 'rejected' && $farmer->rejection_reason)
                                            <br>
                                            <small class="text-danger mt-1" title="{{ $farmer->rejection_reason }}">
                                                <i class="ri-alert-line me-1"></i>Rejected
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $farmer->farmLands->count() }}</span>
                                        <small class="text-muted"> plot(s)</small>
                                    </td>
                                    <td>{{ $farmer->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($farmer->approved_at)
                                            <small class="text-success">
                                                <i class="ri-check-double-line me-1"></i>
                                                Approved: {{ $farmer->approved_at->format('M d') }}
                                            </small>
                                        @elseif($farmer->status === 'rejected')
                                            <small class="text-danger">
                                                <i class="ri-close-line me-1"></i>
                                                Rejected
                                            </small>
                                        @else
                                            <small class="text-warning">
                                                <i class="ri-time-line me-1"></i>
                                                Awaiting Review
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('enrollment.farmers.show', $farmer) }}" 
                                               class="btn btn-sm btn-info waves-effect waves-light" 
                                               title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            
                                            @if(in_array($farmer->status, ['pending_lga_review', 'rejected']))
                                                <a href="{{ route('enrollment.farmers.edit', $farmer) }}" 
                                                   class="btn btn-sm btn-warning waves-effect waves-light" 
                                                   title="Edit/Resubmit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            @endif
                                            
                                            @if(in_array($farmer->status, ['pending_activation', 'active']))
                                                <a href="{{ route('enrollment.farmers.credentials', $farmer) }}" 
                                                   class="btn btn-sm btn-success waves-effect waves-light" 
                                                   title="View Credentials">
                                                    <i class="ri-key-line"></i>
                                                </a>
                                                
                                                <a href="{{ route('enrollment.farmers.farmlands.create', $farmer) }}" 
                                                   class="btn btn-sm btn-primary waves-effect waves-light" 
                                                   title="Add Farmland">
                                                    <i class="ri-add-circle-line"></i>
                                                </a>
                                            @endif
                                            
                                            @if(in_array($farmer->status, ['pending_lga_review', 'rejected']))
                                                <button type="button" class="btn btn-sm btn-danger waves-effect waves-light" 
                                                        onclick="confirmDelete({{ $farmer->id }})" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $farmers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete(farmerId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = `/enrollment/farmers/${farmerId}`;
            form.submit();
        }
    });
}
</script>
@endpush