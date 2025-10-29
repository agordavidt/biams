@extends('layouts.vendor')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Analytics Dashboard</h4>
                <div class="page-title-right">
                    <button onclick="exportAnalytics()" class="btn btn-success">
                        <i class="ri-download-line me-1"></i> Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('vendor.analytics') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ $startDate }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ $endDate }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                <a href="{{ route('vendor.analytics') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">Total Resources</p>
                            <h4 class="mb-0">{{ $overviewStats['total_resources'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 text-center">
                                <i class="ri-box-3-line text-primary font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">{{ $overviewStats['active_resources'] }} Active</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">Total Applications</p>
                            <h4 class="mb-0">{{ $overviewStats['total_applications'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-opacity-10 text-center">
                                <i class="ri-file-list-3-line text-success font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-warning">{{ $overviewStats['pending_applications'] }} Pending</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">Revenue Generated</p>
                            <h4 class="mb-0">₦{{ number_format($overviewStats['total_revenue']) }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info bg-opacity-10 text-center">
                                <i class="ri-money-dollar-circle-line text-info font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-primary">₦{{ number_format($overviewStats['expected_reimbursement']) }} Expected</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium mb-2">Fulfilled Orders</p>
                            <h4 class="mb-0">{{ $overviewStats['fulfilled_applications'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-opacity-10 text-center">
                                <i class="ri-checkbox-circle-line text-warning font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">{{ $overviewStats['approved_applications'] }} Approved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Analytics -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Revenue Trends</h5>
                </div>
                <div class="card-body">
                    <div id="revenue-chart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Revenue Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-1">Total Revenue</h6>
                        <h3 class="text-success">₦{{ number_format($revenueAnalytics['total_paid']) }}</h3>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-1">Average Order Value</h6>
                        <h4 class="text-primary">₦{{ number_format($revenueAnalytics['average_order_value'] ?? 0) }}</h4>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h4 class="text-info">{{ $revenueAnalytics['payment_count'] }}</h4>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted mb-1">Quantity Sold</h6>
                        <h4 class="text-warning">{{ $revenueAnalytics['total_quantity_sold'] }}</h4>
                    </div>
                    
                    <div>
                        <h6 class="text-muted mb-1">Expected Reimbursement</h6>
                        <h4 class="text-success">₦{{ number_format($revenueAnalytics['expected_reimbursement']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Trends -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Application Trends</h5>
                </div>
                <div class="card-body">
                    <div id="application-trends-chart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="status-distribution-chart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resource Performance -->
    <!-- <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Resource Performance</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Resource Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Total Applications</th>
                                    <th>Paid Applications</th>
                                    <th>Conversion Rate</th>
                                    <th>Revenue Generated</th>
                                    <th>Quantity Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resourcePerformance as $resource)
                                <tr>
                                    <td>{{ $resource['name'] }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($resource['type']) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $resource['status'] == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($resource['status']) }}
                                        </span>
                                    </td>
                                    <td>{{ $resource['total_applications'] }}</td>
                                    <td>{{ $resource['paid_applications'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $resource['conversion_rate'] >= 50 ? 'success' : ($resource['conversion_rate'] >= 20 ? 'warning' : 'danger') }}">
                                            {{ $resource['conversion_rate'] }}%
                                        </span>
                                    </td>
                                    <td>₦{{ number_format($resource['total_revenue']) }}</td>
                                    <td>{{ $resource['quantity_sold'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No resource performance data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Geographic Distribution -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Geographic Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>LGA</th>
                                    <th>Total Applications</th>
                                    <th>Paid Applications</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($geographicDistribution as $location)
                                <tr>
                                    <td>{{ $location['lga'] }}</td>
                                    <td>{{ $location['total_applications'] }}</td>
                                    <td>{{ $location['paid_applications'] }}</td>
                                    <td>₦{{ number_format($location['total_revenue']) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No geographic distribution data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Comparison -->
        <!-- <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Comparison</h5>
                </div>
                <div class="card-body">
                    <div id="monthly-comparison-chart" style="min-height: 400px;"></div>
                </div>
            </div>
        </div> -->
    </div>
</div>

<!-- Export Form -->
<form id="export-form" action="{{ route('vendor.analytics.export') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="start_date" value="{{ $startDate }}">
    <input type="hidden" name="end_date" value="{{ $endDate }}">
</form>
@endsection

@push('scripts')
<script src="{{ asset('dashboard/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
    // Revenue Chart
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Trends Chart
        var revenueOptions = {
            series: [{
                name: 'Daily Revenue',
                data: @json($revenueAnalytics['daily_revenue']->map(fn($item) => $item['amount']))
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: true
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: @json($revenueAnalytics['daily_revenue']->map(fn($item) => $item['date']))
            },
            colors: ['#38761D'],
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "₦" + val.toLocaleString()
                    }
                }
            }
        };

        var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
        revenueChart.render();

        // Application Trends Chart
        var trendsData = @json($applicationTrends['daily_trends']);
        var applicationOptions = {
            series: [{
                name: 'Pending',
                data: trendsData.map(item => item.pending)
            }, {
                name: 'Approved',
                data: trendsData.map(item => item.approved)
            }, {
                name: 'Paid',
                data: trendsData.map(item => item.paid)
            }, {
                name: 'Fulfilled',
                data: trendsData.map(item => item.fulfilled)
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: true
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: trendsData.map(item => item.date)
            },
            colors: ['#FFC107', '#17A2B8', '#28A745', '#6F42C1'],
        };

        var applicationChart = new ApexCharts(document.querySelector("#application-trends-chart"), applicationOptions);
        applicationChart.render();

        // Status Distribution Chart
        var statusData = @json($applicationTrends['status_distribution']);
        var statusOptions = {
            series: Object.values(statusData),
            chart: {
                height: 350,
                type: 'donut',
            },
            labels: Object.keys(statusData).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
            colors: ['#FFC107', '#17A2B8', '#28A745', '#6F42C1', '#DC3545'],
            legend: {
                position: 'bottom'
            }
        };

        var statusChart = new ApexCharts(document.querySelector("#status-distribution-chart"), statusOptions);
        statusChart.render();

        // Monthly Comparison Chart
        var monthlyData = @json($monthlyComparison);
        var monthlyOptions = {
            series: [{
                name: 'Applications',
                data: monthlyData.map(item => item.applications)
            }, {
                name: 'Paid Orders',
                data: monthlyData.map(item => item.paid)
            }, {
                name: 'Revenue',
                data: monthlyData.map(item => item.revenue)
            }],
            chart: {
                height: 400,
                type: 'bar',
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: monthlyData.map(item => item.month)
            },
            yaxis: {
                title: {
                    text: 'Count/Amount'
                }
            },
            fill: {
                opacity: 1
            },
            colors: ['#17A2B8', '#28A745', '#38761D'],
            tooltip: {
                y: {
                    formatter: function (val, { seriesIndex }) {
                        if (seriesIndex === 2) {
                            return "₦" + val.toLocaleString();
                        }
                        return val;
                    }
                }
            }
        };

        var monthlyChart = new ApexCharts(document.querySelector("#monthly-comparison-chart"), monthlyOptions);
        monthlyChart.render();
    });

    // Export Analytics Data
    function exportAnalytics() {
        Swal.fire({
            title: 'Export Analytics Data?',
            text: 'This will generate a CSV file with all analytics data for the selected date range.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#38761D',
            cancelButtonColor: '#6C757D',
            confirmButtonText: 'Yes, Export!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('export-form').submit();
            }
        });
    }
</script>
@endpush