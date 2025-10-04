@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Crop Farming Analytics</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.farm-practices.index') }}">Farm Practices</a></li>
                    <li class="breadcrumb-item active">Crops</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Crop Farms</p>
                        <h4 class="mb-0">{{ number_format($cropStats['totalCropFarms']) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-seedling-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Expected Total Yield</p>
                        <h4 class="mb-0">{{ number_format($cropStats['totalExpectedYield']) }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Kilograms</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-bar-chart-box-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Avg Yield per Farm</p>
                        <h4 class="mb-0">{{ number_format($cropStats['avgYieldPerFarm'], 2) }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Kilograms</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-line-chart-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Crop Types</p>
                        <h4 class="mb-0">{{ $cropStats['cropTypes']->count() }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Varieties cultivated</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-plant-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Crop Distribution & Farming Methods -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-soft-success">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line me-1"></i> Crop Type Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <canvas id="cropTypeChart" height="300"></canvas>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            @foreach($cropStats['cropTypes'] as $crop)
                            <tr>
                                <td style="width: 60%">
                                    <strong>{{ ucfirst($crop->crop_type) }}</strong>
                                </td>
                                <td class="text-end" style="width: 40%">
                                    <span class="badge badge-soft-success">{{ number_format($crop->count) }} farms</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-soft-primary">
                <h5 class="card-title mb-0">
                    <i class="ri-tools-line me-1"></i> Farming Methods
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <canvas id="farmingMethodChart" height="300"></canvas>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            @foreach($cropStats['farmingMethods'] as $method)
                            <tr>
                                <td style="width: 60%">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $method->farming_method)) }}</strong>
                                </td>
                                <td class="text-end" style="width: 40%">
                                    <span class="badge badge-soft-primary">{{ number_format($method->count) }} farms</span>
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

<!-- Top Crop Varieties -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-star-line me-1"></i> Top 10 Crop Varieties
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Crop Type</th>
                                <th>Variety</th>
                                <th class="text-center">Number of Farms</th>
                                <th class="text-center">Popularity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cropStats['topVarieties'] as $variety)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ ucfirst($variety->crop_type) }}</strong></td>
                                <td>{{ ucfirst($variety->variety) }}</td>
                                <td class="text-center">
                                    <span class="badge badge-soft-success">{{ number_format($variety->count) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: {{ ($variety->count / $cropStats['topVarieties']->max('count')) * 100 }}%"
                                             aria-valuenow="{{ $variety->count }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="{{ $cropStats['topVarieties']->max('count') }}">
                                            {{ number_format(($variety->count / $cropStats['totalCropFarms']) * 100, 1) }}%
                                        </div>
                                    </div>
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

<!-- Filter and Search -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row g-3">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0">Crop Farm Details</h5>
                    </div>
                    <div class="col-md-8">
                        <form method="GET" class="row g-2">
                            <div class="col-md-4">
                                <select name="crop_type" class="form-select">
                                    <option value="">All Crops</option>
                                    @foreach($cropStats['cropTypes'] as $crop)
                                        <option value="{{ $crop->crop_type }}" {{ request('crop_type') == $crop->crop_type ? 'selected' : '' }}>
                                            {{ ucfirst($crop->crop_type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="farming_method" class="form-select">
                                    <option value="">All Methods</option>
                                    @foreach($cropStats['farmingMethods'] as $method)
                                        <option value="{{ $method->farming_method }}" {{ request('farming_method') == $method->farming_method ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $method->farming_method)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="lga_id" class="form-select">
                                    <option value="">All LGAs</option>
                                    @foreach($lgas as $lga)
                                        <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>
                                            {{ $lga->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-line"></i> Filter
                                </button>
                            </div>
                            @if(request()->hasAny(['crop_type', 'farming_method', 'lga_id']))
                            <div class="col-md-12">
                                <a href="{{ route('admin.farm-practices.crops') }}" class="btn btn-sm btn-soft-secondary">
                                    <i class="ri-close-line"></i> Clear Filters
                                </a>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Farm Name</th>
                                <th>Farmer</th>
                                <th>LGA</th>
                                <th>Crop Type</th>
                                <th>Variety</th>
                                <th class="text-end">Farm Size (Ha)</th>
                                <th class="text-end">Expected Yield (kg)</th>
                                <th>Farming Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($crops as $crop)
                            <tr>
                                <td>{{ $loop->iteration + ($crops->currentPage() - 1) * $crops->perPage() }}</td>
                                <td>
                                    <strong>{{ $crop->farmLand->name ?? 'Unnamed Farm' }}</strong>
                                </td>
                                <td>
                                    {{ $crop->farmLand->farmer->full_name ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $crop->farmLand->farmer->phone_primary ?? '' }}</small>
                                </td>
                                <td>{{ $crop->farmLand->farmer->lga->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-soft-success">{{ ucfirst($crop->crop_type) }}</span>
                                </td>
                                <td>{{ ucfirst($crop->variety) }}</td>
                                <td class="text-end">{{ number_format($crop->farmLand->total_size_hectares, 2) }}</td>
                                <td class="text-end">
                                    <strong>{{ number_format($crop->expected_yield_kg) }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-soft-info">
                                        {{ ucfirst(str_replace('_', ' ', $crop->farming_method)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="ri-plant-line fs-1 d-block mb-2"></i>
                                    <p class="mb-0">No crop farms found</p>
                                    @if(request()->hasAny(['crop_type', 'farming_method', 'lga_id']))
                                        <a href="{{ route('admin.farm-practices.crops') }}" class="btn btn-sm btn-link">Clear filters</a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($crops->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $crops->firstItem() }} to {{ $crops->lastItem() }} of {{ $crops->total() }} entries
                    </div>
                    <div>
                        {{ $crops->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Crop Type Distribution Chart
const cropTypeCtx = document.getElementById('cropTypeChart');
if (cropTypeCtx) {
    const cropData = @json($cropStats['cropTypes']);
    new Chart(cropTypeCtx, {
        type: 'doughnut',
        data: {
            labels: cropData.map(c => c.crop_type.charAt(0).toUpperCase() + c.crop_type.slice(1)),
            datasets: [{
                data: cropData.map(c => c.count),
                backgroundColor: [
                    'rgba(52, 195, 143, 0.8)',
                    'rgba(85, 110, 230, 0.8)',
                    'rgba(244, 184, 59, 0.8)',
                    'rgba(80, 165, 241, 0.8)',
                    'rgba(244, 106, 106, 0.8)',
                    'rgba(116, 120, 141, 0.8)'
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
                        padding: 10,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
}

// Farming Method Chart
const methodCtx = document.getElementById('farmingMethodChart');
if (methodCtx) {
    const methodData = @json($cropStats['farmingMethods']);
    new Chart(methodCtx, {
        type: 'bar',
        data: {
            labels: methodData.map(m => m.farming_method.replace(/_/g, ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')),
            datasets: [{
                label: 'Number of Farms',
                data: methodData.map(m => m.count),
                backgroundColor: 'rgba(85, 110, 230, 0.8)',
                borderColor: 'rgba(85, 110, 230, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
}
</script>
@endpush
@endsection