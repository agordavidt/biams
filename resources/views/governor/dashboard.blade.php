@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Governor's Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('governor.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- State-Wide KPIs -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Farmers</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stateKpis['total_farmers'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-{{ $recentTrends['new_farmers']['change_percentage'] >= 0 ? 'success' : 'danger' }}">
                                <i class="ri-{{ $recentTrends['new_farmers']['change_percentage'] >= 0 ? 'arrow-up' : 'arrow-down' }}-line align-middle"></i>
                                {{ abs($recentTrends['new_farmers']['change_percentage']) }}%
                            </span>
                            <span class="ms-1 text-muted font-size-12">vs last month</span>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Hectares</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ number_format($stateKpis['total_hectares'], 0) }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">Under cultivation</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-plant-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Active Resources</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stateKpis['active_resources'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-warning">
                                <span class="counter-value" data-target="{{ $stateKpis['pending_applications'] }}">0</span>
                            </span>
                            <span class="ms-1 text-muted font-size-12">Pending</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-database-2-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Cooperatives</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stateKpis['total_cooperatives'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">Active organizations</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-community-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Critical Alerts -->
@if($criticalAlerts['expiring_resources'] > 0 || $criticalAlerts['pending_approvals'] > 0)
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="ri-alert-line me-2"></i>
            <strong>Attention Required:</strong>
            @if($criticalAlerts['expiring_resources'] > 0)
                <span class="me-3">{{ $criticalAlerts['expiring_resources'] }} resource(s) expiring within 7 days</span>
            @endif
            @if($criticalAlerts['pending_approvals'] > 0)
                <span>{{ $criticalAlerts['pending_approvals'] }} farmer(s) awaiting LGA review</span>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<!-- Quick Access Modules -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-apps-line me-1"></i> Quick Access
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Policy Insights -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('governor.policy_insights.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                            <i class="ri-lightbulb-line text-primary"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Policy Insights</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Demographics & production analysis
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Intervention Tracking -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('governor.interventions.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                            <i class="ri-heart-pulse-line text-success"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Interventions</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Program effectiveness & reach
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Resources Overview -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('governor.resources.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-info fs-3">
                                            <i class="ri-database-2-line text-info"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Resources</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Distribution & beneficiaries
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Vendors Overview -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('governor.vendors.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-warning fs-3">
                                            <i class="ri-store-2-line text-warning"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Vendors</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Partner performance & impact
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- LGA Comparison -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('governor.lga_comparison.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-secondary fs-3">
                                            <i class="ri-map-2-line text-secondary"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">LGA Comparison</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Regional performance ranking
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Trend Analysis -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('governor.trends.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-danger fs-3">
                                            <i class="ri-line-chart-line text-danger"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Trends</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Temporal patterns & forecasts
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Cooperatives Overview -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('governor.cooperatives.overview') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                            <i class="ri-community-line text-success"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Cooperatives</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Organization performance
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Export Data -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('analytics.export') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                            <i class="ri-download-2-line text-primary"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Export Data</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Download state reports
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

<!-- Top Performing LGAs Snapshot -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <i class="ri-trophy-line me-1"></i> Top Performing LGAs
                </h5>
                <a href="{{ route('governor.lga_comparison.index') }}" class="btn btn-sm btn-soft-primary">
                    View Full Comparison
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Rank</th>
                                <th>LGA</th>
                                <th class="text-end">Farmers</th>
                                <th class="text-end">Hectares</th>
                                <th class="text-end">Avg. Farm Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lgaSnapshot as $index => $lga)
                            <tr>
                                <td>
                                    <span class="badge badge-soft-{{ $index === 0 ? 'success' : ($index === 1 ? 'info' : 'secondary') }}">
                                        #{{ $index + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <h6 class="mb-0">{{ $lga['lga_name'] }}</h6>
                                </td>
                                <td class="text-end">
                                    <span class="fw-semibold">{{ number_format($lga['farmer_count']) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-muted">-</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-muted">-</span>
                                </td>
                            </tr>
                            @endforeach
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
            const data = +counter.innerText.replace(/,/g, '');
            const time = value / speed;
            
            if(data < value) {
                counter.innerText = Math.ceil(data + time).toLocaleString();
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