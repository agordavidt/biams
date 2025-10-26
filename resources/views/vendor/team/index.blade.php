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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Team Members</h4>
                    <a href="{{ route('vendor.team.create') }}" class="btn btn-primary">
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
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teamMembers as $member)
                            <tr>
                                <td>
                                    <strong>{{ $member->name }}</strong>
                                    @if($member->id === auth()->id())
                                        <span class="badge badge-soft-info ms-1">You</span>
                                    @endif
                                </td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->phone_number ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-soft-{{ $member->hasRole('Vendor Manager') ? 'primary' : 'success' }}">
                                        {{ $member->roles->first()->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-soft-{{ $member->status === 'onboarded' ? 'success' : 'warning' }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td>{{ $member->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
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
                                <td colspan="7" class="text-center">No team members found.</td>
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
            order: [[5, 'desc']]
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