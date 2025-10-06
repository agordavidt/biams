@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">LGA Admin Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- LGA Info Banner -->
<div class="row">
    <div class="col-12">
        <div class="card bg-soft-primary border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-md">
                            <span class="avatar-title rounded-circle bg-primary fs-2">
                                <i class="ri-map-pin-line text-white"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ $lgaName }} Local Government</h4>
                        <p class="text-muted mb-0">
                            <i class="ri-user-line me-1"></i>LGA Administrator: <strong>{{ Auth::user()->name }}</strong>
                        </p>
                    </div>
                    <div class="flex-shrink-0 d-none d-md-block">
                        <span class="badge badge-soft-primary px-3 py-2">
                            <i class="ri-shield-check-line align-middle me-1"></i> Administrator
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Pending Review</p>
                        <h4 class="mb-0">{{ $pendingCount ?? 0 }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-warning">
                                <i class="ri-time-line align-middle"></i> Needs Attention
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-time-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Active Farmers</p>
                        <h4 class="mb-0">{{ $activeCount ?? 0 }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">
                                <i class="ri-checkbox-circle-line align-middle"></i> Verified
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-user-check-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Rejected</p>
                        <h4 class="mb-0">{{ $rejectedCount ?? 0 }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                Sent back to agents
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger rounded-circle fs-3">
                                <i class="ri-close-circle-line text-danger"></i>
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
                    <i class="ri-settings-3-line me-1"></i> Management Modules
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Review Submissions -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('lga_admin.farmers.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100 {{ ($pendingCount ?? 0) > 0 ? 'border-warning' : '' }}">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-warning fs-3">
                                            <i class="ri-file-list-line text-warning"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Review Submissions</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        {{ $pendingCount ?? 0 }} pending approval
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Manage Agents -->
                    <div class="col-xl-3 col-md-6">
                        <a href="{{ route('lga_admin.agents.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                            <i class="ri-user-settings-line text-primary"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Enrollment Agents</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Manage your agents
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- View Farmers -->
                    <div class="col-xl-3 col-md-6">
                        <a href="#" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                            <i class="ri-team-line text-success"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">All Farmers</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        View farmer database
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Manifests -->
                    <div class="col-xl-3 col-md-6">
                        <a href="#" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-info fs-3">
                                            <i class="ri-file-list-3-line text-info"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Manifests</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Resource distribution
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

<!-- Quick Actions & Responsibilities -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-flashlight-line me-1"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if(($pendingCount ?? 0) > 0)
                    <a href="{{ route('lga_admin.farmers.index') }}" class="btn btn-warning">
                        <i class="ri-notification-line me-1"></i>
                        Review {{ $pendingCount }} Pending Submission{{ $pendingCount > 1 ? 's' : '' }}
                    </a>
                    @endif
                    <a href="{{ route('lga_admin.farmers.index') }}" class="btn btn-primary">
                        <i class="ri-list-check-2 me-1"></i>
                        Manage Farmer Submissions
                    </a>
                    <a href="{{ route('lga_admin.agents.index') }}" class="btn btn-soft-primary">
                        <i class="ri-user-settings-line me-1"></i>
                        Manage Enrollment Agents
                    </a>
                    <a href="#" class="btn btn-soft-success">
                        <i class="ri-team-line me-1"></i>
                        View All Farmers
                    </a>
                    <a href="#" class="btn btn-soft-info">
                        <i class="ri-file-list-line me-1"></i>
                        View Distribution Manifests
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-list-check me-1"></i> Your Responsibilities
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-line text-success fs-5 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Manage Enrollment Agents</h6>
                                <p class="text-muted mb-0 font-size-13">Oversee agents in {{ $lgaName }} LGA</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-line text-success fs-5 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Verify & Activate Farmers</h6>
                                <p class="text-muted mb-0 font-size-13">Review and approve farmer profiles</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-line text-success fs-5 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Monitor LGA Data</h6>
                                <p class="text-muted mb-0 font-size-13">View farmer data within your jurisdiction</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-line text-success fs-5 me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Resource Distribution</h6>
                                <p class="text-muted mb-0 font-size-13">Manage distribution manifests</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Overview Statistics -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> {{ $lgaName }} LGA Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-xl-4 col-md-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-warning">{{ $pendingCount ?? 0 }}</h4>
                            <p class="text-muted mb-0">Pending Review</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-success">{{ $activeCount ?? 0 }}</h4>
                            <p class="text-muted mb-0">Active Farmers</p>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="py-3">
                            <h4 class="mb-1 text-danger">{{ $rejectedCount ?? 0 }}</h4>
                            <p class="text-muted mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Getting Started Info -->
<div class="row">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                <i class="ri-information-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-2">
                            <i class="ri-lightbulb-line me-1"></i> Getting Started
                        </h5>
                        <p class="card-text mb-0">
                            As an LGA Administrator for <strong>{{ $lgaName }}</strong>, you have oversight of farmer registrations and resource distribution within your local government area. Your primary responsibility is to review and approve farmer enrollments submitted by your enrollment agents, ensuring data accuracy and completeness before activation.
                        </p>
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
            if (!this.classList.contains('border-warning') && !this.classList.contains('border-primary')) {
                this.style.borderColor = '#556ee6';
            }
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
            if (!this.classList.contains('border-warning') && !this.classList.contains('border-primary')) {
                this.style.borderColor = '#e5e7eb';
            }
        });
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