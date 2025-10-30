@extends('layouts.vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Team Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Team</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- NEW: Assignment Management Info Card --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-primary rounded-circle">
                                <i class="ri-links-line font-size-20"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-1">Resource Assignment System</h5>
                            <p class="text-muted mb-0">
                                Control which resources your distribution agents can access and fulfill
                            </p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('vendor.team.assignments.index') }}" class="btn btn-primary btn-lg">
                            <i class="ri-settings-3-line me-2"></i>Manage Resource Assignments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Team Members</h4>
                    <a href="{{ route('vendor.team.create') }}" class="btn btn-success">
                        <i class="ri-user-add-line me-1"></i> Add Team Member
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="teamTable" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Resource Access</th> {{-- NEW COLUMN --}}
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teamMembers as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title bg-{{ $member->hasRole('Vendor Manager') ? 'primary' : 'success' }} rounded-circle">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $member->name }}</strong>
                                            @if($member->id === auth()->id())
                                                <span class="badge badge-soft-info ms-1">You</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->phone_number ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-soft-{{ $member->hasRole('Vendor Manager') ? 'primary' : 'success' }}">
                                        {{ $member->roles->first()->name ?? 'N/A' }}
                                    </span>
                                </td>
                                
                                {{-- NEW: Resource Access Column --}}
                                <td>
                                    @if($member->hasRole('Distribution Agent'))
                                        @php
                                            $assignedResources = $member->assignedResources;
                                            $hasAssignments = $assignedResources->count() > 0;
                                        @endphp
                                        
                                        @if($hasAssignments)
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-warning me-2">
                                                    <i class="ri-lock-line me-1"></i>Restricted
                                                </span>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#viewAssignmentsModal{{ $member->id }}">
                                                    <i class="ri-eye-line me-1"></i>View ({{ $assignedResources->count() }})
                                                </button>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-2">
                                                    <i class="ri-global-line me-1"></i>Full Access
                                                </span>
                                                <small class="text-muted">All resources</small>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A (Manager)</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <span class="badge badge-soft-{{ $member->status === 'onboarded' ? 'success' : 'warning' }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td>{{ $member->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        {{-- NEW: Assignment Button for Distribution Agents --}}
                                        @if($member->hasRole('Distribution Agent'))
                                            <a href="{{ route('vendor.team.assignments.index') }}#agent-{{ $member->id }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Manage Assignments">
                                                <i class="ri-links-line"></i>
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('vendor.team.edit', $member) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        
                                        @if($member->id !== auth()->id())
                                        <button type="button" 
                                                class="btn btn-sm btn-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#resetPasswordModal{{ $member->id }}"
                                                title="Reset Password">
                                            <i class="ri-lock-password-line"></i>
                                        </button>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmDelete({{ $member->id }})"
                                                title="Remove">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        
                                        <form id="delete-form-{{ $member->id }}" 
                                              action="{{ route('vendor.team.destroy', $member) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- NEW: View Assignments Modal --}}
                            @if($member->hasRole('Distribution Agent') && $assignedResources->count() > 0)
                            <div class="modal fade" id="viewAssignmentsModal{{ $member->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="ri-user-line me-2"></i>{{ $member->name }}'s Resource Assignments
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <i class="ri-information-line me-2"></i>
                                                This agent can only access and fulfill the following resources:
                                            </div>
                                            
                                            <div class="row">
                                                @foreach($assignedResources as $resource)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card border">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <h6 class="mb-1">{{ $resource->name }}</h6>
                                                                        <span class="badge bg-info">{{ ucfirst($resource->type) }}</span>
                                                                        @if($resource->requires_quantity)
                                                                            <p class="mb-0 mt-2">
                                                                                <small class="text-muted">
                                                                                    Stock: <strong>{{ $resource->available_stock }}</strong> {{ $resource->unit }}
                                                                                </small>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                    <span class="badge bg-success">
                                                                        <i class="ri-checkbox-circle-line"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <a href="{{ route('vendor.team.assignments.index') }}" class="btn btn-primary">
                                                <i class="ri-settings-3-line me-1"></i>Manage Assignments
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Reset Password Modal -->
                            @if($member->id !== auth()->id())
                            <div class="modal fade" id="resetPasswordModal{{ $member->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('vendor.team.reset-password', $member) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reset Password for {{ $member->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="new_password{{ $member->id }}" class="form-label">New Password</label>
                                                    <input type="password" class="form-control" 
                                                           id="new_password{{ $member->id }}" 
                                                           name="new_password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_password_confirmation{{ $member->id }}" class="form-label">Confirm Password</label>
                                                    <input type="password" class="form-control" 
                                                           id="new_password_confirmation{{ $member->id }}" 
                                                           name="new_password_confirmation" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Reset Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No team members found.</td>
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
        $('#teamTable').DataTable({
            responsive: true,
            order: [[6, 'desc']] // Updated column index for Joined date
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This team member will be removed from your vendor account.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove them'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush