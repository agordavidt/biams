@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Production Analytics</h2>
            <p class="text-muted">Farm types, land usage, and ownership statistics</p>
        </div>
    </div>

    @if(!empty($data))
        @foreach($data as $snapshot)
        <div class="row mb-4">
            {{-- Summary Cards --}}
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Farms</div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ number_format($snapshot->farms_crops + $snapshot->farms_livestock + $snapshot->farms_fisheries + $snapshot->farms_orchards + $snapshot->farms_forestry) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Land (Ha)</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($snapshot->total_land_ha, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Avg Farm Size</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($snapshot->avg_farm_size_ha, 2) }} Ha</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cropland</div>
                        <div class="h5 mb-0 font-weight-bold">{{ number_format($snapshot->total_cropland_ha, 2) }} Ha</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Farm Types --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Farm Type Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="farmTypeChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            {{-- Ownership Status --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Land Ownership</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ownershipChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Land Area by Farm Type</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Farm Type</th>
                                    <th>Number of Farms</th>
                                    <th>Total Area (Ha)</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Crops</td>
                                    <td>{{ number_format($snapshot->farms_crops) }}</td>
                                    <td>{{ number_format($snapshot->total_cropland_ha, 2) }}</td>
                                    <td>{{ $snapshot->total_land_ha > 0 ? number_format(($snapshot->total_cropland_ha / $snapshot->total_land_ha) * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td>Livestock</td>
                                    <td>{{ number_format($snapshot->farms_livestock) }}</td>
                                    <td>{{ number_format($snapshot->total_livestock_land_ha, 2) }}</td>
                                    <td>{{ $snapshot->total_land_ha > 0 ? number_format(($snapshot->total_livestock_land_ha / $snapshot->total_land_ha) * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td>Fisheries</td>
                                    <td>{{ number_format($snapshot->farms_fisheries) }}</td>
                                    <td>{{ number_format($snapshot->total_fisheries_area_ha, 2) }}</td>
                                    <td>{{ $snapshot->total_land_ha > 0 ? number_format(($snapshot->total_fisheries_area_ha / $snapshot->total_land_ha) * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td>Orchards</td>
                                    <td>{{ number_format($snapshot->farms_orchards) }}</td>
                                    <td>{{ number_format($snapshot->total_orchard_land_ha, 2) }}</td>
                                    <td>{{ $snapshot->total_land_ha > 0 ? number_format(($snapshot->total_orchard_land_ha / $snapshot->total_land_ha) * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr>
                                    <td>Forestry</td>
                                    <td>{{ number_format($snapshot->farms_forestry) }}</td>
                                    <td>{{ number_format($snapshot->total_forestry_land_ha, 2) }}</td>
                                    <td>{{ $snapshot->total_land_ha > 0 ? number_format(($snapshot->total_forestry_land_ha / $snapshot->total_land_ha) * 100, 1) : 0 }}%</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{ number_format($snapshot->farms_crops + $snapshot->farms_livestock + $snapshot->farms_fisheries + $snapshot->farms_orchards + $snapshot->farms_forestry) }}</strong></td>
                                    <td><strong>{{ number_format($snapshot->total_land_ha, 2) }}</strong></td>
                                    <td><strong>100%</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            new Chart(document.getElementById('farmTypeChart'), {
                type: 'bar',
                data: {
                    labels: ['Crops', 'Livestock', 'Fisheries', 'Orchards', 'Forestry'],
                    datasets: [{
                        label: 'Number of Farms',
                        data: [
                            {{ $snapshot->farms_crops }},
                            {{ $snapshot->farms_livestock }},
                            {{ $snapshot->farms_fisheries }},
                            {{ $snapshot->farms_orchards }},
                            {{ $snapshot->farms_forestry }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ec4899', '#8b5cf6']
                    }]
                },
                options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });

            new Chart(document.getElementById('ownershipChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Owned', 'Leased', 'Shared', 'Communal'],
                    datasets: [{
                        data: [
                            {{ $snapshot->ownership_owned }},
                            {{ $snapshot->ownership_leased }},
                            {{ $snapshot->ownership_shared }},
                            {{ $snapshot->ownership_communal }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#8b5cf6']
                    }]
                },
                options: { maintainAspectRatio: false }
            });
        </script>
        @endpush
        @endforeach
    @else
        <div class="alert alert-info">
            No production data available. Please run <code>php artisan analytics:generate</code>
        </div>
    @endif
</div>
@endsection

