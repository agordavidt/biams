@extends('layouts.super_admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Analytics</h4>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Practitioners</h5>
                        <p class="fw-bold fs-3">{{ $totalPractitioners }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gender Breakdown</h5>
                        <p class="fw-bold">Male: {{ $genderBreakdown['Male'] ?? 0 }} | Female: {{ $genderBreakdown['Female'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top LGA</h5>
                        <p class="fw-bold">{{ $lgaDistribution->first()->lga ?? 'N/A' }}: {{ $lgaDistribution->first()->count ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Practice Distribution</h5>
                        <p class="fw-bold">Crop: {{ $practiceDistribution['Crop Farmers'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gender Distribution</h5>
                        <div id="genderChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Practitioners by LGA</h5>
                        <div id="lgaChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Age Distribution</h5>
                        <div id="ageChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Income Levels</h5>
                        <div id="incomeChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rice Farmers Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Rice Farmers Report</h5>
                        <table class="table datatable" id="riceFarmersTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>LGA</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Farm Size</th>
                                    <th>Income Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riceFarmers as $farmer)
                                    <tr>
                                        <td>{{ $farmer->user->name }}</td>
                                        <td>{{ $farmer->user->profile->lga }}</td>
                                        <td>{{ $farmer->user->profile->gender }}</td>
                                        <td>{{ \Carbon\Carbon::parse($farmer->user->profile->dob)->age }}</td>
                                        <td>{{ $farmer->farm_size }}</td>
                                        <td>{{ $farmer->user->profile->income_level }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Metadata -->
        <div class="row">
            <div class="col-12 text-muted">
                <p>Generated on {{ now()->format('F d, Y, H:i') }} | Based on {{ $totalPractitioners }} records</p>
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
        };
        var genderChart = new ApexCharts(document.querySelector("#genderChart"), genderOptions);
        genderChart.render();

        // LGA Bar Chart
        var lgaOptions = {
            chart: { type: 'bar', height: 300 },
            series: [{ name: 'Practitioners', data: @json($lgaDistribution->pluck('count')) }],
            xaxis: { categories: @json($lgaDistribution->pluck('lga')) },
            colors: ['#1cbb8c'],
        };
        var lgaChart = new ApexCharts(document.querySelector("#lgaChart"), lgaOptions);
        lgaChart.render();

        // Age Line Chart
        var ageOptions = {
            chart: { type: 'line', height: 300 },
            series: [{ name: 'Practitioners', data: @json($ageGroups->pluck('count')) }],
            xaxis: { categories: @json($ageGroups->pluck('age_group')) },
            colors: ['#1cbb8c'],
        };
        var ageChart = new ApexCharts(document.querySelector("#ageChart"), ageOptions);
        ageChart.render();

        // Income Donut Chart
        var incomeOptions = {
            chart: { type: 'donut', height: 300 },
            series: @json($incomeLevels->pluck('count')),
            labels: @json($incomeLevels->pluck('income_level')),
            colors: ['#1cbb8c', '#ff3d60', '#fcb92c'],
        };
        var incomeChart = new ApexCharts(document.querySelector("#incomeChart"), incomeOptions);
        incomeChart.render();

        // DataTable Initialization
        $(document).ready(function() {
            $('#riceFarmersTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
            });
        });
    </script>
@endpush