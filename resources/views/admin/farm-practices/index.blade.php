@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Farm Practice Analytics</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Farm Practices</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Key Statistics -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Farms</p>
                        <h4 class="mb-0">{{ number_format($stats['totalFarms']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">{{ number_format($stats['totalFarmers']) }} farmers</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-plant-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Land Size</p>
                        <h4 class="mb-0">{{ number_format($stats['totalLandSize'], 2) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">Hectares</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-landscape-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Avg Farm Size</p>
                        <h4 class="mb-0">{{ number_format($stats['avgFarmSize'], 2) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">Hectares per farm</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-dashboard-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Farms per Farmer</p>
                        <h4 class="mb-0">{{ number_format($stats['farmsPerFarmer'], 1) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">Average holdings</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-user-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farm Type Distribution -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line me-1"></i> Farm Type Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Farm Type</th>
                                <th class="text-center">Number of Farms</th>
                                <th class="text-end">Total Land (Ha)</th>
                                <th class="text-end">Avg Size (Ha)</th>
                                <th class="text-center">% of Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($farmTypeDistribution as $type)
                            <tr>
                                <td>
                                    @php
                                        $icons = [
                                            'crops' => 'ri-seedling-line text-success',
                                            'livestock' => 'ri-bear-smile-line text-warning',
                                            'fisheries' => 'ri-ship-line text-info',
                                            'orchards' => 'ri-plant-line text-primary'
                                        ];
                                    @endphp
                                    <i class="{{ $icons[$type->farm_type] ?? 'ri-plant-line' }} me-2"></i>
                                    <strong>{{ ucfirst($type->farm_type) }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-primary fs-6">
                                        {{ number_format($type->count) }}
                                    </span>
                                </td>
                                <td class="text-end">{{ number_format($type->total_hectares, 2) }}</td>
                                <td class="text-end">{{ number_format($type->avg_hectares, 2) }}</td>
                                <td class="text-center">
                                    <strong>{{ number_format(($type->count / $stats['totalFarms']) * 100, 1) }}%</strong>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.farm-practices.' . $type->farm_type) }}" 
                                       class="btn btn-sm btn-soft-info">
                                        <i class="ri-eye-line"></i> View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-center">{{ number_format($stats['totalFarms']) }}</th>
                                <th class="text-end">{{ number_format($stats['totalLandSize'], 2) }}</th>
                                <th class="text-end">{{ number_format($stats['avgFarmSize'], 2) }}</th>
                                <th class="text-center">100%</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Statistics</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <canvas id="farmTypeChart" height="250"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-soft-success rounded-circle">
                                    <i class="ri-seedling-line text-success"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-0">Crop Farms</p>
                            <h6 class="mb-0">{{ number_format($stats['cropFarms']) }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-soft-warning rounded-circle">
                                    <i class="ri-bear-smile-line text-warning"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-0">Livestock Farms</p>
                            <h6 class="mb-0">{{ number_format($stats['livestockFarms']) }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-soft-info rounded-circle">
                                    <i class="ri-ship-line text-info"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-0">Fisheries</p>
                            <h6 class="mb-0">{{ number_format($stats['fisheriesFarms']) }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-soft-primary rounded-circle">
                                    <i class="ri-plant-line text-primary"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-0">Orchards</p>
                            <h6 class="mb-0">{{ number_format($stats['orchardFarms']) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Performing Practices -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-soft-success">
                <h5 class="card-title mb-0">
                    <i class="ri-seedling-line me-1"></i> Top 5 Crops by Expected Yield
                </h5>
            </div>
            <div class="card-body">
                @if($practiceAnalytics['crops']['topCrops']->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Crop Type</th>
                                <th class="text-center">Farms</th>
                                <th class="text-end">Expected Yield (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($practiceAnalytics['crops']['topCrops'] as $crop)
                            <tr>
                                <td><strong>{{ ucfirst($crop->crop_type) }}</strong></td>
                                <td class="text-center">{{ number_format($crop->farm_count) }}</td>
                                <td class="text-end">{{ number_format($crop->total_expected_yield) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">No crop data available</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-soft-warning">
                <h5 class="card-title mb-0">
                    <i class="ri-bear-smile-line me-1"></i> Top 5 Livestock by Population
                </h5>
            </div>
            <div class="card-body">
                @if($practiceAnalytics['livestock']['topAnimals']->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Animal Type</th>
                                <th class="text-center">Farms</th>
                                <th class="text-end">Total Animals</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($practiceAnalytics['livestock']['topAnimals'] as $animal)
                            <tr>
                                <td><strong>{{ ucfirst($animal->animal_type) }}</strong></td>
                                <td class="text-center">{{ number_format($animal->farm_count) }}</td>
                                <td class="text-end">{{ number_format($animal->total_animals) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">No livestock data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-soft-info">
                <h5 class="card-title mb-0">
                    <i class="ri-ship-line me-1"></i> Top 5 Fish Species by Expected Harvest
                </h5>
            </div>
            <div class="card-body">
                @if($practiceAnalytics['fisheries']['topSpecies']->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Species</th>
                                <th class="text-center">Farms</th>
                                <th class="text-end">Expected Harvest (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($practiceAnalytics['fisheries']['topSpecies'] as $species)
                            <tr>
                                <td><strong>{{ ucfirst($species->species_raised) }}</strong></td>
                                <td class="text-center">{{ number_format($species->farm_count) }}</td>
                                <td class="text-end">{{ number_format($species->total_expected) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">No fisheries data available</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-soft-primary">
                <h5 class="card-title mb-0">
                    <i class="ri-plant-line me-1"></i> Top 5 Orchard Trees by Population
                </h5>
            </div>
            <div class="card-body">
                @if($practiceAnalytics['orchards']['topTrees']->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tree Type</th>
                                <th class="text-center">Farms</th>
                                <th class="text-end">Total Trees</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($practiceAnalytics['orchards']['topTrees'] as $tree)
                            <tr>
                                <td><strong>{{ ucfirst($tree->tree_type) }}</strong></td>
                                <td class="text-center">{{ number_format($tree->farm_count) }}</td>
                                <td class="text-end">{{ number_format($tree->total_trees) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">No orchard data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- LGA Distribution -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-map-pin-line me-1"></i> Farm Distribution by LGA
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>LGA Name</th>
                                <th class="text-center">Farmers</th>
                                <th class="text-center">Total Farms</th>
                                <th class="text-end">Land Size (Ha)</th>
                                <th class="text-center">Crops</th>
                                <th class="text-center">Livestock</th>
                                <th class="text-center">Fisheries</th>
                                <th class="text-center">Orchards</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lgaDistribution as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $item['lga']->name }}</strong>
                                    @if($item['lga']->code)
                                        <br><small class="text-muted">{{ $item['lga']->code }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-primary">
                                        {{ number_format($item['lga']->farmers_with_farms) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <strong>{{ number_format($item['total_farms']) }}</strong>
                                </td>
                                <td class="text-end">{{ number_format($item['total_land_size'], 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $item['crop_farms'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning">{{ $item['livestock_farms'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $item['fisheries_farms'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $item['orchard_farms'] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    No farm data available
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($lgaDistribution->isNotEmpty())
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2">Total</th>
                                <th class="text-center">{{ number_format($lgaDistribution->sum(fn($i) => $i['lga']->farmers_with_farms)) }}</th>
                                <th class="text-center">{{ number_format($lgaDistribution->sum('total_farms')) }}</th>
                                <th class="text-end">{{ number_format($lgaDistribution->sum('total_land_size'), 2) }}</th>
                                <th class="text-center">{{ number_format($lgaDistribution->sum('crop_farms')) }}</th>
                                <th class="text-center">{{ number_format($lgaDistribution->sum('livestock_farms')) }}</th>
                                <th class="text-center">{{ number_format($lgaDistribution->sum('fisheries_farms')) }}</th>
                                <th class="text-center">{{ number_format($lgaDistribution->sum('orchard_farms')) }}</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Farm Type Distribution Chart
const ctx = document.getElementById('farmTypeChart');
if (ctx) {
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                'Crops ({{ $stats["cropFarms"] }})',
                'Livestock ({{ $stats["livestockFarms"] }})',
                'Fisheries ({{ $stats["fisheriesFarms"] }})',
                'Orchards ({{ $stats["orchardFarms"] }})'
            ],
            datasets: [{
                data: [
                    {{ $stats['cropFarms'] }},
                    {{ $stats['livestockFarms'] }},
                    {{ $stats['fisheriesFarms'] }},
                    {{ $stats['orchardFarms'] }}
                ],
                backgroundColor: [
                    'rgba(52, 195, 143, 0.8)',
                    'rgba(244, 184, 59, 0.8)',
                    'rgba(80, 165, 241, 0.8)',
                    'rgba(85, 110, 230, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + percentage + '%';
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush
@endsection