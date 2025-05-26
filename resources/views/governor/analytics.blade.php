@extends('layouts.governor')

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Analytics</h4>
                    <div class="page-title-right">
                        <a href="{{ route('super_admin.reports') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-alt me-1"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Total Practitioners</h5>
                        <p class="fw-bold fs-3 text-primary">{{ $totalPractitioners }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Gender Breakdown</h5>
                        <p class="fw-bold">Male: {{ $genderBreakdown['Male'] ?? 0 }} | Female: {{ $genderBreakdown['Female'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Top LGA</h5>
                        <p class="fw-bold">{{ $lgaDistribution->first()->lga ?? 'N/A' }}: {{ $lgaDistribution->first()->count ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Practice Distribution</h5>
                        <p class="fw-bold">Crop: {{ $practiceDistribution['Crop Farmers'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Gender Distribution</h5>
                        <div id="genderChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Practitioners by LGA</h5>
                        <div id="lgaChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Age Distribution</h5>
                        <div id="ageChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Income Levels</h5>
                        <div id="incomeChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Gender Pie Chart
        var genderOptions = {
            chart: { type: 'pie', height: 300 },
            series: [{{ $genderBreakdown['Male'] ?? 0 }}, {{ $genderBreakdown['Female'] ?? 0 }}, {{ $genderBreakdown['Other'] ?? 0 }}],
            labels: ['Male', 'Female', 'Other'],
            colors: ['#1cbb8c', '#ff3d60', '#fcb92c'],
            responsive: [{ breakpoint: 480, options: { chart: { width: 200 }, legend: { position: 'bottom' } } }]
        };
        var genderChart = new ApexCharts(document.querySelector("#genderChart"), genderOptions);
        genderChart.render();

        // LGA Bar Chart
        var lgaOptions = {
            chart: { type: 'bar', height: 300 },
            series: [{ name: 'Practitioners', data: @json($lgaDistribution->pluck('count')) }],
            xaxis: { categories: @json($lgaDistribution->pluck('lga')), labels: { rotate: -45 } },
            colors: ['#1cbb8c'],
            responsive: [{ breakpoint: 480, options: { chart: { height: 250 } } }]
        };
        var lgaChart = new ApexCharts(document.querySelector("#lgaChart"), lgaOptions);
        lgaChart.render();

        // Age Line Chart
        var ageOptions = {
            chart: { type: 'line', height: 300 },
            series: [{ name: 'Practitioners', data: @json($ageGroups->pluck('count')) }],
            xaxis: { categories: @json($ageGroups->pluck('age_group')) },
            colors: ['#1cbb8c'],
            stroke: { curve: 'smooth' },
            responsive: [{ breakpoint: 480, options: { chart: { height: 250 } } }]
        };
        var ageChart = new ApexCharts(document.querySelector("#ageChart"), ageOptions);
        ageChart.render();

        // Income Donut Chart
        var incomeOptions = {
            chart: { type: 'donut', height: 300 },
            series: @json($incomeLevels->pluck('count')),
            labels: @json($incomeLevels->pluck('income_level')),
            colors: ['#1cbb8c', '#ff3d60', '#fcb92c'],
            responsive: [{ breakpoint: 480, options: { chart: { width: 200 }, legend: { position: 'bottom' } } }]
        };
        var incomeChart = new ApexCharts(document.querySelector("#incomeChart"), incomeOptions);
        incomeChart.render();
    </script>
@endpush