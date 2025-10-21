@extends('layouts.commissioner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Commissioner's Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('commissioner.dashboard') }}">Home</a></li>
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

<!-- Comprehensive Analytics Hub -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> Comprehensive Analytics Hub
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Policy Insights -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('commissioner.policy_insights.index') }}" class="text-decoration-none">
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
                        <a href="{{ route('commissioner.interventions.index') }}" class="text-decoration-none">
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

                    <!-- LGA Comparison -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('commissioner.lga_comparison.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-info fs-3">
                                            <i class="ri-map-2-line text-info"></i>
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
                        <a href="{{ route('commissioner.trends.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-warning fs-3">
                                            <i class="ri-line-chart-line text-warning"></i>
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

                    <!-- Advanced Analytics -->
                    <div class="col-xl-3 col-md-6">
                        <a href="" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-danger fs-3">
                                            <i class="ri-bar-chart-2-line text-danger"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Advanced Analytics</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Custom reports & deep insights
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Cooperative Overview -->
                    <div class="col-xl-3 col-md-6">
                        <a href="" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-purple fs-3">
                                            <i class="ri-community-line text-purple"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Cooperatives</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Group performance & impact
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Production Analytics -->
                    <div class="col-xl-3 col-md-6">
                        <a href="" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-orange fs-3">
                                            <i class="ri-seedling-line text-orange"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Production</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Crop & livestock output
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Enrollment Pipeline -->
                    <div class="col-xl-3 col-md-6">
                        <a href="" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-teal fs-3">
                                            <i class="ri-user-add-line text-teal"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Enrollment</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Farmer registration trends
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

<!-- Additional Feature Cards -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-dashboard-line me-1"></i> Strategic Oversight Modules
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Resource Management -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card feature-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-indigo fs-3">
                                        <i class="ri-resource-line text-indigo"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2">Resource Management</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Track agricultural inputs & subsidies
                                </p>
                                <div class="mt-3">
                                    <span class="badge badge-soft-secondary">Coming Soon</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Market Intelligence -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card feature-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-pink fs-3">
                                        <i class="ri-store-2-line text-pink"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2">Market Intelligence</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Price trends & market analysis
                                </p>
                                <div class="mt-3">
                                    <span class="badge badge-soft-secondary">Coming Soon</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Climate & Weather -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card feature-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-cyan fs-3">
                                        <i class="ri-cloud-line text-cyan"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2">Climate Analytics</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Weather patterns & climate impact
                                </p>
                                <div class="mt-3">
                                    <span class="badge badge-soft-secondary">Coming Soon</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Impact -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card feature-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-amber fs-3">
                                        <i class="ri-money-dollar-circle-line text-amber"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2">Financial Impact</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Economic analysis & ROI tracking
                                </p>
                                <div class="mt-3">
                                    <span class="badge badge-soft-secondary">Coming Soon</span>
                                </div>
                            </div>
                        </div>
                    </div>
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

    // Feature Card Hover Effects
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.08)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
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

.feature-card {
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

/* Additional color classes */
.bg-soft-purple {
    background-color: rgba(108, 117, 245, 0.1) !important;
}
.text-purple {
    color: #6c75f5 !important;
}
.bg-soft-orange {
    background-color: rgba(253, 126, 20, 0.1) !important;
}
.text-orange {
    color: #fd7e14 !important;
}
.bg-soft-teal {
    background-color: rgba(32, 201, 151, 0.1) !important;
}
.text-teal {
    color: #20c997 !important;
}
.bg-soft-indigo {
    background-color: rgba(102, 16, 242, 0.1) !important;
}
.text-indigo {
    color: #6610f2 !important;
}
.bg-soft-pink {
    background-color: rgba(232, 62, 140, 0.1) !important;
}
.text-pink {
    color: #e83e8c !important;
}
.bg-soft-cyan {
    background-color: rgba(23, 162, 184, 0.1) !important;
}
.text-cyan {
    color: #17a2b8 !important;
}
.bg-soft-amber {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
.text-amber {
    color: #ffc107 !important;
}
</style>
@endpush