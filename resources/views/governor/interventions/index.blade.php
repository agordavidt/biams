@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Intervention Tracking</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('governor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Interventions</li>
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
                    <i class="ri-filter-3-line me-1"></i> Filter Interventions
                </h5>
            </div>
            <div class="card-body">
                <form id="interventionFilterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Resource</label>
                            <select class="form-select" name="resource_id" id="resourceFilter">
                                <option value="">All Resources</option>
                                <!-- Populated via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Partner</label>
                            <select class="form-select" name="partner_id" id="partnerFilter">
                                <option value="">All Partners</option>
                                <!-- Populated via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="statusFilter">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="granted">Granted</option>
                                <option value="declined">Declined</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">LGA</label>
                            <select class="form-select" name="lga_id" id="lgaFilter">
                                <option value="">All LGAs</option>
                                <!-- Populated via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100" id="applyFilters">
                                <i class="ri-search-line me-1"></i> Analyze
                            </button>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" name="date_from" id="dateFromFilter">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" name="date_to" id="dateToFilter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-soft-secondary" id="resetFilters">
                                    <i class="ri-refresh-line me-1"></i> Reset
                                </button>
                                <button type="button" class="btn btn-soft-success" id="exportReport">
                                    <i class="ri-download-2-line me-1"></i> Export Report
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Tabs -->
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#beneficiaryReport" role="tab">
                    <i class="ri-user-heart-line me-1"></i> Beneficiary Report
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#partnerActivities" role="tab">
                    <i class="ri-handshake-line me-1"></i> Partner Activities
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#coverageAnalysis" role="tab">
                    <i class="ri-radar-line me-1"></i> Coverage Analysis
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Beneficiary Report Tab -->
            <div class="tab-pane active" id="beneficiaryReport" role="tabpanel">
                <!-- Summary Cards -->
                <div class="row mt-3">
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-uppercase fw-medium text-muted mb-1">Total Applications</p>
                                        <h4 class="mb-0" id="totalApplications">-</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                                <i class="ri-file-list-3-line text-primary"></i>
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
                                        <p class="text-uppercase fw-medium text-muted mb-1">Granted</p>
                                        <h4 class="mb-0 text-success" id="grantedApplications">-</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                                <i class="ri-checkbox-circle-line text-success"></i>
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
                                        <p class="text-uppercase fw-medium text-muted mb-1">Pending</p>
                                        <h4 class="mb-0 text-warning" id="pendingApplications">-</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                                <i class="ri-time-line text-warning"></i>
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
                                        <p class="text-uppercase fw-medium text-muted mb-1">Success Rate</p>
                                        <h4 class="mb-0 text-info" id="successRate">-</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                                <i class="ri-percent-line text-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LGA Breakdown -->
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-map-pin-line me-1"></i> LGA Breakdown
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>LGA</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Granted</th>
                                                <th class="text-center">Pending</th>
                                                <th class="text-center">Declined</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lgaBreakdownBody">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    Apply filters to view data
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-pie-chart-line me-1"></i> Status Distribution
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="statusChart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resource Breakdown -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-database-2-line me-1"></i> Resource Breakdown
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Resource Name</th>
                                                <th class="text-center">Applications</th>
                                                <th class="text-center">Granted</th>
                                                <th class="text-center">Success Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody id="resourceBreakdownBody">
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    Apply filters to view data
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partner Activities Tab -->
            <div class="tab-pane" id="partnerActivities" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-handshake-line me-1"></i> Partner Performance
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Partner Name</th>
                                                <th>Organization Type</th>
                                                <th class="text-center">Total Resources</th>
                                                <th class="text-center">Applications</th>
                                                <th class="text-center">Granted</th>
                                                <th class="text-center">Impact Score</th>
                                            </tr>
                                        </thead>
                                        <tbody id="partnerStatsBody">
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    Loading partner data...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resource Performance by Partner -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-line-chart-line me-1"></i> Resource Performance Detail
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="partnerResourceChart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coverage Analysis Tab -->
            <div class="tab-pane" id="coverageAnalysis" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <h2 class="mb-1" id="overallCoverage">-</h2>
                                    <p class="text-muted mb-0">Overall State Coverage</p>
                                </div>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Farmers:</span>
                                        <strong id="totalFarmersCount">-</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Beneficiaries:</span>
                                        <strong id="totalBeneficiaries">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Underserved LGAs -->
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">
                                    <i class="ri-alert-line me-1"></i> Underserved LGAs (< 20% Coverage)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="underservedLGAs">
                                    <p class="text-muted text-center py-3">Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-bar-chart-box-line me-1"></i> Coverage by LGA
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="coverageChart" style="height: 400px;"></div>
                            </div>
                        </div>

                        <!-- Detailed Coverage Table -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-table-line me-1"></i> Detailed Coverage Data
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>LGA</th>
                                                <th class="text-end">Total Farmers</th>
                                                <th class="text-end">Beneficiaries</th>
                                                <th class="text-end">Coverage %</th>
                                                <th class="text-end">Gap</th>
                                            </tr>
                                        </thead>
                                        <tbody id="coverageTableBody">
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    Loading coverage data...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
let statusChart, coverageChart, partnerResourceChart;

document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadPartnerActivities();
    loadCoverageAnalysis();

    // Event listeners
    document.getElementById('applyFilters').addEventListener('click', function() {
        loadBeneficiaryReport();
    });

    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('interventionFilterForm').reset();
    });

    document.getElementById('exportReport').addEventListener('click', function() {
        exportInterventionReport();
    });
});

function loadBeneficiaryReport() {
    const formData = new FormData(document.getElementById('interventionFilterForm'));
    const params = new URLSearchParams(formData);

    fetch(`{{ route('governor.interventions.beneficiary_report') }}?${params}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        displayBeneficiaryReport(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function displayBeneficiaryReport(data) {
    // Update summary cards
    document.getElementById('totalApplications').textContent = data.summary.total_applications.toLocaleString();
    document.getElementById('grantedApplications').textContent = data.summary.granted.toLocaleString();
    document.getElementById('pendingApplications').textContent = data.summary.pending.toLocaleString();
    document.getElementById('successRate').textContent = data.summary.success_rate + '%';

    // Populate LGA breakdown table
    const lgaBody = document.getElementById('lgaBreakdownBody');
    lgaBody.innerHTML = '';
    data.lga_breakdown.forEach(lga => {
        lgaBody.innerHTML += `
            <tr>
                <td><h6 class="mb-0">${lga.lga_name}</h6></td>
                <td class="text-center">${lga.total_applications}</td>
                <td class="text-center"><span class="badge badge-soft-success">${lga.granted}</span></td>
                <td class="text-center"><span class="badge badge-soft-warning">${lga.pending}</span></td>
                <td class="text-center"><span class="badge badge-soft-danger">${lga.declined}</span></td>
            </tr>
        `;
    });

    // Populate resource breakdown table
    const resourceBody = document.getElementById('resourceBreakdownBody');
    resourceBody.innerHTML = '';
    data.resource_breakdown.forEach(resource => {
        const successRate = ((resource.granted_count / resource.application_count) * 100).toFixed(1);
        resourceBody.innerHTML += `
            <tr>
                <td><h6 class="mb-0">${resource.resource_name}</h6></td>
                <td class="text-center">${resource.application_count}</td>
                <td class="text-center">${resource.granted_count}</td>
                <td class="text-center">
                    <span class="badge badge-soft-${successRate > 70 ? 'success' : successRate > 40 ? 'warning' : 'danger'}">
                        ${successRate}%
                    </span>
                </td>
            </tr>
        `;
    });

    // Render status chart
    renderStatusChart(data.summary);
}

function renderStatusChart(summary) {
    const options = {
        series: [summary.granted, summary.pending, summary.declined],
        chart: {
            type: 'donut',
            height: 300
        },
        labels: ['Granted', 'Pending', 'Declined'],
        colors: ['#34c38f', '#f1b44c', '#f46a6a'],
        legend: {
            position: 'bottom'
        }
    };

    if (statusChart) {
        statusChart.destroy();
    }
    statusChart = new ApexCharts(document.querySelector("#statusChart"), options);
    statusChart.render();
}

function loadPartnerActivities() {
    fetch(`{{ route('governor.interventions.partner_activities') }}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        displayPartnerActivities(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function displayPartnerActivities(data) {
    const tbody = document.getElementById('partnerStatsBody');
    tbody.innerHTML = '';
    
    data.partner_stats.forEach(partner => {
        const impactScore = partner.granted_applications > 0 ? 
            ((partner.granted_applications / partner.total_applications) * 100).toFixed(0) : 0;
        
        tbody.innerHTML += `
            <tr>
                <td><h6 class="mb-0">${partner.legal_name}</h6></td>
                <td><span class="badge badge-soft-info">${partner.organization_type}</span></td>
                <td class="text-center">${partner.total_resources}</td>
                <td class="text-center">${partner.total_applications}</td>
                <td class="text-center">${partner.granted_applications}</td>
                <td class="text-center">
                    <span class="badge badge-soft-${impactScore > 70 ? 'success' : impactScore > 40 ? 'warning' : 'secondary'}">
                        ${impactScore}/100
                    </span>
                </td>
            </tr>
        `;
    });

    // Render partner resource chart
    renderPartnerResourceChart(data.resource_performance);
}

function renderPartnerResourceChart(resourcePerformance) {
    const categories = [...new Set(resourcePerformance.map(r => r.partner_name))];
    const series = [{
        name: 'Applications',
        data: categories.map(partner => {
            return resourcePerformance
                .filter(r => r.partner_name === partner)
                .reduce((sum, r) => sum + r.application_count, 0);
        })
    }, {
        name: 'Successful',
        data: categories.map(partner => {
            return resourcePerformance
                .filter(r => r.partner_name === partner)
                .reduce((sum, r) => sum + r.success_count, 0);
        })
    }];

    const options = {
        series: series,
        chart: {
            type: 'bar',
            height: 400
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%'
            }
        },
        xaxis: {
            categories: categories
        },
        colors: ['#556ee6', '#34c38f'],
        legend: {
            position: 'top'
        }
    };

    if (partnerResourceChart) {
        partnerResourceChart.destroy();
    }
    partnerResourceChart = new ApexCharts(document.querySelector("#partnerResourceChart"), options);
    partnerResourceChart.render();
}

function loadCoverageAnalysis() {
    fetch(`{{ route('governor.interventions.coverage_analysis') }}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        displayCoverageAnalysis(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function displayCoverageAnalysis(data) {
    // Update overall coverage
    document.getElementById('overallCoverage').textContent = data.state_summary.overall_coverage + '%';
    document.getElementById('totalFarmersCount').textContent = data.state_summary.total_farmers.toLocaleString();
    document.getElementById('totalBeneficiaries').textContent = data.state_summary.total_beneficiaries.toLocaleString();

    // Display underserved LGAs
    const underservedDiv = document.getElementById('underservedLGAs');
    if (data.underserved_lgas.length === 0) {
        underservedDiv.innerHTML = '<p class="text-success text-center py-3"><i class="ri-checkbox-circle-line me-1"></i> No severely underserved LGAs</p>';
    } else {
        underservedDiv.innerHTML = data.underserved_lgas.map(lga => `
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div>
                    <h6 class="mb-0">${lga.lga_name}</h6>
                    <small class="text-muted">${lga.beneficiaries} of ${lga.total_farmers} farmers</small>
                </div>
                <span class="badge badge-soft-danger">${lga.coverage_percentage}%</span>
            </div>
        `).join('');
    }

    // Populate coverage table
    const tbody = document.getElementById('coverageTableBody');
    tbody.innerHTML = '';
    data.coverage_data.forEach(lga => {
        const badgeClass = lga.coverage_percentage >= 50 ? 'success' : lga.coverage_percentage >= 20 ? 'warning' : 'danger';
        tbody.innerHTML += `
            <tr>
                <td><h6 class="mb-0">${lga.lga_name}</h6></td>
                <td class="text-end">${lga.total_farmers.toLocaleString()}</td>
                <td class="text-end">${lga.beneficiaries.toLocaleString()}</td>
                <td class="text-end">
                    <span class="badge badge-soft-${badgeClass}">${lga.coverage_percentage}%</span>
                </td>
                <td class="text-end text-muted">${lga.gap.toLocaleString()}</td>
            </tr>
        `;
    });

    // Render coverage chart
    renderCoverageChart(data.coverage_data);
}

function renderCoverageChart(coverageData) {
    const options = {
        series: [{
            name: 'Coverage %',
            data: coverageData.map(d => d.coverage_percentage)
        }],
        chart: {
            type: 'bar',
            height: 400
        },
        plotOptions: {
            bar: {
                horizontal: true,
                colors: {
                    ranges: [{
                        from: 0,
                        to: 20,
                        color: '#f46a6a'
                    }, {
                        from: 20,
                        to: 50,
                        color: '#f1b44c'
                    }, {
                        from: 50,
                        to: 100,
                        color: '#34c38f'
                    }]
                }
            }
        },
        xaxis: {
            categories: coverageData.map(d => d.lga_name),
            max: 100
        }
    };

    if (coverageChart) {
        coverageChart.destroy();
    }
    coverageChart = new ApexCharts(document.querySelector("#coverageChart"), options);
    coverageChart.render();
}

function exportInterventionReport() {
    const formData = new FormData(document.getElementById('interventionFilterForm'));
    const params = new URLSearchParams(formData);
    window.location.href = `{{ route('analytics.export') }}?${params}&type=interventions`;
}
</script>
@endpush