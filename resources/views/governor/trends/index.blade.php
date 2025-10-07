@extends('layouts.governor')

@section('content')
<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Trend Analysis & Forecasting</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('governor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Trend Analysis</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Quick Insights Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-white-50 mb-0">Growth Rate</p>
                        <h2 class="mt-3 mb-0 text-white" id="growthRate">
                            <span class="counter-value" data-target="0">0</span>%
                        </h2>
                        <p class="mb-0 text-white-50"><small>Monthly Average</small></p>
                    </div>
                    <div>
                        <i class="ri-line-chart-line fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-white-50 mb-0">New Enrollments</p>
                        <h2 class="mt-3 mb-0 text-white" id="newEnrollments">
                            <span class="counter-value" data-target="0">0</span>
                        </h2>
                        <p class="mb-0 text-white-50"><small>This Month</small></p>
                    </div>
                    <div>
                        <i class="ri-user-add-line fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-white-50 mb-0">Yield Projection</p>
                        <h2 class="mt-3 mb-0 text-white" id="yieldProjection">
                            <span class="counter-value" data-target="0">0</span>MT
                        </h2>
                        <p class="mb-0 text-white-50"><small>Next Quarter</small></p>
                    </div>
                    <div>
                        <i class="ri-plant-line fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-white-50 mb-0">Gender Parity</p>
                        <h2 class="mt-3 mb-0 text-white" id="genderParity">
                            <span class="counter-value" data-target="0">0</span>%
                        </h2>
                        <p class="mb-0 text-white-50"><small>Female Farmers</small></p>
                    </div>
                    <div>
                        <i class="ri-group-line fs-1 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Trend Analysis Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#enrollment" role="tab">
                            <i class="ri-user-follow-line me-1"></i> Enrollment Trends
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#production" role="tab">
                            <i class="ri-seedling-line me-1"></i> Production Trends
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#resources" role="tab">
                            <i class="ri-exchange-line me-1"></i> Resource Utilization
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#gender" role="tab">
                            <i class="ri-women-line me-1"></i> Gender Parity
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <!-- Enrollment Trends Tab -->
                    <div class="tab-pane fade show active" id="enrollment" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Time Period</label>
                                <select class="form-select" id="enrollmentPeriod">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="enrollmentStartDate">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" id="enrollmentEndDate">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">LGA Filter</label>
                                <select class="form-select" id="enrollmentLgaFilter">
                                    <option value="">All LGAs</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12 text-end">
                                <button class="btn btn-primary" id="applyEnrollmentFilters">
                                    <i class="ri-filter-line me-1"></i> Apply Filters
                                </button>
                                <button class="btn btn-success" id="exportEnrollment">
                                    <i class="ri-download-line me-1"></i> Export Data
                                </button>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border border-primary">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Total New Farmers</h6>
                                        <h3 class="mb-0 text-primary" id="totalNewFarmers">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border border-success">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Average per Period</h6>
                                        <h3 class="mb-0 text-success" id="avgPerPeriod">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border border-info">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Growth Trend</h6>
                                        <h3 class="mb-0 text-info" id="growthTrend">
                                            <i class="ri-arrow-up-line"></i> 0%
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Enrollment Trend Over Time</h5>
                                        <div id="enrollmentTrendChart" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Cumulative Growth</h5>
                                        <div id="cumulativeGrowthChart" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Production Trends Tab -->
                    <div class="tab-pane fade" id="production" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Time Period</label>
                                <select class="form-select" id="productionPeriod">
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Crop Type</label>
                                <select class="form-select" id="productionCropType">
                                    <option value="">All Crops</option>
                                </select>
                            </div>
                            <div class="col-md-4 align-self-end">
                                <button class="btn btn-primary w-100" id="applyProductionFilters">
                                    <i class="ri-filter-line me-1"></i> Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Production Metrics -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="ri-bar-chart-box-line fs-2 text-primary mb-2"></i>
                                        <h6 class="text-muted mb-1">Total Expected Yield</h6>
                                        <h4 class="mb-0" id="totalExpectedYield">0 MT</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="ri-stock-line fs-2 text-success mb-2"></i>
                                        <h6 class="text-muted mb-1">Avg Yield/Farm</h6>
                                        <h4 class="mb-0" id="avgYieldPerFarm">0 kg</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="ri-leaf-line fs-2 text-info mb-2"></i>
                                        <h6 class="text-muted mb-1">Active Farms</h6>
                                        <h4 class="mb-0" id="activeFarmsCount">0</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="ri-arrow-up-line fs-2 text-warning mb-2"></i>
                                        <h6 class="text-muted mb-1">Yield Growth</h6>
                                        <h4 class="mb-0" id="yieldGrowthRate">0%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Production Charts -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Crop Production Trends</h5>
                                        <div id="productionTrendChart" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Yield by Crop Type</h5>
                                        <div id="yieldByCropChart" style="height: 350px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Farm Count Trend</h5>
                                        <div id="farmCountTrendChart" style="height: 350px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resource Utilization Tab -->
                    <div class="tab-pane fade" id="resources" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Time Period</label>
                                <select class="form-select" id="resourcePeriod">
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Resource Type</label>
                                <select class="form-select" id="resourceTypeFilter">
                                    <option value="">All Resources</option>
                                </select>
                            </div>
                            <div class="col-md-4 align-self-end">
                                <button class="btn btn-primary w-100" id="applyResourceFilters">
                                    <i class="ri-filter-line me-1"></i> Apply Filters
                                </button>
                            </div>
                        </div>

                        <!-- Resource Metrics -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border-start border-5 border-primary">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Total Applications</h6>
                                        <h3 class="mb-0" id="totalApplications">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-start border-5 border-success">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Granted</h6>
                                        <h3 class="mb-0 text-success" id="grantedApplications">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-start border-5 border-info">
                                    <div class="card-body">
                                        <h6 class="text-muted mb-2">Success Rate</h6>
                                        <h3 class="mb-0 text-info" id="overallSuccessRate">0%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resource Charts -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Resource Application Trends</h5>
                                        <div id="resourceUtilizationChart" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Success Rate Trends</h5>
                                        <div id="successRateTrendChart" style="height: 350px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Application Status Distribution</h5>
                                        <div id="statusDistributionChart" style="height: 350px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gender Parity Tab -->
                    <div class="tab-pane fade" id="gender" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-12 text-end">
                                <button class="btn btn-success" id="exportGenderData">
                                    <i class="ri-download-line me-1"></i> Export Data
                                </button>
                            </div>
                        </div>

                        <!-- Gender Metrics -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <div class="card-body text-white text-center">
                                        <i class="ri-women-line fs-1 mb-2"></i>
                                        <h6 class="text-white-50 mb-1">Female Farmers</h6>
                                        <h3 class="mb-0 text-white" id="totalFemaleFarmers">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-gradient" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <div class="card-body text-white text-center">
                                        <i class="ri-men-line fs-1 mb-2"></i>
                                        <h6 class="text-white-50 mb-1">Male Farmers</h6>
                                        <h3 class="mb-0 text-white" id="totalMaleFarmers">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-gradient" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                    <div class="card-body text-white text-center">
                                        <i class="ri-percent-line fs-1 mb-2"></i>
                                        <h6 class="text-white-50 mb-1">Female %</h6>
                                        <h3 class="mb-0 text-white" id="femalePercentage">0%</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-gradient" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                    <div class="card-body text-white text-center">
                                        <i class="ri-arrow-up-down-line fs-1 mb-2"></i>
                                        <h6 class="text-white-50 mb-1">Parity Goal</h6>
                                        <h3 class="mb-0 text-white">40%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gender Charts -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Gender Distribution Over Time</h5>
                                        <div id="genderTrendChart" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Current Distribution</h5>
                                        <div id="currentGenderChart" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Female Participation Rate Trend</h5>
                                        <div id="femaleParticipationChart" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Progress to Goal -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Progress Toward 40% Female Participation Goal</h5>
                                        <div class="progress" style="height: 30px;">
                                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                                 role="progressbar" 
                                                 id="genderGoalProgress" 
                                                 style="width: 0%"
                                                 aria-valuenow="0" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <span class="fw-bold fs-6">0%</span>
                                            </div>
                                        </div>
                                        <p class="text-muted mt-2 mb-0">
                                            <small id="genderGoalMessage">Loading...</small>
                                        </p>
                                    </div>
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
    $(document).ready(function() {
        // Initialize date pickers with default values
        const today = new Date();
        const oneYearAgo = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate());
        
        $('#enrollmentStartDate').val(oneYearAgo.toISOString().split('T')[0]);
        $('#enrollmentEndDate').val(today.toISOString().split('T')[0]);

        // Load LGA options
        loadLgaOptions();
        
        // Load initial data
        loadEnrollmentTrends();
        loadGenderParityTrends();

        // Event listeners
        $('#applyEnrollmentFilters').on('click', loadEnrollmentTrends);
        $('#applyProductionFilters').on('click', loadProductionTrends);
        $('#applyResourceFilters').on('click', loadResourceUtilizationTrends);

        // Export buttons
        $('#exportEnrollment').on('click', function() {
            exportData('enrollment');
        });
        $('#exportGenderData').on('click', function() {
            exportData('gender');
        });

        // Tab change events
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const target = $(e.target).attr("href");
            if (target === '#production' && !window.productionLoaded) {
                loadProductionTrends();
                window.productionLoaded = true;
            } else if (target === '#resources' && !window.resourcesLoaded) {
                loadResourceUtilizationTrends();
                window.resourcesLoaded = true;
            }
        });

        // ========== ENROLLMENT TRENDS ==========
        function loadEnrollmentTrends() {
            const period = $('#enrollmentPeriod').val();
            const startDate = $('#enrollmentStartDate').val();
            const endDate = $('#enrollmentEndDate').val();
            const lgaId = $('#enrollmentLgaFilter').val();

            $.ajax({
                url: '{{ route("governor.trends.enrollment") }}',
                method: 'GET',
                data: { period, start_date: startDate, end_date: endDate, lga_id: lgaId },
                success: function(response) {
                    renderEnrollmentData(response);
                },
                error: function(xhr) {
                    showError('Failed to load enrollment trends');
                }
            });
        }

        function renderEnrollmentData(data) {
            const trends = data.trends;
            
            // Update summary cards
            $('#totalNewFarmers').text(formatNumber(data.total_new_farmers));
            const avgPerPeriod = trends.length > 0 ? (data.total_new_farmers / trends.length).toFixed(0) : 0;
            $('#avgPerPeriod').text(formatNumber(avgPerPeriod));
            
            // Calculate growth trend
            if (trends.length >= 2) {
                const recent = parseInt(trends[trends.length - 1].count);
                const previous = parseInt(trends[trends.length - 2].count);
                const growth = previous > 0 ? (((recent - previous) / previous) * 100).toFixed(1) : 0;
                const arrow = growth >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line';
                const color = growth >= 0 ? 'text-success' : 'text-danger';
                $('#growthTrend').html(`<i class="${arrow} ${color}"></i> ${Math.abs(growth)}%`);
                $('#growthTrend').removeClass('text-info text-success text-danger').addClass(color);
            }

            // Render charts
            renderEnrollmentChart(trends);
            renderCumulativeChart(trends);
        }

        function renderEnrollmentChart(trends) {
            const options = {
                series: [{
                    name: 'New Farmers',
                    data: trends.map(t => parseInt(t.count))
                }],
                chart: {
                    type: 'area',
                    height: 400,
                    toolbar: { show: true }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: trends.map(t => t.period),
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Farmers'
                    }
                },
                colors: ['#0ab39c'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return formatNumber(val) + ' total farmers';
                        }
                    }
                }
            };

            renderChart('#cumulativeGrowthChart', options);
        }

        // ========== PRODUCTION TRENDS ==========
        function loadProductionTrends() {
            const period = $('#productionPeriod').val();
            const cropType = $('#productionCropType').val();

            $.ajax({
                url: '{{ route("governor.trends.production") }}',
                method: 'GET',
                data: { period, crop_type: cropType },
                success: function(response) {
                    renderProductionData(response);
                },
                error: function(xhr) {
                    showError('Failed to load production trends');
                }
            });
        }

        function renderProductionData(data) {
            const trends = data.production_trends;
            
            // Calculate metrics
            const totalYield = trends.reduce((sum, t) => sum + parseFloat(t.total_expected_yield || 0), 0);
            const avgYield = trends.length > 0 ? (totalYield / trends.length).toFixed(0) : 0;
            const totalFarms = trends.reduce((sum, t) => sum + parseInt(t.farm_count || 0), 0);
            
            $('#totalExpectedYield').text(formatNumber(totalYield / 1000, 2) + ' MT');
            $('#avgYieldPerFarm').text(formatNumber(avgYield) + ' kg');
            $('#activeFarmsCount').text(formatNumber(totalFarms));
            
            // Calculate yield growth
            if (trends.length >= 2) {
                const recent = parseFloat(trends[trends.length - 1].total_expected_yield || 0);
                const previous = parseFloat(trends[trends.length - 2].total_expected_yield || 0);
                const growth = previous > 0 ? (((recent - previous) / previous) * 100).toFixed(1) : 0;
                $('#yieldGrowthRate').text(growth + '%');
            }

            // Group by crop type for charts
            const cropGroups = {};
            trends.forEach(t => {
                if (!cropGroups[t.crop_type]) {
                    cropGroups[t.crop_type] = [];
                }
                cropGroups[t.crop_type].push(t);
            });

            renderProductionChart(cropGroups);
            renderYieldByCropChart(trends);
            renderFarmCountChart(cropGroups);
        }

        function renderProductionChart(cropGroups) {
            const series = Object.keys(cropGroups).map(crop => ({
                name: crop,
                data: cropGroups[crop].map(t => parseFloat(t.total_expected_yield || 0) / 1000)
            }));

            const categories = cropGroups[Object.keys(cropGroups)[0]].map(t => t.period);

            const options = {
                series: series,
                chart: {
                    type: 'line',
                    height: 400,
                    toolbar: { show: true }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    title: {
                        text: 'Expected Yield (MT)'
                    }
                },
                colors: ['#0ab39c', '#f7b84b', '#405189', '#f06548', '#299cdb'],
                legend: {
                    position: 'top'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return formatNumber(val, 2) + ' MT';
                        }
                    }
                }
            };

            renderChart('#productionTrendChart', options);
        }

        function renderYieldByCropChart(trends) {
            // Aggregate by crop type
            const cropYields = {};
            trends.forEach(t => {
                if (!cropYields[t.crop_type]) {
                    cropYields[t.crop_type] = 0;
                }
                cropYields[t.crop_type] += parseFloat(t.total_expected_yield || 0);
            });

            const options = {
                series: Object.values(cropYields).map(v => v / 1000),
                chart: {
                    type: 'donut',
                    height: 350
                },
                labels: Object.keys(cropYields),
                colors: ['#0ab39c', '#f7b84b', '#405189', '#f06548', '#299cdb'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Yield',
                                    formatter: function(w) {
                                        return formatNumber(w.globals.seriesTotals.reduce((a, b) => a + b, 0), 2) + ' MT';
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return formatNumber(val, 2) + ' MT';
                        }
                    }
                }
            };

            renderChart('#yieldByCropChart', options);
        }

        function renderFarmCountChart(cropGroups) {
            const series = Object.keys(cropGroups).map(crop => ({
                name: crop,
                data: cropGroups[crop].map(t => parseInt(t.farm_count || 0))
            }));

            const categories = cropGroups[Object.keys(cropGroups)[0]].map(t => t.period);

            const options = {
                series: series,
                chart: {
                    type: 'bar',
                    height: 350,
                    stacked: true,
                    toolbar: { show: true }
                },
                plotOptions: {
                    bar: {
                        horizontal: false
                    }
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Farms'
                    }
                },
                colors: ['#0ab39c', '#f7b84b', '#405189', '#f06548', '#299cdb'],
                legend: {
                    position: 'top'
                },
                fill: {
                    opacity: 1
                }
            };

            renderChart('#farmCountTrendChart', options);
        }

        // ========== RESOURCE UTILIZATION ==========
        function loadResourceUtilizationTrends() {
            const period = $('#resourcePeriod').val();
            const resourceId = $('#resourceTypeFilter').val();

            $.ajax({
                url: '{{ route("governor.trends.resource_utilization") }}',
                method: 'GET',
                data: { period, resource_id: resourceId },
                success: function(response) {
                    renderResourceData(response);
                },
                error: function(xhr) {
                    showError('Failed to load resource utilization trends');
                }
            });
        }

        function renderResourceData(data) {
            const trends = data.utilization_trends;
            
            // Calculate totals
            const totalApps = trends.reduce((sum, t) => sum + parseInt(t.application_count || 0), 0);
            const totalGranted = trends.reduce((sum, t) => sum + parseInt(t.granted_count || 0), 0);
            const successRate = totalApps > 0 ? ((totalGranted / totalApps) * 100).toFixed(1) : 0;
            
            $('#totalApplications').text(formatNumber(totalApps));
            $('#grantedApplications').text(formatNumber(totalGranted));
            $('#overallSuccessRate').text(successRate + '%');

            // Group by resource
            const resourceGroups = {};
            trends.forEach(t => {
                if (!resourceGroups[t.resource_name]) {
                    resourceGroups[t.resource_name] = [];
                }
                resourceGroups[t.resource_name].push(t);
            });

            renderResourceUtilizationChart(resourceGroups);
            renderSuccessRateChart(resourceGroups);
            renderStatusDistributionChart(trends);
        }

        function renderResourceUtilizationChart(resourceGroups) {
            const series = Object.keys(resourceGroups).map(resource => ({
                name: resource,
                data: resourceGroups[resource].map(t => parseInt(t.application_count || 0))
            }));

            const categories = resourceGroups[Object.keys(resourceGroups)[0]].map(t => t.period);

            const options = {
                series: series,
                chart: {
                    type: 'area',
                    height: 400,
                    stacked: false,
                    toolbar: { show: true }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Applications'
                    }
                },
                colors: ['#0ab39c', '#f7b84b', '#405189', '#f06548'],
                legend: {
                    position: 'top'
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.6,
                        opacityTo: 0.1
                    }
                }
            };

            renderChart('#resourceUtilizationChart', options);
        }

        function renderSuccessRateChart(resourceGroups) {
            const series = Object.keys(resourceGroups).map(resource => ({
                name: resource,
                data: resourceGroups[resource].map(t => parseFloat(t.success_rate || 0))
            }));

            const categories = resourceGroups[Object.keys(resourceGroups)[0]].map(t => t.period);

            const options = {
                series: series,
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: { show: true }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    title: {
                        text: 'Success Rate (%)'
                    },
                    min: 0,
                    max: 100
                },
                colors: ['#0ab39c', '#f7b84b', '#405189', '#f06548'],
                markers: {
                    size: 5
                },
                legend: {
                    position: 'top'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(1) + '%';
                        }
                    }
                }
            };

            renderChart('#successRateTrendChart', options);
        }

        function renderStatusDistributionChart(trends) {
            const totalGranted = trends.reduce((sum, t) => sum + parseInt(t.granted_count || 0), 0);
            const totalPending = trends.reduce((sum, t) => sum + parseInt(t.pending_count || 0), 0);
            const totalApps = trends.reduce((sum, t) => sum + parseInt(t.application_count || 0), 0);
            const totalDeclined = totalApps - totalGranted - totalPending;

            const options = {
                series: [totalGranted, totalPending, totalDeclined],
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: ['Granted', 'Pending', 'Declined'],
                colors: ['#0ab39c', '#f7b84b', '#f06548'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            offset: -5
                        }
                    }
                },
                dataLabels: {
                    formatter: function(val, opts) {
                        return opts.w.config.series[opts.seriesIndex];
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return formatNumber(val) + ' applications';
                        }
                    }
                }
            };

            renderChart('#statusDistributionChart', options);
        }

        // ========== GENDER PARITY ==========
        function loadGenderParityTrends() {
            $.ajax({
                url: '{{ route("governor.trends.gender_parity") }}',
                method: 'GET',
                success: function(response) {
                    renderGenderData(response);
                },
                error: function(xhr) {
                    showError('Failed to load gender parity trends');
                }
            });
        }

        function renderGenderData(data) {
            const trends = data.gender_parity_trends;
            
            if (trends.length === 0) return;

            // Get latest data
            const latest = trends[trends.length - 1];
            
            $('#totalFemaleFarmers').text(formatNumber(latest.female));
            $('#totalMaleFarmers').text(formatNumber(latest.male));
            $('#femalePercentage').text(latest.female_percentage + '%');
            $('#genderParity').find('.counter-value').attr('data-target', latest.female_percentage).text(latest.female_percentage);

            // Update progress bar
            const progress = (latest.female_percentage / 40) * 100;
            $('#genderGoalProgress').css('width', Math.min(progress, 100) + '%');
            $('#genderGoalProgress span').text(latest.female_percentage + '%');
            
            const remaining = 40 - latest.female_percentage;
            if (remaining > 0) {
                $('#genderGoalMessage').text(`${remaining.toFixed(1)}% more needed to reach the 40% target`);
            } else {
                $('#genderGoalMessage').text('Goal achieved! Excellent progress toward gender parity.');
                $('#genderGoalProgress').addClass('bg-success');
            }

            renderGenderTrendChart(trends);
            renderCurrentGenderChart(latest);
            renderFemaleParticipationChart(trends);
        }

        function renderGenderTrendChart(trends) {
            const options = {
                series: [
                    {
                        name: 'Female',
                        data: trends.map(t => parseInt(t.female))
                    },
                    {
                        name: 'Male',
                        data: trends.map(t => parseInt(t.male))
                    }
                ],
                chart: {
                    type: 'area',
                    height: 400,
                    stacked: false,
                    toolbar: { show: true }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: trends.map(t => t.period),
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Farmers'
                    }
                },
                colors: ['#667eea', '#f5576c'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.6,
                        opacityTo: 0.2
                    }
                },
                legend: {
                    position: 'top'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return formatNumber(val) + ' farmers';
                        }
                    }
                }
            };

            renderChart('#genderTrendChart', options);
        }

        function renderCurrentGenderChart(latest) {
            const options = {
                series: [latest.female, latest.male],
                chart: {
                    type: 'donut',
                    height: 400
                },
                labels: ['Female', 'Male'],
                colors: ['#667eea', '#f5576c'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function(w) {
                                        return formatNumber(w.globals.seriesTotals.reduce((a, b) => a + b, 0));
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    formatter: function(val, opts) {
                        return val.toFixed(1) + '%';
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return formatNumber(val) + ' farmers';
                        }
                    }
                }
            };

            renderChart('#currentGenderChart', options);
        }

        function renderFemaleParticipationChart(trends) {
            const options = {
                series: [{
                    name: 'Female %',
                    data: trends.map(t => parseFloat(t.female_percentage))
                }],
                chart: {
                    type: 'line',
                    height: 300,
                    toolbar: { show: true }
                },
                stroke: {
                    curve: 'smooth',
                    width: 4
                },
                xaxis: {
                    categories: trends.map(t => t.period),
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    title: {
                        text: 'Female Participation (%)'
                    },
                    min: 0,
                    max: 50
                },
                colors: ['#667eea'],
                markers: {
                    size: 5
                },
                annotations: {
                    yaxis: [{
                        y: 40,
                        borderColor: '#00E396',
                        label: {
                            borderColor: '#00E396',
                            style: {
                                color: '#fff',
                                background: '#00E396'
                            },
                            text: 'Target: 40%'
                        }
                    }]
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val.toFixed(2) + '%';
                        }
                    }
                }
            };

            renderChart('#femaleParticipationChart', options);
        }

        // ========== UTILITY FUNCTIONS ==========
        function loadLgaOptions() {
            $.ajax({
                url: '{{ route("governor.lga_comparison.performance_ranking") }}',
                method: 'GET',
                success: function(response) {
                    let options = '<option value="">All LGAs</option>';
                    response.rankings.forEach(lga => {
                        options += `<option value="${lga.rank}">${lga.lga_name}</option>`;
                    });
                    $('#enrollmentLgaFilter').html(options);
                }
            });
        }

        function renderChart(selector, options) {
            // Clear existing chart
            $(selector).empty();
            
            const chart = new ApexCharts(document.querySelector(selector), options);
            chart.render();
        }

        function formatNumber(num, decimals = 0) {
            if (num === null || num === undefined) return '0';
            const parsed = parseFloat(num);
            return parsed.toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }

        function showError(message) {
            Swal.fire('Error', message, 'error');
        }

        function exportData(type) {
            // Implementation for exporting data
            Swal.fire({
                title: 'Export Data',
                text: 'Preparing export file...',
                icon: 'info',
                showConfirmButton: false,
                timer: 1500
            });
        }

        // Animate counters on page load
        animateCounters();

        function animateCounters() {
            $('.counter-value').each(function() {
                const $this = $(this);
                const target = parseInt($this.attr('data-target')) || 0;
                
                $({ count: 0 }).animate({ count: target }, {
                    duration: 1500,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.floor(this.count));
                    },
                    complete: function() {
                        $this.text(target);
                    }
                });
            });
        }
    });
</script>
@endpush