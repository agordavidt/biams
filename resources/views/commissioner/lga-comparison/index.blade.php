@extends('layouts.commissioner')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">LGA Performance Comparison</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('commissioner.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">LGA Comparison</li>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Performance Ranking</p>
                        <h6 class="mb-0">LGA Rankings</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-trophy-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.lga_comparison.performance_ranking') }}" class="btn btn-soft-primary btn-sm">
                        View Ranking <i class="ri-arrow-right-line align-middle"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Capacity Analysis</p>
                        <h6 class="mb-0">LGA Capacity</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-building-2-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.lga_comparison.capacity_analysis') }}" class="btn btn-soft-success btn-sm">
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Compare LGAs</p>
                        <h6 class="mb-0">Side-by-Side</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-scale-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.lga_comparison.compare_lgas') }}" class="btn btn-soft-info btn-sm">
                        Compare <i class="ri-arrow-right-line align-middle"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Geographic Analysis</p>
                        <h6 class="mb-0">Spatial Distribution</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-map-pin-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('commissioner.lga_comparison.geographic_analysis') }}" class="btn btn-soft-warning btn-sm">
                        View Map <i class="ri-arrow-right-line align-middle"></i>
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
                    <i class="ri-map-2-line me-1"></i> Regional Performance Dashboard
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <i class="ri-information-line me-2"></i>
                    Compare Local Government Area performance, identify regional disparities, and allocate resources effectively across the state.
                </div>
                
                <div class="row">
                    <div class="col-lg-6">
                        <div class="border p-3 rounded mb-3">
                            <h6 class="mb-3">Top Performing LGAs</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>LGA</th>
                                            <th class="text-end">Farmers</th>
                                            <th class="text-end">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="badge bg-success">1</span></td>
                                            <td>Makurdi</td>
                                            <td class="text-end">2,450</td>
                                            <td class="text-end">98.5</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-info">2</span></td>
                                            <td>Gboko</td>
                                            <td class="text-end">1,890</td>
                                            <td class="text-end">95.2</td>
                                        </tr>
                                        <tr>
                                            <td><span class="badge bg-primary">3</span></td>
                                            <td>Otukpo</td>
                                            <td class="text-end">1,567</td>
                                            <td class="text-end">92.8</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="border p-3 rounded">
                            <h6 class="mb-3">Comparison Tools</h6>
                            <div class="d-grid gap-2">
                                <a href="{{ route('commissioner.lga_comparison.compare_lgas') }}" class="btn btn-soft-primary text-start">
                                    <i class="ri-scale-line align-middle me-2"></i>
                                    Compare Multiple LGAs
                                </a>
                                <a href="{{ route('commissioner.lga_comparison.performance_ranking') }}" class="btn btn-soft-success text-start">
                                    <i class="ri-trophy-line align-middle me-2"></i>
                                    View Full Performance Ranking
                                </a>
                                <a href="{{ route('commissioner.lga_comparison.capacity_analysis') }}" class="btn btn-soft-info text-start">
                                    <i class="ri-building-2-line align-middle me-2"></i>
                                    Capacity & Resource Analysis
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