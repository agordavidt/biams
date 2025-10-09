@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Crop Production Analytics</h2>
            <p class="text-muted">Crop-specific yields, methods, and production statistics</p>
        </div>
    </div>

    @if(!empty($data))
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Crop Production Overview</h5>
                        <button class="btn btn-sm btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Crop Type</th>
                                        <th>Farmers</th>
                                        <th>Farms</th>
                                        <th>Area (Ha)</th>
                                        <th>Expected Yield (Kg)</th>
                                        <th>Yield/Ha</th>
                                        <th>Irrigation</th>
                                        <th>Rain-fed</th>
                                        <th>Organic</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $crop)
                                    <tr>
                                        <td><strong>{{ ucwords(str_replace('_', ' ', $crop->crop_type)) }}</strong></td>
                                        <td>{{ number_format($crop->farmer_count) }}</td>
                                        <td>{{ number_format($crop->farm_count) }}</td>
                                        <td>{{ number_format($crop->total_area_ha, 2) }}</td>
                                        <td>{{ number_format($crop->total_expected_yield_kg, 2) }}</td>
                                        <td>{{ number_format($crop->avg_yield_per_ha, 2) }}</td>
                                        <td>{{ number_format($crop->method_irrigation) }}</td>
                                        <td>{{ number_format($crop->method_rain_fed) }}</td>
                                        <td>{{ number_format($crop->method_organic) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($topCrops))
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Top Crops by Area</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="topCropsArea"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Top Crops by Expected Yield</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="topCropsYield"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            const cropLabels = {!! json_encode(array_map(function($c) { return ucwords(str_replace('_', ' ', $c->crop_type)); }, array_slice($topCrops, 0, 10))) !!};
            const cropAreas = {!! json_encode(array_map(function($c) { return $c->total_area_ha; }, array_slice($topCrops, 0, 10))) !!};
            const cropYields = {!! json_encode(array_map(function($c) { return $c->total_expected_yield_kg; }, array_slice($topCrops, 0, 10))) !!};

            new Chart(document.getElementById('topCropsArea'), {
                type: 'bar',
                data: {
                    labels: cropLabels,
                    datasets: [{
                        label: 'Area (Hectares)',
                        data: cropAreas,
                        backgroundColor: '#10b981'
                    }]
                },
                options: { maintainAspectRatio: false, indexAxis: 'y', scales: { x: { beginAtZero: true } } }
            });

            new Chart(document.getElementById('topCropsYield'), {
                type: 'bar',
                data: {
                    labels: cropLabels,
                    datasets: [{
                        label: 'Expected Yield (Kg)',
                        data: cropYields,
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: { maintainAspectRatio: false, indexAxis: 'y', scales: { x: { beginAtZero: true } } }
            });
        </script>
        @endpush
        @endif
    @else
        <div class="alert alert-info">
            No crop data available. Please run <code>php artisan analytics:generate</code>
        </div>
    @endif
</div>
@endsection


