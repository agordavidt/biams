@extends('layouts.admin')

@section('content')
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
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Farmers</p>
                        <h4 class="mb-0">{{ number_format($stats['totalFarmers']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">
                                <i class="ri-arrow-up-line align-middle"></i>
                                {{ number_format($stats['activeFarmers']) }}
                            </span>
                            <span class="ms-1 text-muted font-size-12">Active</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-user-3-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">System Staff</p>
                        <h4 class="mb-0">{{ number_format($stats['totalStaff']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-info">{{ number_format($stats['lgaAdmins']) }} Admins</span>
                            <span class="badge badge-soft-success ms-1">{{ number_format($stats['enrollmentAgents']) }} Agents</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-team-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Farmlands</p>
                        <h4 class="mb-0">{{ number_format($stats['totalFarmLands']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                {{ number_format($stats['totalLandSize'], 2) }} Hectares
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-landscape-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Pending Approvals</p>
                        <h4 class="mb-0">{{ number_format($stats['pendingApproval']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-warning">
                                <i class="ri-time-line align-middle"></i> Needs Attention
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-file-list-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Modules -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-apps-line me-1"></i> System Modules
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Farm Practices Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('admin.farm-practices.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                            <i class="ri-plant-line text-success"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Farm Practices</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Agricultural analytics & insights
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- System Staff Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                            <i class="ri-team-line text-primary"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">System Staff</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        LGA Admins & Enrollment Agents
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Cooperatives Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="#" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-info fs-3">
                                            <i class="ri-community-line text-info"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Cooperatives</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Farmer cooperatives management
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Partners Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('admin.vendors.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-warning fs-3">
                                            <i class="ri-handshake-line text-warning"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Vendors</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Strategic partnerships
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Resources Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('admin.resources.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-danger fs-3">
                                            <i class="ri-database-2-line text-danger"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Resources</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Agricultural resources & inputs
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Marketplace Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('admin.marketplace.dashboard') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                            <i class="ri-store-line text-success"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Marketplace</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Agricultural trading platform
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Abattoirs Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="#" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-info fs-3">
                                            <i class="ri-building-line text-info"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Abattoirs</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Abattoir & livestock management
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Support Module -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('admin.support.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                            <i class="ri-customer-service-2-line text-primary"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Support System</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Help desk & user support
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

<!-- Farm Type Distribution & Recent Activity -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line me-1"></i> Farm Type Distribution
                </h5>
            </div>
            <div class="card-body">
                @if($farmTypeDistribution->isNotEmpty())
                    <div class="mb-3">
                        <canvas id="farmTypeChart" height="250"></canvas>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                @foreach($farmTypeDistribution as $type => $count)
                                <tr>
                                    <td style="width: 60%">
                                        @php
                                            $icons = [
                                                'Crops' => 'ri-seedling-line text-success',
                                                'Livestock' => 'ri-bear-smile-line text-warning',
                                                'Fisheries' => 'ri-ship-line text-info',
                                                'Orchards' => 'ri-plant-line text-primary'
                                            ];
                                        @endphp
                                        <i class="{{ $icons[$type] ?? 'ri-plant-line' }} me-2"></i>
                                        <strong>{{ $type }}</strong>
                                    </td>
                                    <td class="text-end" style="width: 40%">
                                        <span class="badge badge-soft-primary">{{ number_format($count) }} farms</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td class="text-end">
                                        <strong>{{ number_format($farmTypeDistribution->sum()) }} farms</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="ri-plant-line fs-1 d-block mb-2"></i>
                        <p class="mb-0">No farm data available yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-user-add-line me-1"></i> Recent Staff Registrations
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Staff Member</th>
                                <th>Role</th>
                                <th>Unit</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStaff as $staff)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-6">
                                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 font-size-14">{{ $staff->name }}</h6>
                                            <small class="text-muted">{{ $staff->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($staff->hasRole('LGA Admin'))
                                        <span class="badge badge-soft-primary">LGA Admin</span>
                                    @elseif($staff->hasRole('Enrollment Agent'))
                                        <span class="badge badge-soft-success">Enrollment Agent</span>
                                    @else
                                        <span class="badge badge-soft-secondary">Staff</span>
                                    @endif
                                </td>
                                <td>
                                    @if($staff->administrativeUnit)
                                        <small>{{ $staff->administrativeUnit->name }}</small>
                                    @else
                                        <small class="text-muted">Not assigned</small>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $staff->created_at->format('M d, Y') }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">
                                    No recent registrations
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

<!-- Additional Statistics -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-primary">{{ number_format($stats['totalLGAs']) }}</h4>
                            <p class="text-muted mb-0">LGAs</p>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-success">{{ number_format($stats['totalCooperatives']) }}</h4>
                            <p class="text-muted mb-0">Cooperatives</p>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-info">{{ number_format($stats['totalMembers']) }}</h4>
                            <p class="text-muted mb-0">Coop Members</p>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-warning">{{ number_format($stats['lgaAdmins']) }}</h4>
                            <p class="text-muted mb-0">LGA Admins</p>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-primary">{{ number_format($stats['enrollmentAgents']) }}</h4>
                            <p class="text-muted mb-0">Agents</p>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="py-3">
                            <h4 class="mb-1 text-success">{{ number_format($stats['totalLandSize'], 2) }}</h4>
                            <p class="text-muted mb-0">Total Hectares</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Module Card Hover Effects
document.addEventListener('DOMContentLoaded', function() {
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
});

// Farm Type Distribution Chart
const farmTypeCtx = document.getElementById('farmTypeChart');
if (farmTypeCtx) {
    const farmTypeData = @json($farmTypeDistribution);
    
    if (Object.keys(farmTypeData).length > 0) {
        new Chart(farmTypeCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(farmTypeData),
                datasets: [{
                    data: Object.values(farmTypeData),
                    backgroundColor: [
                        'rgba(52, 195, 143, 0.8)',   // Success - Crops
                        'rgba(244, 184, 59, 0.8)',   // Warning - Livestock
                        'rgba(80, 165, 241, 0.8)',   // Info - Fisheries
                        'rgba(85, 110, 230, 0.8)'    // Primary - Orchards
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
}
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
@endsection