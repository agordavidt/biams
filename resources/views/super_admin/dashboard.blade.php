@extends('layouts.super_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Super Admin Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Users</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stats['total_users'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">
                                <i class="ri-check-line align-middle"></i>
                                <span class="counter-value" data-target="{{ $stats['onboarded_users'] }}">0</span>
                            </span>
                            <span class="ms-1 text-muted font-size-12">Onboarded</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-user-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Departments</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stats['total_departments'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <a href="{{ route('super_admin.management.departments.index') }}" class="text-decoration-underline font-size-12">
                                Manage Departments
                            </a>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-building-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Agencies</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stats['total_agencies'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <a href="{{ route('super_admin.management.agencies.index') }}" class="text-decoration-underline font-size-12">
                                Manage Agencies
                            </a>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-community-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total LGAs</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stats['total_lgas'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <a href="{{ route('super_admin.management.lgas.index') }}" class="text-decoration-underline font-size-12">
                                Manage LGAs
                            </a>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-map-pin-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Management Modules -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-settings-3-line me-1"></i> System Management
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Users Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('super_admin.management.users.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                            <i class="ri-user-settings-line text-primary"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">User Management</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Manage system users & roles
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Departments Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('super_admin.management.departments.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                            <i class="ri-building-line text-success"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Departments</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Organizational departments
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Agencies Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('super_admin.management.agencies.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-info fs-3">
                                            <i class="ri-community-line text-info"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Agencies</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Government agencies
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- LGAs Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('super_admin.management.lgas.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-warning fs-3">
                                            <i class="ri-map-pin-line text-warning"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Local Governments</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        LGA administration
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics & Data Management -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> Analytics & Data Management
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Reports & Analytics -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card module-card border h-100" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                        <i class="ri-line-chart-line text-primary"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">Reports & Analytics</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    State-wide data insights
                                </p>
                                <div class="mt-2">
                                    <span class="badge badge-soft-info">Coming Soon</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Farm Practices -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card module-card border h-100" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                        <i class="ri-plant-line text-success"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">Farm Practices</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Distribution & analytics
                                </p>
                                <div class="mt-2">
                                    <span class="badge badge-soft-info">Coming Soon</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resources Management -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('super_admin.resources.index') }}" class="text-decoration-none">
                        <div class="card module-card border h-100" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-warning fs-3">
                                        <i class="ri-database-2-line text-warning"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">Resources</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Resources & beneficiaries
                                </p>                               
                            </div>
                        </div>
                        </a>
                    </div>

                    <!-- Partners Management -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('super_admin.vendors.index') }}" class="text-decoration-none">
                        <div class="card module-card border h-100" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-danger fs-3">
                                        <i class="ri-handshake-line text-danger"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">Vendors</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Vendors activities & impact
                                </p>                                
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Status & Users by Role -->
<!-- <div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-user-line me-1"></i> User Status Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-4">
                        <div class="p-3">
                            <h4 class="mb-1 text-success">
                                <span class="counter-value" data-target="{{ $stats['onboarded_users'] }}">0</span>
                            </h4>
                            <p class="text-muted mb-0">Onboarded</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 border-start border-end">
                            <h4 class="mb-1 text-warning">
                                <span class="counter-value" data-target="{{ $stats['pending_users'] }}">0</span>
                            </h4>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3">
                            <h4 class="mb-1 text-danger">
                                <span class="counter-value" data-target="{{ $stats['rejected_users'] }}">0</span>
                            </h4>
                            <p class="text-muted mb-0">Rejected</p>
                        </div>
                    </div>
                </div>

                @if(count($usersByRole) > 0)
                <div class="border-top pt-3">
                    <h6 class="text-muted mb-3">
                        <i class="ri-shield-user-line me-1"></i> Users by Role
                    </h6>
                    <div class="row g-3">
                        @foreach($usersByRole as $role => $count)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-5">
                                            <i class="ri-shield-user-line"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $role }}</h6>
                                    <small class="text-muted">{{ $count }} {{ Str::plural('user', $count) }}</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge badge-soft-primary">{{ $count }}</span>
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
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-flashlight-line me-1"></i> Quick Actions
                </h5>
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
</div> -->

<!-- Distribution Tables -->
<div class="row">
    <!-- Top Departments -->
    <!-- <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="ri-building-line me-1"></i> Top Departments
                </h5>
                <a href="{{ route('super_admin.management.departments.index') }}" class="btn btn-sm btn-soft-secondary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @forelse($departmentsWithUsers as $dept)
                <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-xs">
                            <span class="avatar-title bg-soft-success text-success rounded-circle fs-6">
                                <i class="ri-building-line"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $dept->name }}</h6>
                        @if($dept->abbreviation)
                            <small class="text-muted">{{ $dept->abbreviation }}</small>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge badge-soft-success">{{ $dept->users_count }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="ri-building-line fs-1 d-block mb-2"></i>
                    <p class="mb-0">No departments found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div> -->

    <!-- Top Agencies -->
    <!-- <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="ri-community-line me-1"></i> Top Agencies
                </h5>
                <a href="{{ route('super_admin.management.agencies.index') }}" class="btn btn-sm btn-soft-secondary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @forelse($agenciesWithUsers as $agency)
                <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-xs">
                            <span class="avatar-title bg-soft-info text-info rounded-circle fs-6">
                                <i class="ri-community-line"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $agency->name }}</h6>
                        <small class="text-muted">{{ $agency->department->name ?? 'N/A' }}</small>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge badge-soft-info">{{ $agency->users_count }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="ri-community-line fs-1 d-block mb-2"></i>
                    <p class="mb-0">No agencies found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div> -->

    <!-- Top LGAs -->
    <!-- <div class="col-xl-4">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="ri-map-pin-line me-1"></i> Top LGAs
                </h5>
                <a href="{{ route('super_admin.management.lgas.index') }}" class="btn btn-sm btn-soft-secondary">
                    View All
                </a>
            </div>
            <div class="card-body">
                @forelse($lgasWithUsers as $lga)
                <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-xs">
                            <span class="avatar-title bg-soft-warning text-warning rounded-circle fs-6">
                                <i class="ri-map-pin-line"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $lga->name }}</h6>
                        @if($lga->code)
                            <small class="text-muted">{{ $lga->code }}</small>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge badge-soft-warning">{{ $lga->users_count }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="ri-map-pin-line fs-1 d-block mb-2"></i>
                    <p class="mb-0">No LGAs found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div> -->
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Module Card Hover Effects
    const moduleCards = document.querySelectorAll('.module-card');
    moduleCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.1)';
            this.style.borderColor = '#556ee6';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
            this.style.borderColor = '#e5e7eb';
        });
    });

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
                counter.innerText = value.toLocaleString();
            }
        }
        
        animate();
    });
});
</script>

<style>
.module-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-animate {
    transition: all 0.3s ease;
}

.card-animate:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
@endpush