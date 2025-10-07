@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Policy Insights</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('governor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Policy Insights</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Filter Panel -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-filter-3-line me-1"></i> Analysis Filters
                </h5>
            </div>
            <div class="card-body">
                <form id="analysisFilterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender" id="genderFilter">
                                <option value="">All</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Age Group</label>
                            <select class="form-select" name="age_group" id="ageGroupFilter">
                                <option value="">All</option>
                                <option value="Youth">Youth (18-35)</option>
                                <option value="Adult">Adult (36-59)</option>
                                <option value="Senior">Senior (60+)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Farm Type</label>
                            <select class="form-select" name="farm_type" id="farmTypeFilter">
                                <option value="">All</option>
                                <option value="crops">Crops</option>
                                <option value="livestock">Livestock</option>
                                <option value="fisheries">Fisheries</option>
                                <option value="orchards">Orchards</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Crop Type</label>
                            <input type="text" class="form-control" name="crop_type" id="cropTypeFilter" placeholder="e.g., Cassava">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">LGA</label>
                            <select class="form-select" name="lga_id" id="lgaFilter">
                                <option value="">All LGAs</option>
                                <!-- LGAs populated via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Education Level</label>
                            <select class="form-select" name="educational_level" id="educationFilter">
                                <option value="">All</option>
                                <option value="None">None</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="Tertiary">Tertiary</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" id="applyFilters">
                                    <i class="ri-search-line me-1"></i> Analyze
                                </button>
                                <button type="button" class="btn btn-soft-secondary" id="resetFilters">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </button>
                                <button type="button" class="btn btn-soft-success" id="exportResults">
                                    <i class="ri-download-2-line me-1"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Analysis Results -->
<div class="row" id="analysisResults" style="display: none;">
    <!-- Summary Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Matching Farmers</p>
                        <h4 class="mb-0" id="totalCount">-</h4>
                        <p class="text-muted mb-0 mt-2">
                            <small>Total matching criteria</small>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-user-line text-primary"></i>
                            </span>
                        </div>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Hectares</p>
                        <h4 class="mb-0" id="totalHectares">-</h4>
                        <p class="text-muted mb-0 mt-2">
                            <small>Under cultivation</small>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-landscape-line text-success"></i>
                            </span>
                        </div>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Avg. Farm Size</p>
                        <h4 class="mb-0" id="avgFarmSize">-</h4>
                        <p class="text-muted mb-0 mt-2">
                            <small>Hectares per farm</small>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-pie-chart-line text-info"></i>
                            </span>
                        </div>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">LGAs Covered</p>
                        <h4 class="mb-0" id="lgasCount">-</h4>
                        <p class="text-muted mb-0 mt-2">
                            <small>Geographic spread</small>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-map-pin-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribution Charts -->
<div class="row" id="distributionCharts" style="display: none;">
    <!-- LGA Distribution -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-map-pin-line me-1"></i> Distribution by LGA
                </h5>
            </div>
            <div class="card-body">
                <div id="lgaDistributionChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Gender Breakdown -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-user-3-line me-1"></i> Gender Distribution
                </h5>
            </div>
            <div class="card-body">
                <div id="genderDistributionChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Age Distribution -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-group-line me-1"></i> Age Distribution
                </h5>
            </div>
            <div class="card-body">
                <div id="ageDistributionChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Farm Type Distribution -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-plant-line me-1"></i> Farm Type Distribution
            </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" id="farmTypeTable">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Farm Type</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Percentage</th>
                            </tr>
                        </thead>
                        <tbody id="farmTypeTableBody">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Breakdown Table -->
<div class="row" id="detailedTable" style="display: none;">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-table-line me-1"></i> LGA Breakdown
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="lgaBreakdownTable">
                        <thead class="table-light">
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Farmers</th>
                                <th class="text-end">Hectares</th>
                                <th class="text-end">Avg. Size</th>
                                <th class="text-end">% of Total</th>
                            </tr>
                        </thead>
                        <tbody id="lgaBreakdownBody">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Empty State -->
<div class="row" id="emptyState">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="avatar-lg mx-auto mb-4">
                    <span class="avatar-title bg-soft-info text-info rounded-circle fs-1">
                        <i class="ri-search-line"></i>
                    </span>
                </div>
                <h5 class="mb-2">Select Your Analysis Criteria</h5>
                <p class="text-muted">
                    Use the filters above to analyze farmer demographics, production patterns, and geographic distribution.
                    <br>
                    Examples: "Female cassava farmers", "Youth in livestock", "Orchard farms in Makurdi"
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
let lgaChart, genderChart, ageChart;

document.addEventListener('DOMContentLoaded', function() {
    // Load LGAs
    loadLGAs();

    // Apply Filters
    document.getElementById('applyFilters').addEventListener('click', function() {
        performAnalysis();
    });

    // Reset Filters
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('analysisFilterForm').reset();
        hideResults();
    });

    // Export Results
    document.getElementById('exportResults').addEventListener('click', function() {
        exportAnalysis();
    });
});

function loadLGAs() {
    // You would typically load this via AJAX from your backend
    const lgaSelect = document.getElementById('lgaFilter');
    // Placeholder - replace with actual AJAX call
}

function performAnalysis() {
    const formData = new FormData(document.getElementById('analysisFilterForm'));
    const params = new URLSearchParams(formData);

    // Show loading state
    showLoadingState();

    fetch(`{{ route('governor.policy_insights.demographic_analysis') }}?${params}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        displayResults(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while performing the analysis.');
    });
}

function displayResults(data) {
    // Hide empty state
    document.getElementById('emptyState').style.display = 'none';

    // Show results sections
    document.getElementById('analysisResults').style.display = 'flex';
    document.getElementById('distributionCharts').style.display = 'flex';
    document.getElementById('detailedTable').style.display = 'block';

    // Update summary cards
    document.getElementById('totalCount').textContent = data.total_count.toLocaleString();
    document.getElementById('totalHectares').textContent = parseFloat(data.total_hectares).toFixed(2).toLocaleString();
    document.getElementById('avgFarmSize').textContent = parseFloat(data.average_farm_size).toFixed(2);
    document.getElementById('lgasCount').textContent = data.lga_distribution.length;

    // Render charts
    renderLGAChart(data.lga_distribution);
    renderGenderChart(data.gender_breakdown);
    renderAgeChart(data.age_distribution);

    // Populate tables
    populateLGATable(data.lga_distribution, data.total_count);
    populateFarmTypeTable(data);
}

function renderLGAChart(distribution) {
    const options = {
        series: distribution.map(d => d.count),
        chart: {
            type: 'donut',
            height: 350
        },
        labels: distribution.map(d => d.name),
        colors: ['#556ee6', '#34c38f', '#f46a6a', '#50a5f1', '#f1b44c', '#343a40'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%"
            }
        }
    };

    if (lgaChart) {
        lgaChart.destroy();
    }
    lgaChart = new ApexCharts(document.querySelector("#lgaDistributionChart"), options);
    lgaChart.render();
}

function renderGenderChart(breakdown) {
    const options = {
        series: breakdown.map(d => d.count),
        chart: {
            type: 'pie',
            height: 350
        },
        labels: breakdown.map(d => d.gender),
        colors: ['#556ee6', '#f46a6a'],
        legend: {
            position: 'bottom'
        }
    };

    if (genderChart) {
        genderChart.destroy();
    }
    genderChart = new ApexCharts(document.querySelector("#genderDistributionChart"), options);
    genderChart.render();
}

function renderAgeChart(distribution) {
    const options = {
        series: [{
            data: distribution.map(d => d.count)
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                horizontal: true,
                colors: {
                    ranges: [{
                        from: 0,
                        to: 1000000,
                        color: '#34c38f'
                    }]
                }
            }
        },
        xaxis: {
            categories: distribution.map(d => d.age_group)
        }
    };

    if (ageChart) {
        ageChart.destroy();
    }
    ageChart = new ApexCharts(document.querySelector("#ageDistributionChart"), options);
    ageChart.render();
}

function populateLGATable(distribution, total) {
    const tbody = document.getElementById('lgaBreakdownBody');
    tbody.innerHTML = '';

    distribution.forEach(lga => {
        const percentage = ((lga.count / total) * 100).toFixed(2);
        const row = `
            <tr>
                <td><h6 class="mb-0">${lga.name}</h6></td>
                <td class="text-end">${lga.count.toLocaleString()}</td>
                <td class="text-end">-</td>
                <td class="text-end">-</td>
                <td class="text-end">
                    <span class="badge badge-soft-primary">${percentage}%</span>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function populateFarmTypeTable(data) {
    // Implementation depends on your data structure
}

function showLoadingState() {
    document.getElementById('emptyState').innerHTML = `
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Analyzing data...</p>
                </div>
            </div>
        </div>
    `;
}

function hideResults() {
    document.getElementById('analysisResults').style.display = 'none';
    document.getElementById('distributionCharts').style.display = 'none';
    document.getElementById('detailedTable').style.display = 'none';
    document.getElementById('emptyState').style.display = 'block';
    document.getElementById('emptyState').innerHTML = `
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <span class="avatar-title bg-soft-info text-info rounded-circle fs-1">
                            <i class="ri-search-line"></i>
                        </span>
                    </div>
                    <h5 class="mb-2">Select Your Analysis Criteria</h5>
                    <p class="text-muted">
                        Use the filters above to analyze farmer demographics, production patterns, and geographic distribution.
                    </p>
                </div>
            </div>
        </div>
    `;
}

function exportAnalysis() {
    const formData = new FormData(document.getElementById('analysisFilterForm'));
    const params = new URLSearchParams(formData);
    window.location.href = `{{ route('analytics.export') }}?${params}&type=policy_insights`;
}
</script>
@endpush
</parameter>
</invoke>