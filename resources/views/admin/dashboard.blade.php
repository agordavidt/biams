@extends('layouts.admin')

@section('content')
<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">State Admin Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Farmers</p>
                        <h4 class="mb-2">{{ number_format($stats['totalFarmers']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-success fw-bold font-size-12 me-2">
                                {{ number_format($stats['activeFarmers']) }} Active
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-user-3-line font-size-24"></i>
                        </span>
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
                        <p class="text-truncate font-size-14 mb-2">System Staff</p>
                        <h4 class="mb-2">{{ number_format($stats['totalStaff']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-info fw-bold font-size-12 me-2">
                                {{ number_format($stats['lgaAdmins']) }} LGA Admins
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-info rounded-3">
                            <i class="ri-team-line font-size-24"></i>
                        </span>
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
                        <p class="text-truncate font-size-14 mb-2">Cooperatives</p>
                        <h4 class="mb-2">{{ number_format($stats['totalCooperatives']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-warning fw-bold font-size-12 me-2">
                                {{ number_format($stats['totalMembers']) }} Members
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-community-line font-size-24"></i>
                        </span>
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
                        <p class="text-truncate font-size-14 mb-2">Pending Approvals</p>
                        <h4 class="mb-2">{{ number_format($stats['pendingApproval']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-danger fw-bold font-size-12 me-2">
                                Needs Attention
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-danger rounded-3">
                            <i class="ri-time-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Modules Grid -->
<div class="row">
    <div class="col-12">
        <h5 class="mb-3">System Modules</h5>
    </div>
    
    <!-- Farmers Module -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.farmers.index') }}" class="card module-card text-decoration-none">
            <div class="card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-soft-success text-success font-size-24">
                        <i class="ri-plant-line"></i>
                    </span>
                </div>
                <h5 class="card-title text-dark">Farmers Management</h5>
                <p class="text-muted mb-0">Manage all farmers and farm data</p>
            </div>
        </a>
    </div>

    <!-- Users Module -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.users.index') }}" class="card module-card text-decoration-none">
            <div class="card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                        <i class="ri-team-line"></i>
                    </span>
                </div>
                <h5 class="card-title text-dark">System Staff</h5>
                <p class="text-muted mb-0">Manage LGA Admins & Enrollment Agents</p>
            </div>
        </a>
    </div>

    <!-- Partners Module -->
    <div class="col-xl-3 col-md-6">
        <a href="#" class="card module-card text-decoration-none">
            <div class="card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-soft-info text-info font-size-24">
                        <i class="ri-handshake-line"></i>
                    </span>
                </div>
                <h5 class="card-title text-dark">Partners</h5>
                <p class="text-muted mb-0">Manage partnerships & collaborations</p>
            </div>
        </a>
    </div>

    <!-- Resources Module -->
    <div class="col-xl-3 col-md-6">
        <a href="#" class="card module-card text-decoration-none">
            <div class="card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-soft-warning text-warning font-size-24">
                        <i class="ri-resource-line"></i>
                    </span>
                </div>
                <h5 class="card-title text-dark">Resources</h5>
                <p class="text-muted mb-0">Agricultural resources & inputs</p>
            </div>
        </a>
    </div>

    <!-- Analytics Module -->
    <div class="col-xl-3 col-md-6">
        <a href="#" class="card module-card text-decoration-none">
            <div class="card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-soft-purple text-purple font-size-24">
                        <i class="ri-bar-chart-line"></i>
                    </span>
                </div>
                <h5 class="card-title text-dark">Analytics</h5>
                <p class="text-muted mb-0">Reports & data insights</p>
            </div>
        </a>
    </div>

    <!-- Markets Module -->
    <div class="col-xl-3 col-md-6">
        <a href="#" class="card module-card text-decoration-none">
            <div class="card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-soft-success text-success font-size-24">
                        <i class="ri-store-line"></i>
                    </span>
                </div>
                <h5 class="card-title text-dark">Marketplace</h5>
                <p class="text-muted mb-0">Agricultural trading platform</p>
            </div>
        </a>
    </div>

    <!-- Support Module -->
    <div class="col-xl-3 col-md-6">
        <a href="#" class="card module-card text-decoration-none">
            <div class="card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-soft-danger text-danger font-size-24">
                        <i class="ri-customer-service-line"></i>
                    </span>
                </div>
                <h5 class="card-title text-dark">Support</h5>
                <p class="text-muted mb-0">Help desk & user support</p>
            </div>
        </a>
    </div>

    <!-- Cooperatives Module -->
    <div class="col-xl-3 col-md-6">
        <a href="#" class="card module-card text-decoration-none">
            <div class="card-body text-center">
                <div class="avatar-sm mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-soft-dark text-dark font-size-24">
                        <i class="ri-community-line"></i>
                    </span>
                </div>
                <h5 class="card-title text-dark">Cooperatives</h5>
                <p class="text-muted mb-0">Manage farmer cooperatives</p>
            </div>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Staff Registrations</h4>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>LGA/Dept</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentStaff as $staff)
                            <tr>
                                <td>
                                    <strong>{{ $staff->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $staff->email }}</small>
                                </td>
                                <td>
                                    @if($staff->hasRole('LGA Admin'))
                                    <span class="badge bg-primary">LGA Admin</span>
                                    @elseif($staff->hasRole('Enrollment Agent'))
                                    <span class="badge bg-success">Enrollment Agent</span>
                                    @else
                                    <span class="badge bg-secondary">Staff</span>
                                    @endif
                                </td>
                                <td>{{ $staff->administrative_unit }}</td>
                                <td>{{ $staff->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">System Overview</h4>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h5 class="mb-1">{{ number_format($stats['totalLGAs']) }}</h5>
                        <p class="text-muted mb-0">LGAs</p>
                    </div>
                    <div class="col-6 mb-3">
                        <h5 class="mb-1">{{ number_format($stats['enrollmentAgents']) }}</h5>
                        <p class="text-muted mb-0">Enrollment Agents</p>
                    </div>
                    <div class="col-6">
                        <h5 class="mb-1">{{ number_format($stats['totalFarmLands']) }}</h5>
                        <p class="text-muted mb-0">Farm Lands</p>
                    </div>
                    <div class="col-6">
                        <h5 class="mb-1">{{ number_format($stats['totalLandSize'], 2) }}</h5>
                        <p class="text-muted mb-0">Hectares</p>
                    </div>
                </div>
                <hr>
                <div class="mt-3">
                    <h6 class="mb-2">Farm Type Distribution</h6>
                    @foreach($farmTypeDistribution as $type => $count)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-capitalize">{{ $type }}</span>
                        <span class="badge bg-soft-primary">{{ number_format($count) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
.module-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    height: 100%;
}

.module-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-color: #3b82f6;
}

.bg-soft-primary { background-color: rgba(59, 130, 246, 0.1); }
.bg-soft-success { background-color: rgba(16, 185, 129, 0.1); }
.bg-soft-info { background-color: rgba(59, 130, 246, 0.1); }
.bg-soft-warning { background-color: rgba(245, 158, 11, 0.1); }
.bg-soft-purple { background-color: rgba(139, 92, 246, 0.1); }
.bg-soft-danger { background-color: rgba(239, 68, 68, 0.1); }
.bg-soft-dark { background-color: rgba(17, 24, 39, 0.1); }
</style>
@endpush