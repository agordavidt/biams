@extends('layouts.governor')

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Practice Reports</h4>
                    <div class="page-title-right">
                        <!-- FIX: Change from super_admin.analytics to governor.analytics -->
                        <a href="{{ route('governor.analytics') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice Report Selection -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Generate Practice Report</h5>
                        <form method="GET" action="{{ route('governor.reports') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3 col-sm-6">
                                    <select name="practice" class="form-select" onchange="this.form.submit()">
                                        @foreach ($practiceOptions as $key => $label)
                                            <option value="{{ $key }}" {{ $practice === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <input type="text" name="filter" class="form-control" placeholder="e.g., Rice, Cattle" value="{{ $filter ?? '' }}">
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <select name="lga" class="form-select">
                                        <option value="">All LGAs</option>
                                        @foreach ($lgas as $lga)
                                            <option value="{{ $lga }}" {{ $lgaFilter === $lga ? 'selected' : '' }}>{{ $lga }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <select name="gender" class="form-select">
                                        <option value="">All Genders</option>
                                        <option value="Male" {{ $genderFilter === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $genderFilter === 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-1"></i> Generate
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Practice-Specific Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $practiceOptions[$practice] }} Distribution</h5>
                        <div id="practiceChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Practice Report Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $reportTitle }}</h5>
                        <table class="table table-hover datatable" id="practiceReportTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>LGA</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Key Metric</th>
                                    <th>Scale Metric</th>
                                    <th>Income Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reportData as $item)
                                    <tr>
                                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                                        <td>{{ $item->user->profile->lga ?? 'N/A' }}</td>
                                        <td>{{ $item->user->profile->gender ?? 'N/A' }}</td>
                                        <td>{{ $item->age ?? 'N/A' }}</td>
                                        <td>{{ $item->key_metric ?? 'N/A' }}</td>
                                        <td>{{ $item->scale_metric ?? 'N/A' }}</td>
                                        <td>{{ $item->user->profile->income_level ?? 'N/A' }}</td>
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
                <p>Generated on {{ now()->format('F d, Y, H:i') }} | Based on {{ $reportData->count() }} records</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Practice-Specific Bar Chart
        var practiceOptions = {
            chart: { type: 'bar', height: 300 },
            series: [{ name: '{{ $practiceOptions[$practice] }}', data: @json(array_values($chartData)) }],
            xaxis: { categories: @json(array_keys($chartData)), labels: { rotate: -45 } },
            colors: ['#1cbb8c'],
            responsive: [{ breakpoint: 480, options: { chart: { height: 250 } } }]
        };
        var practiceChart = new ApexCharts(document.querySelector("#practiceChart"), practiceOptions);
        practiceChart.render();

        // DataTable Initialization
        $(document).ready(function() {
            $('#practiceReportTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                pageLength: 10,
                order: [[0, 'asc']]
            });
        });
    </script>
@endpush