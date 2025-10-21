@extends('layouts.commissioner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Intervention Program Tracking</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('commissioner.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Intervention Tracking</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Beneficiary Reports</p>
                        <h6 class="mb-0">Program Impact</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-user-star-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.interventions.beneficiary_report') }}" class="btn btn-soft-primary btn-sm">
                        View Reports <i class="ri-arrow-right-line align-middle"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Partner Activities</p>
                        <h6 class="mb-0">Partner Performance</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-handshake-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.interventions.partner_activities') }}" class="btn btn-soft-success btn-sm">
                        View Partners <i class="ri-arrow-right-line align-middle"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Coverage Analysis</p>
                        <h6 class="mb-0">Program Reach</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-radar-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.interventions.coverage_analysis') }}" class="btn btn-soft-info btn-sm">
                        View Coverage <i class="ri-arrow-right-line align-middle"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Program Effectiveness</p>
                        <h6 class="mb-0">Success Metrics</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-medal-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.interventions.index') }}" class="btn btn-soft-warning btn-sm">
                        View Metrics <i class="ri-arrow-right-line align-middle"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-heart-pulse-line me-1"></i> Intervention Program Dashboard
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="ri-information-line me-2"></i>
                    Monitor the effectiveness of agricultural intervention programs, track resource distribution, and measure impact across the state.
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <div class="border rounded p-3 mb-3">
                            <h6 class="mb-3">Program Overview</h6>
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1">12</h4>
                                        <small class="text-muted">Active Programs</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-success mb-1">8,450</h4>
                                        <small class="text-muted">Total Beneficiaries</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-info mb-1">15</h4>
                                        <small class="text-muted">Partner Organizations</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div>
                                        <h4 class="text-warning mb-1">78%</h4>
                                        <small class="text-muted">Success Rate</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded p-3">
                            <h6 class="mb-3">Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('commissioner.interventions.beneficiary_report') }}" class="btn btn-soft-primary btn-sm text-start">
                                    <i class="ri-file-chart-line align-middle me-2"></i>
                                    Generate Beneficiary Report
                                </a>
                                <a href="{{ route('commissioner.interventions.coverage_analysis') }}" class="btn btn-soft-success btn-sm text-start">
                                    <i class="ri-map-pin-line align-middle me-2"></i>
                                    Coverage Gap Analysis
                                </a>
                                <a href="{{ route('commissioner.interventions.partner_activities') }}" class="btn btn-soft-info btn-sm text-start">
                                    <i class="ri-handshake-line align-middle me-2"></i>
                                    Partner Performance Review
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection