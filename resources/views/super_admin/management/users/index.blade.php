@extends('layouts.super_admin')
@section('content')
<div class="container-fluid">
    <!-- Back Button + Title -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-3">
                        <i class="ri-arrow-left-line"></i>
                    </a>
                    <div>
                        <h4 class="mb-1">User Management</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <a href="{{ route('super_admin.management.users.create') }}" class="btn btn-primary">
                     Create User
                </a>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="ri-checkbox-circle-line me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Users Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                            <thead class="bg-light">
                                <tr>
                                    <th width="60" class="text-center">S/N</th>
                                    <th width="180">Name</th>
                                    <th width="220">Email</th>
                                    <th width="130">Role</th>
                                    <th width="150">Admin Type</th>
                                    <th width="160">Admin Unit</th>
                                    <th width="100" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 170px;" title="{{ $user->name }}">
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 210px;" title="{{ $user->email }}">
                                                <a href="mailto:{{ $user->email }}" class="text-muted">{{ $user->email }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary text-white small">
                                                {{ $user->roles->first()?->name ?? 'No Role' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->administrative_type)
                                                <div class="text-truncate" style="max-width: 140px;" title="{{ class_basename($user->administrative_type) }}">
                                                    {{ class_basename($user->administrative_type) }}
                                                </div>
                                            @else
                                                <span class="text-muted small">Global</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->administrative_type && $user->administrative_id)
                                                @php
                                                    $unitModel = $user->administrative_type;
                                                    $unit = $unitModel::find($user->administrative_id);
                                                @endphp
                                                <div class="text-truncate" style="max-width: 150px;" title="{{ $unit?->name ?? 'Unit Not Found' }}">
                                                    {{ $unit?->name ?? 'Unit Not Found' }}
                                                </div>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('super_admin.management.users.edit', $user->id) }}"
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                @if(Auth::id() !== $user->id)
                                                    <form action="{{ route('super_admin.management.users.destroy', $user->id) }}"
                                                          method="POST" style="display: inline-block;" id="delete-form-{{ $user->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete({{ $user->id }})" title="Delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="ri-inbox-line display-4 d-block mb-3"></i>
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Delete User?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        });
    }
</script>
@endpush