@extends('layouts.super_admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Analytics Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>
            <p class="text-muted mb-0">
                @if($role === 'Super Admin' || $role === 'Governor' || $role === 'State Admin')
                    State-wide Agricultural Insights and Performance Metrics
                @elseif($role === 'LGA Admin')
                    {{ auth()->user()->administrativeUnit->name ?? 'LGA' }} Analytics Overview
                @else
                    Your Enrollment Performance Dashboard
                @endif
            </p>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row">
        @if(isset($data['demographics']) && is_array($data['demographics']))
        <!-- Total Farmers Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Farmers</p>
                            <h4 class="mt-2 mb-0">{{ number_format($data['demographics']['total_farmers'] ?? 0) }}</h4>
                            <p class="mb-0">
                                <span class="badge bg-success-subtle text-success mb-0">
                                    <i class="ri-arrow-up-line align-middle"></i> Active
                                </span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10">
                                <span class="avatar-title rounded-circle bg-primary bg-opacity-10 text-primary font-size-20">
                                    <i class="ri-user-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Gender Ratio</p>
                            <h6 class="mt-2 mb-1 text-muted">
                                M: {{ number_format($data['demographics']['male_count'] ?? 0) }} | 
                                F: {{ number_format($data['demographics']['female_count'] ?? 0) }}
                            </h6>
                            <div class="progress progress-sm mt-2">
                                @php
                                    $total = ($data['demographics']['male_count'] ?? 0) + ($data['demographics']['female_count'] ?? 0);
                                    $malePercent = $total > 0 ? (($data['demographics']['male_count'] ?? 0) / $total) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $malePercent }}%"></div>
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ 100 - $malePercent }}%"></div>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10">
                                <span class="avatar-title rounded-circle bg-success bg-opacity-10 text-success font-size-20">
                                    <i class="ri-team-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(isset($data['production']) && is_array($data['production']))
        <!-- Total Farmland Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Farmland</p>
                            <h4 class="mt-2 mb-0">{{ number_format($data['production']['total_land_ha'] ?? 0, 2) }} Ha</h4>
                            <p class="text-muted mb-0">Avg: {{ number_format($data['production']['state_avg_farm_size'] ?? 0, 2) }} Ha/farm</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-opacity-10">
                                <span class="avatar-title rounded-circle bg-warning bg-opacity-10 text-warning font-size-20">
                                    <i class="ri-landscape-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Farm Types Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Active Farms</p>
                            <h4 class="mt-2 mb-0">{{ number_format($data['production']['total_farms'] ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                Crops: {{ number_format($data['production']['total_crop_farms'] ?? 0) }} | 
                                Livestock: {{ number_format($data['production']['total_livestock_farms'] ?? 0) }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info bg-opacity-10">
                                <span class="avatar-title rounded-circle bg-info bg-opacity-10 text-info font-size-20">
                                    <i class="ri-home-gear-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Charts and Detailed Data Row -->
    <div class="row mt-4">
        <!-- Gender Distribution Chart -->
        @if(isset($data['demographics']) && is_array($data['demographics']))
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gender Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="genderChart" height="300"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Farm Type Distribution Chart -->
        @if(isset($data['production']) && is_array($data['production']))
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Farm Type Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="farmTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Top Crops Section -->
    @if(isset($data['top_crops']) && count($data['top_crops']) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Top Crops by Expected Yield</h5>
                    <a href="{{ route('analytics.crops') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Crop Type</th>
                                    <th>Farmers</th>
                                    <th>Total Area (Ha)</th>
                                    <th>Expected Yield (Kg)</th>
                                    <th>Avg Yield/Ha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['top_crops'] as $crop)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title bg-primary bg-opacity-10 rounded text-primary">
                                                        <i class="ri-seedling-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">{{ ucwords(str_replace('_', ' ', $crop->crop_type)) }}</div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($crop->farmer_count) }}</td>
                                    <td>{{ number_format($crop->total_area_ha, 2) }}</td>
                                    <td>{{ number_format($crop->total_expected_yield_kg, 2) }}</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success">
                                            {{ number_format($crop->avg_yield_per_ha, 2) }} Kg/Ha
                                        </span>
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
    @endif

    <!-- LGA Comparison Section (State-level only) -->
    @if(isset($data['lga_breakdown']) && count($data['lga_breakdown']) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">LGA Performance Overview</h5>
                    <a href="{{ route('analytics.lga_comparison') }}" class="btn btn-sm btn-primary">Detailed View</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>LGA</th>
                                    <th>Total Farmers</th>
                                    <th>Performance</th>
                                    <th>Rank</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['lga_breakdown'] as $index => $lga)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title bg-info bg-opacity-10 rounded text-info">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">{{ $lga->lga_name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($lga->total_farmers) }}</td>
                                    <td>
                                        <div class="progress progress-sm" style="height: 5px;">
                                            @php
                                                $maxFarmers = max(array_column($data['lga_breakdown'], 'total_farmers'));
                                                $percentage = $maxFarmers > 0 ? ($lga->total_farmers / $maxFarmers) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $index < 3 ? 'success' : 'secondary' }}-subtle text-{{ $index < 3 ? 'success' : 'secondary' }}">
                                            #{{ $index + 1 }}
                                        </span>
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
    @endif

    <!-- Agent Performance Section (LGA Admin only) -->
    @if(isset($data['agents_performance']) && count($data['agents_performance']) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Enrollment Agents Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Agent Name</th>
                                    <th>Active</th>
                                    <th>Pending Review</th>
                                    <th>Pending Activation</th>
                                    <th>Rejected</th>
                                    <th>This Month</th>
                                    <th>Approval Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['agents_performance'] as $agent)
                                <tr>
                                    <td>{{ $agent->name }}</td>
                                    <td><span class="badge bg-success">{{ $agent->active_count }}</span></td>
                                    <td><span class="badge bg-warning">{{ $agent->pending_review_count }}</span></td>
                                    <td><span class="badge bg-info">{{ $agent->pending_activation_count }}</span></td>
                                    <td><span class="badge bg-danger">{{ $agent->rejected_count }}</span></td>
                                    <td>{{ $agent->new_enrollments_month }}</td>
                                    <td>
                                        <span class="badge bg-{{ $agent->approval_rate >= 80 ? 'success' : ($agent->approval_rate >= 60 ? 'warning' : 'danger') }}">
                                            {{ number_format($agent->approval_rate, 1) }}%
                                        </span>
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
    @endif

    <!-- Export Section -->
    @can('export_analytics')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <h5>Export Analytics Data</h5>
                            <p class="text-muted mb-4">Download comprehensive reports in your preferred format for further analysis</p>
                            <div class="hstack gap-3 justify-content-center">
                                <a href="{{ route('analytics.export', ['type' => 'comprehensive', 'format' => 'csv']) }}" 
                                   class="btn btn-primary">
                                    <i class="ri-download-line align-middle me-1"></i> Export CSV
                                </a>
                                <a href="{{ route('analytics.export', ['type' => 'comprehensive', 'format' => 'json']) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="ri-download-line align-middle me-1"></i> Export JSON
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Gender Distribution Chart
    @if(isset($data['demographics']) && is_array($data['demographics']))
    const genderCtx = document.getElementById('genderChart');
    if (genderCtx) {
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male Farmers', 'Female Farmers'],
                datasets: [{
                    data: [
                        {{ $data['demographics']['male_count'] ?? 0 }},
                        {{ $data['demographics']['female_count'] ?? 0 }}
                    ],
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
    @endif

    // Farm Type Distribution Chart
    @if(isset($data['production']) && is_array($data['production']))
    const farmTypeCtx = document.getElementById('farmTypeChart');
    if (farmTypeCtx) {
        new Chart(farmTypeCtx, {
            type: 'bar',
            data: {
                labels: ['Crop Farms', 'Livestock Farms', 'Fisheries', 'Orchards', 'Forestry'],
                datasets: [{
                    label: 'Number of Farms',
                    data: [
                        {{ $data['production']['total_crop_farms'] ?? 0 }},
                        {{ $data['production']['total_livestock_farms'] ?? 0 }},
                        {{ $data['production']['farms_fisheries'] ?? 0 }},
                        {{ $data['production']['farms_orchards'] ?? 0 }},
                        {{ $data['production']['farms_forestry'] ?? 0 }}
                    ],
                    backgroundColor: '#4e73df',
                    borderColor: '#4e73df',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    @endif

    // Add animation to cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card-animate');
        cards.forEach(card => {
            card.style.transform = 'translateY(0)';
            card.style.opacity = '1';
            card.style.transition = 'all 0.3s ease';
        });
    });
</script>
@endpush