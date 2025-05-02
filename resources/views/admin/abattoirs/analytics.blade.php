@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Abattoir Analytics</h4>
                <div>
                    <a href="{{ route('admin.abattoirs.analytics.livestock') }}" class="btn btn-info">Livestock Report</a>
                    <a href="{{ route('admin.abattoirs.analytics.slaughter') }}" class="btn btn-info">Slaughter Report</a>
                    <a href="{{ route('admin.abattoirs.index') }}" class="btn btn-secondary">Back to Abattoirs</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filter Options</h5>
                    <form method="GET" action="{{ route('admin.abattoirs.analytics') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <select name="date_range" class="form-select" onchange="toggleDateInputs(this.value)">
                                <option value="day" {{ $dateRange == 'day' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ $dateRange == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ $dateRange == 'month' ? 'selected' : '' }}>This Month</option>
                                <option value="year" {{ $dateRange == 'year' ? 'selected' : '' }}>This Year</option>
                                <option value="custom" {{ ($startDate && $endDate && $dateRange == 'custom') ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-3 custom-dates" style="{{ ($dateRange == 'custom') ? '' : 'display: none;' }}">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-3 custom-dates" style="{{ ($dateRange == 'custom') ? '' : 'display: none;' }}">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">LGA Filter</label>
                            <select name="lga" class="form-select">
                                <option value="">All LGAs</option>
                                @foreach($lgas as $lga)
                                    <option value="{{ $lga }}" {{ $selectedLga == $lga ? 'selected' : '' }}>{{ $lga }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Abattoir Filter</label>
                            <select name="abattoir_id" class="form-select">
                                <option value="">All Abattoirs</option>
                                @foreach($abattoirs as $abattoir)
                                    <option value="{{ $abattoir->id }}" {{ $selectedAbattoir == $abattoir->id ? 'selected' : '' }}>{{ $abattoir->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                            <a href="{{ route('admin.abattoirs.analytics') }}" class="btn btn-secondary">Reset</a>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('admin.abattoirs.analytics.report') }}?lga={{ $selectedLga }}&abattoir_id={{ $selectedAbattoir }}&start_date={{ $startDate ? $startDate->format('Y-m-d') : '' }}&end_date={{ $endDate ? $endDate->format('Y-m-d') : '' }}" 
                               class="btn btn-success" target="_blank">
                                <i class="ri-file-pdf-line me-1"></i> Generate PDF Report
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary h-100">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h4 class="fw-medium text-white">{{ number_format($totalRegisteredLivestock) }}</h4>
                            <h5 class="text-white-50">Total Registered Livestock</h5>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-white">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    <i class="ri-cattle-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success h-100">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h4 class="fw-medium text-white">{{ number_format($totalSlaughteredLivestock) }}</h4>
                            <h5 class="text-white-50">Total Slaughtered Livestock</h5>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-white">
                                <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                    <i class="ri-knife-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning h-100">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h4 class="fw-medium text-white">{{ number_format($pendingSlaughter) }}</h4>
                            <h5 class="text-white-50">Pending Slaughter</h5>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-white">
                                <span class="avatar-title rounded-circle bg-warning-subtle text-warning">
                                    <i class="ri-time-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info h-100">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h4 class="fw-medium text-white">{{ number_format($meatProduction) }} kg</h4>
                            <h5 class="text-white-50">Total Meat Production</h5>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <div class="avatar-sm rounded-circle bg-white">
                                <span class="avatar-title rounded-circle bg-info-subtle text-info">
                                    <i class="ri-scales-3-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts - Row 1 -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4 mb-xl-0">
            <div class="card h-100">
                <div class="card-body chart-container">
                    <h4 class="card-title mb-4">Daily Livestock Registration</h4>
                    <div style="height: 400px; position: relative;">
                        <canvas id="registrationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body chart-container">
                    <h4 class="card-title mb-4">Daily Slaughter Operations</h4>
                    <div style="height: 400px; position: relative;">
                        <canvas id="slaughterChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts - Row 2 -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4 mb-xl-0">
            <div class="card h-100">
                <div class="card-body chart-container">
                    <h4 class="card-title mb-4">Species Distribution</h4>
                    <div style="height: 380px; position: relative;">
                        <canvas id="speciesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body chart-container">
                    <h4 class="card-title mb-4">LGA Distribution</h4>
                    <div style="height: 380px; position: relative;">
                        <canvas id="lgaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts - Row 3 -->
    <div class="row">
        <div class="col-xl-6 mb-4 mb-xl-0">
            <div class="card h-100">
                <div class="card-body chart-container">
                    <h4 class="card-title mb-4">Meat Grade Distribution</h4>
                    <div style="height: 380px; position: relative;">
                        <canvas id="meatGradeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body chart-container">
                    <h4 class="card-title mb-4">Livestock Status</h4>
                    <div style="height: 380px; position: relative;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleDateInputs(value) {
        if (value === 'custom') {
            document.querySelectorAll('.custom-dates').forEach(el => el.style.display = 'block');
        } else {
            document.querySelectorAll('.custom-dates').forEach(el => el.style.display = 'none');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Common chart options - global configuration
        Chart.defaults.font.family = "'Poppins', 'Helvetica', 'sans-serif'";
        Chart.defaults.color = '#555555';
        Chart.defaults.scale.grid.color = 'rgba(0, 0, 0, 0.05)';
        
        // Common options for all charts
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        boxWidth: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    padding: 10,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    displayColors: true,
                    caretSize: 6,
                    cornerRadius: 4
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        };

        // Line chart options
        const lineChartOptions = {
            ...commonOptions,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        precision: 0,
                        font: {
                            size: 11
                        }
                    }
                }
            },
            elements: {
                line: {
                    tension: 0.3
                },
                point: {
                    radius: 4,
                    hitRadius: 10,
                    hoverRadius: 6
                }
            }
        };

        // Registration Chart
        new Chart(document.getElementById('registrationChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($registrationChartData['labels']) !!},
                datasets: [{
                    label: 'Registrations',
                    data: {!! json_encode($registrationChartData['values']) !!},
                    borderColor: '#556ee6',
                    backgroundColor: 'rgba(85, 110, 230, 0.3)',
                    borderWidth: 2,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#556ee6'
                }]
            },
            options: lineChartOptions
        });

        // Slaughter Chart
        new Chart(document.getElementById('slaughterChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($slaughterChartData['labels']) !!},
                datasets: [{
                    label: 'Slaughters',
                    data: {!! json_encode($slaughterChartData['values']) !!},
                    borderColor: '#34c38f',
                    backgroundColor: 'rgba(52, 195, 143, 0.3)',
                    borderWidth: 2,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#34c38f'
                }]
            },
            options: lineChartOptions
        });

        // Species Chart
        new Chart(document.getElementById('speciesChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($speciesDistribution->pluck('species')->map(function($item) { return ucfirst($item); })) !!},
                datasets: [{
                    data: {!! json_encode($speciesDistribution->pluck('count')) !!},
                    backgroundColor: ['#556ee6', '#34c38f', '#f1b44c', '#f46a6a', '#50a5f1', '#6c757d', '#74788d'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        ...commonOptions.plugins.legend,
                        position: 'right'
                    }
                },
                cutout: '40%' // Less cutout for better visibility
            }
        });

        // Determine if we should use horizontal bars based on number of LGAs
        const lgaCount = {!! $lgaDistribution->count() !!};
        const useHorizontalBars = lgaCount > 6;
        
        // LGA Chart
        new Chart(document.getElementById('lgaChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($lgaDistribution->pluck('origin_lga')) !!},
                datasets: [{
                    label: 'Livestock Count',
                    data: {!! json_encode($lgaDistribution->pluck('count')) !!},
                    backgroundColor: '#50a5f1',
                    borderColor: '#4a9cd9',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                ...commonOptions,
                indexAxis: useHorizontalBars ? 'y' : 'x',
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: useHorizontalBars ? true : false
                        },
                        ticks: {
                            precision: 0,
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: useHorizontalBars ? 'rgba(0, 0, 0, 0)' : 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Meat Grade Chart
        new Chart(document.getElementById('meatGradeChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($meatGradeDistribution->pluck('meat_grade')->map(function($item) { return ucfirst($item); })) !!},
                datasets: [{
                    data: {!! json_encode($meatGradeDistribution->pluck('count')) !!},
                    backgroundColor: ['#34c38f', '#f1b44c', '#f46a6a', '#74788d', '#556ee6'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        ...commonOptions.plugins.legend,
                        position: 'right'
                    }
                },
                cutout: '50%'
            }
        });

        // Status Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Registered', 'Rejected', 'Slaughtered'],
                datasets: [{
                    data: [
                        {{ $pendingSlaughter }},
                        {{ $rejectedLivestock }}, 
                        {{ $totalSlaughteredLivestock }}
                    ],
                    backgroundColor: ['#50a5f1', '#f46a6a', '#f1b44c'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: {
                        ...commonOptions.plugins.legend,
                        position: 'right'
                    }
                },
                cutout: '40%' // Less cutout for better visibility
            }
        });
        
        // Add resize event listener to ensure charts respond to window changes
        window.addEventListener('resize', function() {
            Chart.instances.forEach(chart => {
                chart.resize();
            });
        });
    });
</script>
@endpush