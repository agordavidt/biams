@extends('layouts.commissioner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Trend Analysis & Forecasting</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('commissioner.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Trend Analysis</li>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Enrollment Trends</p>
                        <h6 class="mb-0">Farmer Registration</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-user-add-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.trends.enrollment_trends') }}" class="btn btn-soft-primary btn-sm">
                        View Trends <i class="ri-arrow-right-line align-middle"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Production Trends</p>
                        <h6 class="mb-0">Crop Yields</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-bar-chart-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.trends.production_trends') }}" class="btn btn-soft-success btn-sm">
                        View Trends <i class="ri-arrow-right-line align-middle"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Resource Utilization</p>
                        <h6 class="mb-0">Program Usage</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-resource-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.trends.resource_utilization_trends') }}" class="btn btn-soft-info btn-sm">
                        View Trends <i class="ri-arrow-right-line align-middle"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Gender Parity</p>
                        <h6 class="mb-0">Gender Analysis</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-women-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.trends.gender_parity_trends') }}" class="btn btn-soft-warning btn-sm">
                        View Trends <i class="ri-arrow-right-line align-middle"></i>
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
                    <i class="ri-line-chart-line me-1"></i> Temporal Analysis Dashboard
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="ri-information-line me-2"></i>
                    Monitor temporal patterns, identify seasonal variations, and forecast future agricultural trends for strategic planning.
                </div>
                
                <div class="row">
                    <div class="col-lg-8">
                        <div class="border rounded p-3 mb-3">
                            <h6 class="mb-3">Analysis Period</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" value="{{ date('Y-m-d', strtotime('-1 year')) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="border rounded p-3">
                            <h6 class="mb-3">Quick Metrics</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Monthly Growth:</small>
                                <small class="fw-semibold text-success">+8.2%</small>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Quarterly Trend:</small>
                                <small class="fw-semibold text-success">Positive</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Forecast Accuracy:</small>
                                <small class="fw-semibold text-info">92%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection