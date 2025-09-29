@extends('layouts.admin')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row">
    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Users</p>
                        <h4 class="mb-2">{{ number_format($stats['totalUsers']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-success fw-bold font-size-12 me-2">
                                <i class="ri-arrow-right-up-line me-1 align-middle"></i>New today: {{ $stats['newToday'] }}
                            </span>
                        </p>
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

    <!-- Pending Approvals Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Pending Approvals</p>
                        <h4 class="mb-2">{{ number_format($stats['pendingUsers']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-warning fw-bold font-size-12 me-2">
                                <i class="ri-time-line me-1 align-middle"></i>Requires attention
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-user-follow-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Completion Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Profile Completion</p>
                        <h4 class="mb-2">{{ $stats['profileCompletion']['percentage'] }}%</h4>
                        <p class="text-muted mb-0">
                            <span class="text-info fw-bold font-size-12 me-2">
                                <i class="ri-profile-line me-1 align-middle"></i>{{ number_format($stats['profileCompletion']['completed']) }} complete
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-info rounded-3">
                            <i class="ri-profile-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 24h Activity Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">24h Activity</p>
                        <h4 class="mb-2">{{ number_format($stats['recentActivity']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-success fw-bold font-size-12 me-2">
                                <i class="ri-pulse-line me-1 align-middle"></i>Verified users
                            </span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-pulse-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Monthly Trends Chart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Monthly Registration Trends</h4>
                <div id="monthly-trends" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <!-- Practice Distribution Chart -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Practice Distribution</h4>
                <div id="practice-distribution" style="height: 300px;"></div>
                <div class="text-center mt-4">
                    <div class="row">
                        @foreach($practiceDistribution as $practice => $count)
                        <div class="col-6">
                            <h5 class="mb-2">{{ number_format($count) }}</h5>
                            <p class="text-muted text-truncate">{{ $practice }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Users</h4>
                <div class="table-responsive">
                    <table id="recent-users" class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>LGA</th>                                
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Age</th> 
                                <th>Status</th>                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                            <tr>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>{{ $user['lga'] }}</td>                                
                                <td>{{ $user['phone'] }}</td>
                                <td>{{ $user['gender'] }}</td>
                                <td>{{ $user['age'] }}</td>
                                <td>
                                    <span class="badge bg-{{ $user['status_color'] }}">
                                        {{ $user['status'] }}
                                    </span>
                                </td>                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#recent-users').DataTable({
            pageLength: 10,
            ordering: true,
            responsive: true,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        // Monthly Trends Chart
        var monthlyOptions = {
            series: [{
                name: 'Total Registrations',
                data: @json($monthlyStats->pluck('total'))
            }, {
                name: 'Active Users',
                data: @json($monthlyStats->pluck('onboarded'))
            }],
            chart: {
                type: 'bar',
                height: 300,
                stacked: true,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%'
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: @json($monthlyStats->pluck('month'))
            },
            colors: ['#3b82f6', '#10b981'],
            legend: {
                position: 'top'
            },
            fill: {
                opacity: 1
            }
        };

        var monthlyChart = new ApexCharts(
            document.querySelector("#monthly-trends"), 
            monthlyOptions
        );
        monthlyChart.render();

        // Practice Distribution Chart
        var practiceOptions = {
            series: @json(array_values($practiceDistribution)),
            chart: {
                type: 'donut',
                height: 300
            },
            labels: @json(array_keys($practiceDistribution)),
            colors: ['#3b82f6', '#10b981', '#6366f1', '#8b5cf6'],
            legend: {
                position: 'bottom'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var practiceChart = new ApexCharts(
            document.querySelector("#practice-distribution"), 
            practiceOptions
        );
        practiceChart.render();
    });

</script>
@endpush


