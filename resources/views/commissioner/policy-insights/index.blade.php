@extends('layouts.commissioner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Policy Insights & Analytics</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('commissioner.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Policy Insights</li>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Demographic Analysis</p>
                        <h6 class="mb-0">Farmers Profile</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-group-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.policy_insights.demographic_analysis') }}" class="btn btn-soft-primary btn-sm">
                        View Analysis <i class="ri-arrow-right-line align-middle"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Youth Engagement</p>
                        <h6 class="mb-0">Young Farmers</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-user-heart-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.policy_insights.youth_engagement') }}" class="btn btn-soft-success btn-sm">
                        View Analysis <i class="ri-arrow-right-line align-middle"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Yield Projections</p>
                        <h6 class="mb-0">Crop Forecasts</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-seedling-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.policy_insights.yield_projections') }}" class="btn btn-soft-info btn-sm">
                        View Analysis <i class="ri-arrow-right-line align-middle"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Production Patterns</p>
                        <h6 class="mb-0">Crop Distribution</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-bar-chart-2-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.policy_insights.production_patterns') }}" class="btn btn-soft-warning btn-sm">
                        View Analysis <i class="ri-arrow-right-line align-middle"></i>
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
                    <i class="ri-lightbulb-line me-1"></i> Policy Insights Dashboard
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="ri-information-line me-2"></i>
                    This dashboard provides comprehensive analytics for evidence-based policy decisions and agricultural planning.
                </div>
                
                <div class="row">
                    <div class="col-lg-6">
                        <div class="border p-3 rounded mb-3">
                            <h6 class="mb-3">Quick Access</h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('commissioner.policy_insights.demographic_analysis') }}?gender=Female" class="btn btn-soft-primary text-start">
                                    <i class="ri-women-line align-middle me-2"></i>
                                    Female Farmers Analysis
                                </a>
                                <a href="{{ route('commissioner.policy_insights.youth_engagement') }}" class="btn btn-soft-success text-start">
                                    <i class="ri-user-heart-line align-middle me-2"></i>
                                    Youth Agriculture Programs
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="border p-3 rounded">
                            <h6 class="mb-3">Recent Insights</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="ri-checkbox-circle-line text-success me-2"></i>
                                    <small>Youth participation increased by 15% this quarter</small>
                                </li>
                                <li class="mb-2">
                                    <i class="ri-checkbox-circle-line text-success me-2"></i>
                                    <small>Female farmer enrollment shows positive growth</small>
                                </li>
                                <li class="mb-2">
                                    <i class="ri-alert-line text-warning me-2"></i>
                                    <small>Northern LGAs show lower resource utilization</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection