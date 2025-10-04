@extends('layouts.super_admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Custom Analytics Results</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('analytics.dashboard') }}">Analytics</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('analytics.advanced.index') }}">Advanced Filter</a></li>
                        <li class="breadcrumb-item active">Results</li>
                    </ol>
                </div>
            </div>
            <p class="text-muted mb-0">Filtered analysis based on your custom criteria</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="hstack gap-3 justify-content-end">
                <a href="{{ route('analytics.advanced.index') }}" class="btn btn-light">
                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Filters
                </a>
                @can('export_analytics')
                <a href="{{ route('analytics.advanced.export', request()->query()) }}" class="btn btn-success">
                    <i class="ri-download-line align-middle me-1"></i> Export Results
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- Applied Filters Summary --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light bg-opacity-10 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-filter-3-line text-primary me-2"></i>
                        <h5 class="card-title mb-0">Applied Filters</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($results['summary']['filters_applied'] as $key => $value)
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="border rounded p-2 bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block">{{ ucwords(str_replace('_', ' ', $key)) }}</small>
                                        <span class="fw-semibold">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <!-- Total Farmers Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Farmers</p>
                            <h4 class="mt-2 mb-0">{{ number_format($results['summary']['total_farmers']) }}</h4>
                            <p class="mb-0">
                                <span class="badge bg-primary bg-opacity-10 text-primary mb-0">
                                    <i class="ri-user-line align-middle"></i> Registered
                                </span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10">
                                <span class="avatar-title rounded-circle bg-primary bg-opacity-10 text-primary font-size-20">
                                    <i class="ri-team-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Farms Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Farms</p>
                            <h4 class="mt-2 mb-0">{{ number_format($results['summary']['total_farms']) }}</h4>
                            <p class="text-muted mb-0">Active operations</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10">
                                <span class="avatar-title rounded-circle bg-success bg-opacity-10 text-success font-size-20">
                                    <i class="ri-seedling-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Land Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Land Area</p>
                            <h4 class="mt-2 mb-0">{{ number_format($results['summary']['total_land_hectares'], 2) }} Ha</h4>
                            <p class="text-muted mb-0">Cultivated area</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info bg-opacity-10">
                                <span class="avatar-title rounded-circle bg-info bg-opacity-10 text-info font-size-20">
                                    <i class="ri-landscape-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average Land Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Avg Land/Farmer</p>
                            <h4 class="mt-2 mb-0">{{ number_format($results['summary']['average_land_per_farmer'], 2) }} Ha</h4>
                            <p class="text-muted mb-0">Per farmer average</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-opacity-10">
                                <span class="avatar-title rounded-circle bg-warning bg-opacity-10 text-warning font-size-20">
                                    <i class="ri-dashboard-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Demographics Analysis --}}
    <div class="row mb-4">
        <!-- Gender Distribution -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <i class="ri-men-line text-primary me-2"></i>
                        <h5 class="card-title mb-0">Gender Distribution</h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="genderChart" height="250"></canvas>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1">{{ $results['demographics']['gender_distribution']['male'] }}</h4>
                                    <small class="text-muted">Male</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h4 class="text-success mb-1">{{ $results['demographics']['gender_distribution']['female'] }}</h4>
                                    <small class="text-muted">Female</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h4 class="text-info mb-1">{{ $results['demographics']['gender_distribution']['other'] }}</h4>
                                <small class="text-muted">Other</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <i class="ri-user-star-line text-success me-2"></i>
                        <h5 class="card-title mb-0">Age Distribution</h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="ageChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Education & Marital Status --}}
    <div class="row mb-4">
        <!-- Education Level -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <i class="ri-graduation-cap-line text-info me-2"></i>
                        <h5 class="card-title mb-0">Education Level</h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="educationChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Marital Status -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <i class="ri-heart-line text-warning me-2"></i>
                        <h5 class="card-title mb-0">Marital Status</h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="maritalChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Production Analysis --}}
    @if(isset($results['production_analysis']) && !empty($results['production_analysis']))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success bg-opacity-10 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-bar-chart-line text-success me-2"></i>
                        <h5 class="card-title mb-0">Production Analysis</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($results['production_analysis']['crop_details']))
                        @php $crop = $results['production_analysis']['crop_details']; @endphp
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="ri-seedling-line align-middle me-1"></i>
                                {{ ucwords(str_replace('_', ' ', $crop['crop_type'])) }} Production Details
                            </h6>
                            <div class="row g-3 mb-4">
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border border-primary border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-primary mb-1">{{ number_format($crop['total_farmers']) }}</h3>
                                            <small class="text-muted">Total Farmers</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border border-success border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-success mb-1">{{ number_format($crop['total_area_hectares'], 2) }}</h3>
                                            <small class="text-muted">Total Area (Ha)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border border-info border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-info mb-1">{{ number_format($crop['total_expected_yield_kg'], 2) }}</h3>
                                            <small class="text-muted">Expected Yield (Kg)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card border border-warning border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-warning mb-1">{{ number_format($crop['yield_per_hectare'], 2) }}</h3>
                                            <small class="text-muted">Kg per Hectare</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-3">Farming Methods Breakdown</h6>
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Farming Method</th>
                                            <th>Farmers</th>
                                            <th>Area (Ha)</th>
                                            <th>Expected Yield (Kg)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($crop['methods_breakdown'] as $method)
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">{{ ucwords(str_replace('_', ' ', $method['method'])) }}</span>
                                            </td>
                                            <td>{{ number_format($method['farmers']) }}</td>
                                            <td>{{ number_format($method['area_ha'], 2) }}</td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                    {{ number_format($method['expected_yield_kg'], 2) }} Kg
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if(isset($results['production_analysis']['livestock_details']))
                        @php $livestock = $results['production_analysis']['livestock_details']; @endphp
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="ri-bear-smile-line align-middle me-1"></i>
                                {{ ucwords(str_replace('_', ' ', $livestock['animal_type'])) }} Production Details
                            </h6>
                            <div class="row g-3 mb-4">
                                <div class="col-xl-4 col-md-4">
                                    <div class="card border border-primary border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-primary mb-1">{{ number_format($livestock['total_farmers']) }}</h3>
                                            <small class="text-muted">Total Farmers</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="card border border-success border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-success mb-1">{{ number_format($livestock['total_animals']) }}</h3>
                                            <small class="text-muted">Total Animals</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="card border border-info border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-info mb-1">{{ number_format($livestock['average_herd_size'], 2) }}</h3>
                                            <small class="text-muted">Avg Herd Size</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-3">Breeding Methods</h6>
                            <div class="table-responsive">
                                <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Breeding Method</th>
                                            <th>Farmers</th>
                                            <th>Total Animals</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($livestock['breeding_methods'] as $method)
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">{{ ucwords(str_replace('_', ' ', $method['method'])) }}</span>
                                            </td>
                                            <td>{{ number_format($method['farmers']) }}</td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                    {{ number_format($method['total_animals']) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if(isset($results['production_analysis']['fisheries_details']))
                        @php $fisheries = $results['production_analysis']['fisheries_details']; @endphp
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="ri-fish-line align-middle me-1"></i>
                                Fisheries Production Details
                            </h6>
                            <div class="row g-3">
                                <div class="col-xl-4 col-md-4">
                                    <div class="card border border-primary border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-primary mb-1">{{ number_format($fisheries['total_farmers']) }}</h3>
                                            <small class="text-muted">Total Farmers</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="card border border-success border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-success mb-1">{{ number_format($fisheries['total_pond_area_sqm'], 2) }}</h3>
                                            <small class="text-muted">Pond Area (sqm)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="card border border-info border-opacity-25">
                                        <div class="card-body text-center">
                                            <h3 class="text-info mb-1">{{ number_format($fisheries['total_expected_harvest_kg'], 2) }}</h3>
                                            <small class="text-muted">Expected Harvest (Kg)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Geographic Distribution --}}
    @if(count($results['geographic_distribution']['lga_breakdown']) > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light bg-opacity-10 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-map-pin-line text-info me-2"></i>
                        <h5 class="card-title mb-0">Geographic Distribution</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            <i class="ri-map-pin-line align-middle me-1"></i>
                            Coverage: {{ $results['geographic_distribution']['total_lgas_covered'] }} LGA(s)
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Local Government Area</th>
                                    <th>Farmer Count</th>
                                    <th>Total Land (Ha)</th>
                                    <th>Avg Land per Farmer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results['geographic_distribution']['lga_breakdown'] as $lgaName => $stats)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="avatar-xs">
                                                    <div class="avatar-title bg-info bg-opacity-10 rounded text-info">
                                                        <i class="ri-government-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">{{ $lgaName }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ number_format($stats['count']) }}</span>
                                    </td>
                                    <td>{{ number_format($stats['total_land_ha'], 2) }} Ha</td>
                                    <td>
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            {{ number_format($stats['avg_land_ha'], 2) }} Ha
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
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Gender Chart
    const genderCtx = document.getElementById('genderChart');
    if (genderCtx) {
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male Farmers', 'Female Farmers', 'Other'],
                datasets: [{
                    data: [
                        {{ $results['demographics']['gender_distribution']['male'] }},
                        {{ $results['demographics']['gender_distribution']['female'] }},
                        {{ $results['demographics']['gender_distribution']['other'] }}
                    ],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '65%',
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

    // Age Chart
    const ageCtx = document.getElementById('ageChart');
    if (ageCtx) {
        new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: ['18-25', '26-35', '36-45', '46-55', '56+'],
                datasets: [{
                    label: 'Farmers',
                    data: [
                        {{ $results['demographics']['age_distribution']['18-25'] }},
                        {{ $results['demographics']['age_distribution']['26-35'] }},
                        {{ $results['demographics']['age_distribution']['36-45'] }},
                        {{ $results['demographics']['age_distribution']['46-55'] }},
                        {{ $results['demographics']['age_distribution']['56+'] }}
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

    // Education Chart
    const educationCtx = document.getElementById('educationChart');
    if (educationCtx) {
        new Chart(educationCtx, {
            type: 'bar',
            data: {
                labels: ['None', 'Primary', 'Secondary', 'Tertiary', 'Vocational'],
                datasets: [{
                    label: 'Farmers',
                    data: [
                        {{ $results['demographics']['education_distribution']['none'] }},
                        {{ $results['demographics']['education_distribution']['primary'] }},
                        {{ $results['demographics']['education_distribution']['secondary'] }},
                        {{ $results['demographics']['education_distribution']['tertiary'] }},
                        {{ $results['demographics']['education_distribution']['vocational'] }}
                    ],
                    backgroundColor: '#1cc88a',
                    borderColor: '#1cc88a',
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

    // Marital Status Chart
    const maritalCtx = document.getElementById('maritalChart');
    if (maritalCtx) {
        new Chart(maritalCtx, {
            type: 'pie',
            data: {
                labels: ['Single', 'Married', 'Divorced', 'Widowed'],
                datasets: [{
                    data: [
                        {{ $results['demographics']['marital_status_distribution']['single'] }},
                        {{ $results['demographics']['marital_status_distribution']['married'] }},
                        {{ $results['demographics']['marital_status_distribution']['divorced'] }},
                        {{ $results['demographics']['marital_status_distribution']['widowed'] }}
                    ],
                    backgroundColor: ['#f6c23e', '#4e73df', '#36b9cc', '#e74a3b'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                maintainAspectRatio: false,
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

<style>
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
    }
    
    .card-animate {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card-animate:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
    }
</style>
@endpush