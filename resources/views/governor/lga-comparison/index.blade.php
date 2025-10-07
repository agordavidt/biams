@extends('layouts.governor')

@section('content')
<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">LGA Performance Comparison</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('governor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">LGA Comparison</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Overview -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total LGAs</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-map-pin-line text-primary fs-3"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0" id="totalLgasCount">
                            <span class="counter-value" data-target="23">0</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total Farmers</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-group-line text-success fs-3"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0" id="totalFarmersCount">
                            <span class="counter-value" data-target="0">0</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Total Hectares</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-landscape-line text-info fs-3"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0" id="totalHectaresCount">
                            <span class="counter-value" data-target="0">0</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Avg Farm Size</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-bar-chart-box-line text-warning fs-3"></i>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0" id="avgFarmSizeCount">
                            <span class="counter-value" data-target="0">0</span> ha
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#rankings" role="tab">
                            <i class="ri-trophy-line me-1 align-bottom"></i> Performance Rankings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#capacity" role="tab">
                            <i class="ri-pie-chart-line me-1 align-bottom"></i> Capacity Analysis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#sidebyside" role="tab">
                            <i class="ri-contrast-line me-1 align-bottom"></i> Side-by-Side
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#geographic" role="tab">
                            <i class="ri-map-2-line me-1 align-bottom"></i> Geographic Distribution
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <!-- Performance Rankings Tab -->
                    <div class="tab-pane fade show active" id="rankings" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Rank By Metric</label>
                                <select class="form-select" id="rankingMetric">
                                    <option value="total_farmers" selected>Total Farmers</option>
                                    <option value="total_hectares">Total Hectares</option>
                                    <option value="avg_farm_size">Average Farm Size</option>
                                    <option value="active_farmers">Active Farmers</option>
                                    <option value="pending_farmers">Pending Farmers</option>
                                </select>
                            </div>
                            <div class="col-md-8 text-end align-self-end">
                                <button class="btn btn-primary" id="exportRankings">
                                    <i class="ri-download-line me-1"></i> Export Rankings
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle" id="rankingsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rank</th>
                                        <th>LGA</th>
                                        <th>Total Farmers</th>
                                        <th>Total Hectares</th>
                                        <th>Avg Farm Size</th>
                                        <th>Active Farmers</th>
                                        <th>Pending Review</th>
                                    </tr>
                                </thead>
                                <tbody id="rankingsTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Rankings Chart -->
                        <div class="mt-4">
                            <h5 class="card-title mb-3">Visual Comparison</h5>
                            <div id="rankingsChart" style="height: 400px;"></div>
                        </div>
                    </div>

                    <!-- Capacity Analysis Tab -->
                    <div class="tab-pane fade" id="capacity" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Select LGA (Optional)</label>
                                <select class="form-select" id="capacityLgaFilter">
                                    <option value="">All LGAs</option>
                                </select>
                            </div>
                            <div class="col-md-8 text-end align-self-end">
                                <button class="btn btn-primary" id="exportCapacity">
                                    <i class="ri-download-line me-1"></i> Export Analysis
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="capacityTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>LGA</th>
                                        <th>Crop Farms</th>
                                        <th>Crop Hectares</th>
                                        <th>Livestock Farms</th>
                                        <th>Fishery Farms</th>
                                        <th>Orchard Farms</th>
                                        <th>Orchard Hectares</th>
                                        <th>Cooperatives</th>
                                        <th>Coop Members</th>
                                    </tr>
                                </thead>
                                <tbody id="capacityTableBody">
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Farm Type Distribution Chart -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Farm Type Distribution</h5>
                                        <div id="farmTypeChart" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Cooperative Membership</h5>
                                        <div id="coopMembershipChart" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Side-by-Side Comparison Tab -->
                    <div class="tab-pane fade" id="sidebyside" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Select LGAs to Compare (2-5)</label>
                                <select class="form-select" id="lgaSelector" multiple>
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple LGAs</small>
                            </div>
                            <div class="col-md-4 align-self-end">
                                <button class="btn btn-success w-100" id="compareBtn">
                                    <i class="ri-git-compare-line me-1"></i> Compare Selected
                                </button>
                            </div>
                        </div>

                        <div id="comparisonResults" style="display: none;">
                            <!-- Basic Comparison -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Basic Statistics Comparison</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="basicComparisonTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Metric</th>
                                                    <!-- LGA columns will be added dynamically -->
                                                </tr>
                                            </thead>
                                            <tbody id="basicComparisonBody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Farm Type Distribution -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Farm Type Distribution</h5>
                                </div>
                                <div class="card-body">
                                    <div id="comparisonFarmTypeChart" style="height: 400px;"></div>
                                </div>
                            </div>

                            <!-- Top Crops -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Top 5 Crops by LGA</h5>
                                </div>
                                <div class="card-body">
                                    <div id="topCropsContainer" class="row"></div>
                                </div>
                            </div>

                            <!-- Resource Application Success -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Resource Application Success Rates</h5>
                                </div>
                                <div class="card-body">
                                    <div id="resourceSuccessChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>

                        <div id="noComparisonMessage" class="alert alert-info text-center">
                            <i class="ri-information-line me-2"></i>
                            Select 2-5 LGAs above and click "Compare Selected" to view detailed comparison
                        </div>
                    </div>

                    <!-- Geographic Distribution Tab -->
                    <div class="tab-pane fade" id="geographic" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-12 text-end">
                                <button class="btn btn-primary" id="exportGeographic">
                                    <i class="ri-download-line me-1"></i> Export Data
                                </button>
                            </div>
                        </div>

                        <!-- State Totals -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="text-white-50 mb-2">State Total Farmers</h6>
                                        <h3 class="mb-0" id="stateTotalFarmers">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="text-white-50 mb-2">State Total Hectares</h6>
                                        <h3 class="mb-0" id="stateTotalHectares">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="geographicTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>LGA</th>
                                        <th>Code</th>
                                        <th>Farmer Count</th>
                                        <th>% of State</th>
                                        <th>Total Hectares</th>
                                        <th>% of State</th>
                                        <th>Farm Count</th>
                                        <th>Farms/Farmer</th>
                                    </tr>
                                </thead>
                                <tbody id="geographicTableBody">
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Distribution Charts -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Farmer Distribution by LGA</h5>
                                        <div id="farmerDistributionChart" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Land Distribution by LGA</h5>
                                        <div id="landDistributionChart" style="height: 400px;"></div>
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
        let allLgas = [];
        let currentRankings = [];
        let currentCapacity = [];
        let currentGeographic = [];

        // Initialize
        loadPerformanceRankings();
        loadCapacityAnalysis();
        loadGeographicAnalysis();
        populateLgaSelectors();

        // Performance Rankings
        $('#rankingMetric').on('change', function() {
            loadPerformanceRankings();
        });

        function loadPerformanceRankings() {
            const metric = $('#rankingMetric').val();
            
            $.ajax({
                url: '{{ route("governor.lga_comparison.performance_ranking") }}',
                method: 'GET',
                data: { metric: metric },
                success: function(response) {
                    currentRankings = response.rankings;
                    renderRankingsTable(response.rankings);
                    renderRankingsChart(response.rankings, metric);
                    updateQuickStats(response.rankings);
                },
                error: function(xhr) {
                    showError('Failed to load rankings');
                }
            });
        }

        function renderRankingsTable(rankings) {
            let html = '';
            rankings.forEach((lga, index) => {
                const rankClass = index < 3 ? 'badge bg-success' : 'badge bg-secondary';
                html += `
                    <tr>
                        <td><span class="${rankClass}">#${lga.rank}</span></td>
                        <td><strong>${lga.lga_name}</strong> <small class="text-muted">(${lga.lga_code})</small></td>
                        <td>${formatNumber(lga.total_farmers)}</td>
                        <td>${formatNumber(lga.total_hectares, 2)} ha</td>
                        <td>${formatNumber(lga.avg_farm_size, 2)} ha</td>
                        <td><span class="badge bg-success-subtle text-success">${formatNumber(lga.active_farmers)}</span></td>
                        <td><span class="badge bg-warning-subtle text-warning">${formatNumber(lga.pending_farmers)}</span></td>
                    </tr>
                `;
            });
            $('#rankingsTableBody').html(html);
        }

        function renderRankingsChart(rankings, metric) {
            const top10 = rankings.slice(0, 10);
            
            const options = {
                series: [{
                    name: getMetricLabel(metric),
                    data: top10.map(lga => parseFloat(lga[metric]))
                }],
                chart: {
                    type: 'bar',
                    height: 400,
                    toolbar: { show: true }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        distributed: true,
                        dataLabels: { position: 'top' }
                    }
                },
                colors: ['#0ab39c'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return formatNumber(val, 2);
                    }
                },
                xaxis: {
                    categories: top10.map(lga => lga.lga_name),
                    title: { text: getMetricLabel(metric) }
                },
                yaxis: {
                    title: { text: 'LGA' }
                },
                title: {
                    text: `Top 10 LGAs by ${getMetricLabel(metric)}`,
                    align: 'center'
                }
            };

            const chart = new ApexCharts(document.querySelector("#rankingsChart"), options);
            chart.render();
        }

        // Capacity Analysis
        $('#capacityLgaFilter').on('change', function() {
            loadCapacityAnalysis();
        });

        function loadCapacityAnalysis() {
            const lgaId = $('#capacityLgaFilter').val();
            
            $.ajax({
                url: '{{ route("governor.lga_comparison.capacity_analysis") }}',
                method: 'GET',
                data: { lga_id: lgaId },
                success: function(response) {
                    currentCapacity = response.capacity_analysis;
                    renderCapacityTable(response.capacity_analysis);
                    renderFarmTypeChart(response.capacity_analysis);
                    renderCoopMembershipChart(response.capacity_analysis);
                },
                error: function(xhr) {
                    showError('Failed to load capacity analysis');
                }
            });
        }

        function renderCapacityTable(data) {
            let html = '';
            data.forEach(lga => {
                html += `
                    <tr>
                        <td><strong>${lga.lga_name}</strong></td>
                        <td>${formatNumber(lga.crop_farms)}</td>
                        <td>${formatNumber(lga.crop_hectares, 2)} ha</td>
                        <td>${formatNumber(lga.livestock_farms)}</td>
                        <td>${formatNumber(lga.fishery_farms)}</td>
                        <td>${formatNumber(lga.orchard_farms)}</td>
                        <td>${formatNumber(lga.orchard_hectares, 2)} ha</td>
                        <td>${formatNumber(lga.cooperative_count)}</td>
                        <td>${formatNumber(lga.coop_members)}</td>
                    </tr>
                `;
            });
            $('#capacityTableBody').html(html);
        }

        function renderFarmTypeChart(data) {
            const categories = data.map(lga => lga.lga_name);
            
            const options = {
                series: [
                    { name: 'Crop Farms', data: data.map(lga => parseInt(lga.crop_farms)) },
                    { name: 'Livestock', data: data.map(lga => parseInt(lga.livestock_farms)) },
                    { name: 'Fisheries', data: data.map(lga => parseInt(lga.fishery_farms)) },
                    { name: 'Orchards', data: data.map(lga => parseInt(lga.orchard_farms)) }
                ],
                chart: {
                    type: 'bar',
                    height: 300,
                    stacked: true
                },
                plotOptions: {
                    bar: { horizontal: false }
                },
                xaxis: {
                    categories: categories,
                    labels: { rotate: -45 }
                },
                legend: { position: 'top' },
                colors: ['#0ab39c', '#f7b84b', '#405189', '#f06548']
            };

            const chart = new ApexCharts(document.querySelector("#farmTypeChart"), options);
            chart.render();
        }

        function renderCoopMembershipChart(data) {
            const options = {
                series: data.map(lga => parseInt(lga.coop_members)),
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: data.map(lga => lga.lga_name),
                legend: { position: 'bottom' },
                colors: ['#0ab39c', '#f7b84b', '#405189', '#f06548', '#299cdb']
            };

            const chart = new ApexCharts(document.querySelector("#coopMembershipChart"), options);
            chart.render();
        }

        // Side-by-Side Comparison
        function populateLgaSelectors() {
            $.ajax({
                url: '{{ route("governor.lga_comparison.performance_ranking") }}',
                method: 'GET',
                success: function(response) {
                    allLgas = response.rankings;
                    let options = '';
                    allLgas.forEach(lga => {
                        options += `<option value="${lga.lga_code}">${lga.lga_name}</option>`;
                    });
                    $('#lgaSelector, #capacityLgaFilter').append(options);
                }
            });
        }

        $('#compareBtn').on('click', function() {
            const selectedLgas = $('#lgaSelector').val();
            
            if (!selectedLgas || selectedLgas.length < 2) {
                Swal.fire('Warning', 'Please select at least 2 LGAs', 'warning');
                return;
            }
            
            if (selectedLgas.length > 5) {
                Swal.fire('Warning', 'Maximum 5 LGAs can be compared', 'warning');
                return;
            }

            // Get LGA IDs from codes
            const lgaIds = selectedLgas.map(code => {
                const lga = allLgas.find(l => l.lga_code === code);
                return lga ? lga.rank : null; // Using rank as proxy, should use actual ID
            }).filter(id => id !== null);

            loadComparison(lgaIds);
        });

        function loadComparison(lgaIds) {
            $.ajax({
                url: '{{ route("governor.lga_comparison.compare") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    lga_ids: lgaIds
                },
                success: function(response) {
                    $('#noComparisonMessage').hide();
                    $('#comparisonResults').show();
                    renderBasicComparison(response.basic_comparison);
                    renderComparisonCharts(response);
                },
                error: function(xhr) {
                    showError('Failed to load comparison');
                }
            });
        }

        function renderBasicComparison(data) {
            // Build table dynamically
            let headerHtml = '<th>Metric</th>';
            data.forEach(lga => {
                headerHtml += `<th>${lga.lga_name}</th>`;
            });
            $('#basicComparisonTable thead tr').html(headerHtml);

            const metrics = [
                { key: 'total_farmers', label: 'Total Farmers' },
                { key: 'female_farmers', label: 'Female Farmers' },
                { key: 'male_farmers', label: 'Male Farmers' },
                { key: 'total_hectares', label: 'Total Hectares', decimals: 2 },
                { key: 'avg_farm_size', label: 'Avg Farm Size', decimals: 2 },
                { key: 'total_farms', label: 'Total Farms' }
            ];

            let bodyHtml = '';
            metrics.forEach(metric => {
                bodyHtml += `<tr><td><strong>${metric.label}</strong></td>`;
                data.forEach(lga => {
                    const value = formatNumber(lga[metric.key], metric.decimals || 0);
                    bodyHtml += `<td>${value}</td>`;
                });
                bodyHtml += '</tr>';
            });
            $('#basicComparisonBody').html(bodyHtml);
        }

        function renderComparisonCharts(response) {
            // Farm type distribution chart
            // Top crops display
            // Resource success chart
            // Implementation similar to above charts
        }

        // Geographic Analysis
        function loadGeographicAnalysis() {
            $.ajax({
                url: '{{ route("governor.lga_comparison.geographic_analysis") }}',
                method: 'GET',
                success: function(response) {
                    currentGeographic = response.geographic_distribution;
                    renderGeographicTable(response.geographic_distribution);
                    updateStateTotals(response.state_totals);
                    renderDistributionCharts(response.geographic_distribution);
                },
                error: function(xhr) {
                    showError('Failed to load geographic analysis');
                }
            });
        }

        function renderGeographicTable(data) {
            let html = '';
            data.forEach(lga => {
                html += `
                    <tr>
                        <td><strong>${lga.lga_name}</strong></td>
                        <td>${lga.code}</td>
                        <td>${formatNumber(lga.farmer_count)}</td>
                        <td><span class="badge bg-primary-subtle text-primary">${lga.farmer_percentage}%</span></td>
                        <td>${formatNumber(lga.total_hectares, 2)} ha</td>
                        <td><span class="badge bg-success-subtle text-success">${lga.hectare_percentage}%</span></td>
                        <td>${formatNumber(lga.farm_count)}</td>
                        <td>${lga.farms_per_farmer}</td>
                    </tr>
                `;
            });
            $('#geographicTableBody').html(html);
        }

        function updateStateTotals(totals) {
            $('#stateTotalFarmers').text(formatNumber(totals.total_farmers));
            $('#stateTotalHectares').text(formatNumber(totals.total_hectares, 2));
        }

        function renderDistributionCharts(data) {
            // Farmer Distribution Chart
            const farmerOptions = {
                series: data.map(lga => parseInt(lga.farmer_count)),
                chart: {
                    type: 'pie',
                    height: 400
                },
                labels: data.map(lga => lga.lga_name),
                legend: {
                    position: 'bottom',
                    height: 100
                },
                colors: generateColorPalette(data.length)
            };

            const farmerChart = new ApexCharts(document.querySelector("#farmerDistributionChart"), farmerOptions);
            farmerChart.render();

            // Land Distribution Chart
            const landOptions = {
                series: data.map(lga => parseFloat(lga.total_hectares)),
                chart: {
                    type: 'pie',
                    height: 400
                },
                labels: data.map(lga => lga.lga_name),
                legend: {
                    position: 'bottom',
                    height: 100
                },
                colors: generateColorPalette(data.length)
            };

            const landChart = new ApexCharts(document.querySelector("#landDistributionChart"), landOptions);
            landChart.render();
        }

        // Update Quick Stats
        function updateQuickStats(rankings) {
            const totalFarmers = rankings.reduce((sum, lga) => sum + parseInt(lga.total_farmers), 0);
            const totalHectares = rankings.reduce((sum, lga) => sum + parseFloat(lga.total_hectares), 0);
            const avgFarmSize = totalHectares / totalFarmers;

            animateCounter($('#totalFarmersCount .counter-value'), totalFarmers);
            animateCounter($('#totalHectaresCount .counter-value'), totalHectares);
            animateCounter($('#avgFarmSizeCount .counter-value'), avgFarmSize.toFixed(2));
        }

        // Export Functions
        $('#exportRankings').on('click', function() {
            exportToCSV(currentRankings, 'lga_performance_rankings.csv');
        });

        $('#exportCapacity').on('click', function() {
            exportToCSV(currentCapacity, 'lga_capacity_analysis.csv');
        });

        $('#exportGeographic').on('click', function() {
            exportToCSV(currentGeographic, 'lga_geographic_distribution.csv');
        });

        // Utility Functions
        function formatNumber(num, decimals = 0) {
            if (num === null || num === undefined) return '0';
            const parsed = parseFloat(num);
            return parsed.toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }

        function getMetricLabel(metric) {
            const labels = {
                'total_farmers': 'Total Farmers',
                'total_hectares': 'Total Hectares',
                'avg_farm_size': 'Average Farm Size (ha)',
                'active_farmers': 'Active Farmers',
                'pending_farmers': 'Pending Farmers'
            };
            return labels[metric] || metric;
        }

        function generateColorPalette(count) {
            const colors = [
                '#0ab39c', '#f7b84b', '#405189', '#f06548', '#299cdb',
                '#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7',
                '#dfe6e9', '#74b9ff', '#a29bfe', '#fd79a8', '#fdcb6e'
            ];
            return colors.slice(0, count);
        }

        function animateCounter(element, target) {
            const start = 0;
            const duration = 1000;
            const increment = target / (duration / 16);
            let current = start;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.text(target);
                    clearInterval(timer);
                } else {
                    element.text(Math.floor(current));
                }
            }, 16);
        }

        function exportToCSV(data, filename) {
            if (!data || data.length === 0) {
                Swal.fire('Warning', 'No data to export', 'warning');
                return;
            }

            const headers = Object.keys(data[0]);
            let csv = headers.join(',') + '\n';

            data.forEach(row => {
                const values = headers.map(header => {
                    const value = row[header];
                    return typeof value === 'string' ? `"${value}"` : value;
                });
                csv += values.join(',') + '\n';
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function showError(message) {
            Swal.fire('Error', message, 'error');
        }
    });
</script>
@endpush


@push('styles')
<style>
        /* Custom styles to be added to the blade layout or as inline styles */
        
        /* Performance Cards */
        .performance-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
        }

        .performance-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Rank Badges */
        .rank-badge-gold {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .rank-badge-silver {
            background: linear-gradient(135deg, #C0C0C0, #808080);
            color: white;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .rank-badge-bronze {
            background: linear-gradient(135deg, #CD7F32, #8B4513);
            color: white;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        /* Table Enhancements */
        .comparison-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .comparison-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .comparison-table tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Progress Bars */
        .progress-bar-custom {
            height: 8px;
            border-radius: 4px;
            background: linear-gradient(90deg, #0ab39c, #299cdb);
        }

        /* Metric Cards */
        .metric-card {
            border-left: 4px solid #0ab39c;
            padding: 1.5rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .metric-card h6 {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .metric-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #0ab39c;
        }

        /* Tab Styling */
        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #0ab39c;
        }

        .nav-tabs-custom .nav-link.active {
            color: #0ab39c;
            border-bottom: 3px solid #0ab39c;
            background: transparent;
        }

        /* Filter Section */
        .filter-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        /* Comparison Grid */
        .comparison-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .comparison-item {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-top: 3px solid #0ab39c;
        }

        .comparison-item h5 {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .comparison-item .value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #212529;
        }

        /* Loading Spinner */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 8px;
        }

        /* Export Button */
        .btn-export {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* LGA Selector */
        .lga-selector-wrapper {
            position: relative;
        }

        .lga-selector-wrapper select {
            height: 200px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
        }

        .lga-selector-wrapper select:focus {
            border-color: #0ab39c;
            box-shadow: 0 0 0 0.2rem rgba(10, 179, 156, 0.25);
        }

        /* Top Crops Display */
        .crop-item {
            background: #f8f9fa;
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .crop-item .crop-name {
            font-weight: 500;
            color: #212529;
        }

        .crop-item .crop-count {
            background: #0ab39c;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .comparison-grid {
                grid-template-columns: 1fr;
            }

            .nav-tabs-custom .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            .metric-card .value {
                font-size: 1.5rem;
            }
        }

        /* Chart Container */
        .chart-container {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        /* Alert Styles */
        .alert-custom {
            border-left: 4px solid #0ab39c;
            background: #e8f8f5;
            color: #0a5f4e;
            padding: 1rem 1.5rem;
            border-radius: 8px;
        }

        /* Status Indicators */
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .status-indicator.active {
            background: #0ab39c;
        }

        .status-indicator.pending {
            background: #f7b84b;
        }

        .status-indicator.inactive {
            background: #f06548;
        }

        /* Data Grid Enhancement */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .data-grid-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .data-grid-item label {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.25rem;
            display: block;
        }

        .data-grid-item .value {
            font-size: 1.25rem;
            font-weight: bold;
            color: #212529;
        }

        /* Scroll Indicators */
        .scroll-indicator {
            text-align: center;
            padding: 0.5rem;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .scroll-indicator i {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
    </style>
   @endpush