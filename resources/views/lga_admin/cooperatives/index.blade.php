@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Cooperative Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Cooperatives</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total Cooperatives</p>
                        <h4 class="mb-0">{{ number_format($stats['total_cooperatives']) }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="ri-building-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total Members</p>
                        <h4 class="mb-0">{{ number_format($stats['total_members']) }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-success">
                                <i class="ri-team-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Land Managed (ha)</p>
                        <h4 class="mb-0">{{ number_format($stats['total_land_managed'], 2) }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-info mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-info">
                                <i class="ri-landscape-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Registered This Month</p>
                        <h4 class="mb-0">{{ number_format($stats['active_this_month']) }}</h4>
                    </div>
                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-warning">
                                <i class="ri-calendar-check-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-6">
                        <div class="d-flex gap-2">
                            <a href="{{ route('lga_admin.cooperatives.create') }}" class="btn btn-primary">
                                Register New Cooperative
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <form method="GET" action="{{ route('lga_admin.cooperatives.index') }}" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, registration number..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-secondary">
                                <i class="ri-search-line"></i>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('lga_admin.cooperatives.index') }}" class="btn btn-light">
                                    <i class="ri-close-line"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Registration No.</th>
                                <th>Name</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Members</th>
                                <th>Land (ha)</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cooperatives as $cooperative)
                            <tr>
                                <td>
                                    <span class="badge bg-soft-primary text-primary">{{ $cooperative->registration_number }}</span>
                                </td>
                                <td>
                                    <h6 class="mb-0">{{ $cooperative->name }}</h6>
                                    <small class="text-muted">
                                        @if($cooperative->primary_activities)
                                            {{ implode(', ', array_slice($cooperative->primary_activities, 0, 2)) }}
                                            @if(count($cooperative->primary_activities) > 2)
                                                <span class="text-muted">+{{ count($cooperative->primary_activities) - 2 }} more</span>
                                            @endif
                                        @endif
                                    </small>
                                </td>
                                <td>{{ $cooperative->contact_person }}</td>
                                <td>
                                    <i class="ri-phone-line text-muted"></i> {{ $cooperative->phone }}
                                </td>
                                <td>
                                    <span class="badge bg-soft-success text-success">
                                        {{ number_format($cooperative->members_count) }} members
                                    </span>
                                </td>
                                <td>{{ number_format($cooperative->total_land_size ?? 0, 2) }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $cooperative->created_at->format('M d, Y') }}
                                        <br>by {{ $cooperative->registeredBy->name }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('lga_admin.cooperatives.show', $cooperative) }}" class="btn btn-sm btn-soft-info" title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('lga_admin.cooperatives.members', $cooperative) }}" class="btn btn-sm btn-soft-success" title="Manage Members">
                                            <i class="ri-team-line"></i>
                                        </a>
                                        <a href="{{ route('lga_admin.cooperatives.edit', $cooperative) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('lga_admin.cooperatives.destroy', $cooperative) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-soft-danger delete-btn" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-building-line font-size-24 d-block mb-2"></i>
                                        <p class="mb-0">No cooperatives registered yet.</p>
                                        <a href="{{ route('lga_admin.cooperatives.create') }}" class="btn btn-sm btn-primary mt-2">
                                            Register First Cooperative
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $cooperatives->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: 'Delete Cooperative?',
                text: "This action cannot be undone. The cooperative must have no members to be deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush