@extends('layouts.super_admin')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Super Admin Dashboard</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Users Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Users</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" data-target="{{ $stats['total_users'] }}">0</span>
                                </h4>
                                <a href="{{ route('super_admin.management.users.index') }}" class="text-decoration-underline">
                                    Manage Users
                                </a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="ri-user-line text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Departments Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Departments</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" data-target="{{ $stats['total_departments'] }}">0</span>
                                </h4>
                                <a href="{{ route('super_admin.management.departments.index') }}" class="text-decoration-underline">
                                    Manage Departments
                                </a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-success-subtle rounded fs-3">
                                    <i class="ri-building-line text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agencies Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Agencies</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" data-target="{{ $stats['total_agencies'] }}">0</span>
                                </h4>
                                <a href="{{ route('super_admin.management.agencies.index') }}" class="text-decoration-underline">
                                    Manage Agencies
                                </a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle rounded fs-3">
                                    <i class="ri-community-line text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LGAs Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total LGAs</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    <span class="counter-value" data-target="{{ $stats['total_lgas'] }}">0</span>
                                </h4>
                                <a href="{{ route('super_admin.management.lgas.index') }}" class="text-decoration-underline">
                                    Manage LGAs
                                </a>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle rounded fs-3">
                                    <i class="ri-map-pin-line text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Status Overview & Quick Actions -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header border-0 align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">User Status Overview</h4>
                    </div>
                    <div class="card-body p-0 pb-2">
                        <div class="w-100">
                            <div class="row g-0 text-center">
                                <div class="col-4">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1">
                                            <span class="counter-value text-success" data-target="{{ $stats['onboarded_users'] }}">0</span>
                                        </h5>
                                        <p class="text-muted mb-0">Onboarded</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1">
                                            <span class="counter-value text-warning" data-target="{{ $stats['pending_users'] }}">0</span>
                                        </h5>
                                        <p class="text-muted mb-0">Pending</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 border border-dashed border-start-0 border-end-0">
                                        <h5 class="mb-1">
                                            <span class="counter-value text-danger" data-target="{{ $stats['rejected_users'] }}">0</span>
                                        </h5>
                                        <p class="text-muted mb-0">Rejected</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(count($usersByRole) > 0)
                        <div class="p-3 pt-2">
                            <h6 class="text-muted mb-3">Users by Role</h6>
                            <div class="row g-3">
                                @foreach($usersByRole as $role => $count)
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-16">
                                                    <i class="ri-shield-user-line"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-muted mb-1">{{ $role }}</p>
                                            <h6 class="mb-0">{{ $count }} {{ Str::plural('user', $count) }}</h6>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('super_admin.management.users.create') }}" class="btn btn-primary">
                                <i class="ri-user-add-line align-middle me-1"></i>
                                Create New User
                            </a>
                            <a href="{{ route('super_admin.management.users.index') }}" class="btn btn-soft-primary">
                                <i class="ri-user-settings-line align-middle me-1"></i>
                                Manage Users
                            </a>
                            <a href="{{ route('super_admin.management.departments.index') }}" class="btn btn-soft-success">
                                <i class="ri-building-line align-middle me-1"></i>
                                Manage Departments
                            </a>
                            <a href="{{ route('super_admin.management.agencies.index') }}" class="btn btn-soft-info">
                                <i class="ri-community-line align-middle me-1"></i>
                                Manage Agencies
                            </a>
                            <a href="{{ route('super_admin.management.lgas.index') }}" class="btn btn-soft-warning">
                                <i class="ri-map-pin-line align-middle me-1"></i>
                                Manage LGAs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution Tables -->
        <div class="row">
            <!-- Top Departments -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Top Departments by Users</h4>
                        <a href="{{ route('super_admin.management.departments.index') }}" class="btn btn-sm btn-soft-secondary">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($departmentsWithUsers as $dept)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $dept->name }}</h6>
                                <p class="text-muted mb-0 fs-12">
                                    @if($dept->abbreviation)
                                        <span class="badge bg-primary-subtle text-primary">{{ $dept->abbreviation }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-primary">{{ $dept->users_count }} {{ Str::plural('user', $dept->users_count) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="ri-building-line fs-1"></i>
                            <p class="mt-2 mb-0">No departments found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Agencies -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Top Agencies by Users</h4>
                        <a href="{{ route('super_admin.management.agencies.index') }}" class="btn btn-sm btn-soft-secondary">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($agenciesWithUsers as $agency)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $agency->name }}</h6>
                                <p class="text-muted mb-0 fs-12">{{ $agency->department->name ?? 'N/A' }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-info">{{ $agency->users_count }} {{ Str::plural('user', $agency->users_count) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="ri-community-line fs-1"></i>
                            <p class="mt-2 mb-0">No agencies found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top LGAs -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Top LGAs by Users</h4>
                        <a href="{{ route('super_admin.management.lgas.index') }}" class="btn btn-sm btn-soft-secondary">
                            View All
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($lgasWithUsers as $lga)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $lga->name }}</h6>
                                <p class="text-muted mb-0 fs-12">
                                    @if($lga->code)
                                        <span class="badge bg-warning-subtle text-warning">{{ $lga->code }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-warning">{{ $lga->users_count }} {{ Str::plural('user', $lga->users_count) }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="ri-map-pin-line fs-1"></i>
                            <p class="mt-2 mb-0">No LGAs found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <!-- <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Recent Users</h4>
                        <a href="{{ route('super_admin.management.users.index') }}" class="btn btn-sm btn-soft-secondary">
                            View All Users
                        </a>
                    </div>
                    <div class="card-body">
                        @if($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary-subtle text-primary">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($user->status === 'onboarded')
                                                <span class="badge bg-success">Onboarded</span>
                                            @elseif($user->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('super_admin.management.users.edit', $user) }}" class="btn btn-sm btn-soft-primary">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-5">
                            <i class="ri-user-line display-4"></i>
                            <p class="mt-2">No users found</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter animation
        const counters = document.querySelectorAll('.counter-value');
        const speed = 200;

        counters.forEach(counter => {
            const animate = () => {
                const value = +counter.getAttribute('data-target');
                const data = +counter.innerText;
                const time = value / speed;
                
                if(data < value) {
                    counter.innerText = Math.ceil(data + time);
                    setTimeout(animate, 1);
                } else {
                    counter.innerText = value;
                }
            }
            
            animate();
        });
    });
</script>
@endpush