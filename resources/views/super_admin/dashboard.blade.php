@extends('layouts.super_admin')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Super Admin Dashboard</h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Total Users -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Total Users</p>
                                <h4 class="mb-2">{{ $totalUsers }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <i class="ri-user-3-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Male Users -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Male Users</p>
                                <h4 class="mb-2">{{ $maleUsers }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <i class="ri-user-3-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Female Users -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Female Users</p>
                                <h4 class="mb-2">{{ $femaleUsers }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-warning rounded-3">
                                    <i class="ri-user-3-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Users -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Pending Users</p>
                                <h4 class="mb-2">{{ $pendingUsers }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-danger rounded-3">
                                    <i class="ri-user-3-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login Security -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Suspicious Logins</p>
                                <h4 class="mb-2">{{ $recentSuspiciousLogins }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-danger rounded-3">
                                    <i class="ri-shield-alert-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Failed Login Attempts -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate font-size-14 mb-2">Failed Logins (7d)</p>
                                <h4 class="mb-2">{{ $loginStats['failed_attempts'] }}</h4>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-warning rounded-3">
                                    <i class="ri-login-circle-line font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Statistics Cards -->

        <!-- Charts -->
        <div class="row">
            <!-- Registration Trends -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Registration Trends</h4>
                        <div id="registration-trends-chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>

            <!-- User Distribution by LGA -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">User Distribution by LGA</h4>
                        <div id="lga-distribution-chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>

            <!-- Login Security Overview -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Login Security (7 days)</h4>
                        <div id="login-security-chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Charts -->

        <!-- Recent Users -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Recent Users</h4>
                        <div class="table-responsive">
                            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>LGA</th>
                                        <th>Gender</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ ucfirst($user->role) }}</td>
                                            <td>{{ ucfirst($user->status) }}</td>
                                            <td>{{ $user->profile->lga ?? 'N/A' }}</td>
                                            <td>{{ $user->profile->gender ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Recent Users -->
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Registration Trends Chart
    var registrationTrendsOptions = {
        chart: { type: 'line', height: 350 },
        series: [{ name: 'Registrations', data: @json($registrationTrends) }],
        xaxis: { categories: @json($registrationMonths) }
    };
    var registrationTrendsChart = new ApexCharts(document.querySelector("#registration-trends-chart"), registrationTrendsOptions);
    registrationTrendsChart.render();

    // LGA Distribution Chart
    var lgaDistributionOptions = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'Users', data: @json($lgaDistribution) }],
        xaxis: { categories: @json($lgaCategories) }
    };
    var lgaDistributionChart = new ApexCharts(document.querySelector("#lga-distribution-chart"), lgaDistributionOptions);
    lgaDistributionChart.render();

    // Login Security Chart
    var loginSecurityOptions = {
        chart: { type: 'donut', height: 350 },
        series: [{{ $loginStats['successful_logins'] }}, {{ $loginStats['failed_attempts'] }}, {{ $loginStats['suspicious_attempts'] }}],
        labels: ['Successful', 'Failed', 'Suspicious'],
        colors: ['#28a745', '#ffc107', '#dc3545'],
        legend: { position: 'bottom' }
    };
    var loginSecurityChart = new ApexCharts(document.querySelector("#login-security-chart"), loginSecurityOptions);
    loginSecurityChart.render();
</script>
@endsection