@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Livestock Farming Analytics</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.farm-practices.index') }}">Farm Practices</a></li>
                    <li class="breadcrumb-item active">Livestock</li>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Avg Herd Size</p>
                        <h4 class="mb-0">{{ number_format($livestockStats['avgHerdSize'], 0) }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Animals per farm</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-bar-chart-grouped-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Animal Types</p>
                        <h4 class="mb-0">{{ $livestockStats['animalTypes']->count() }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Species farmed</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-list-check text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Animal Type Distribution -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-soft-warning">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-line me-1"></i> Livestock Distribution by Animal Type
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Animal Type</th>
                                <th class="text-center">Number of Farms</th>
                                <th class="text-end">Total Animals</th>
                                <th class="text-end">Avg per Farm</th>
                                <th class="text-center">% of Farms</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($livestockStats['animalTypes'] as $animal)
                            <tr>
                                <td>
                                    <strong>{{ ucfirst($animal->animal_type) }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-warning fs-6">{{ number_format($animal->farm_count) }}</span>
                                </td>
                                <td class="text-end">
                                    <strong>{{ number_format($animal->total_animals) }}</strong>
                                </td>
                                <td class="text-end">
                                    {{ number_format($animal->total_animals / $animal->farm_count, 0) }}
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" 
                                             role="progressbar" 
                                             style="width: {{ ($animal->farm_count / $livestockStats['totalLivestockFarms']) * 100 }}%">
                                            {{ number_format(($animal->farm_count / $livestockStats['totalLivestockFarms']) * 100, 1) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-center">{{ number_format($livestockStats['totalLivestockFarms']) }}</th>
                                <th class="text-end">{{ number_format($livestockStats['totalAnimals']) }}</th>
                                <th class="text-end">{{ number_format($livestockStats['avgHerdSize'], 0) }}</th>
                                <th class="text-center">100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-soft-primary">
                <h5 class="card-title mb-0">
                    <i class="ri-pie-chart-2-line me-1"></i> Population Distribution
                </h5>
            </div>
            <div class="card-body">
                <canvas id="animalPopulationChart" height="300"></canvas>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-soft-success">
                <h5 class="card-title mb-0">
                    <i class="ri-tools-line me-1"></i> Breeding Practices
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            @foreach($livestockStats['breedingPractices'] as $practice)
                            <tr>
                                <td>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $practice->breeding_practice)) }}</strong>
                                </td>
                                <td class="text-end">
                                    <span class="badge badge-soft-success">{{ number_format($practice->count) }}</span>
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
                        <h5 class="card-title mb-0">Livestock Farm Details</h5>
                    </div>
                    <div class="col-md-8">
                        <form method="GET" class="row g-2">
                            <div class="col-md-5">
                                <select name="animal_type" class="form-select">
                                    <option value="">All Animal Types</option>
                                    @foreach($livestockStats['animalTypes'] as $animal)
                                        <option value="{{ $animal->animal_type }}" {{ request('animal_type') == $animal->animal_type ? 'selected' : '' }}>
                                            {{ ucfirst($animal->animal_type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="lga_id" class="form-select">
                                    <option value="">All LGAs</option>
                                    @foreach($lgas as $lga)
                                        <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>
                                            {{ $lga->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-filter-line"></i> Filter
                                </button>
                            </div>
                            @if(request()->hasAny(['animal_type', 'lga_id']))
                            <div class="col-md-12">
                                <a href="{{ route('admin.farm-practices.livestock') }}" class="btn btn-sm btn-soft-secondary">
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
                                <th>Animal Type</th>
                                <th class="text-center">Herd/Flock Size</th>
                                <th class="text-end">Farm Size (Ha)</th>
                                <th>Breeding Practice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($livestock as $animal)
                            <tr>
                                <td>{{ $loop->iteration + ($livestock->currentPage() - 1) * $livestock->perPage() }}</td>
                                <td>
                                    <strong>{{ $animal->farmLand->name ?? 'Unnamed Farm' }}</strong>
                                </td>
                                <td>
                                    {{ $animal->farmLand->farmer->full_name ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $animal->farmLand->farmer->phone_primary ?? '' }}</small>
                                </td>
                                <td>{{ $animal->farmLand->farmer->lga->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-soft-warning">{{ ucfirst($animal->animal_type) }}</span>
                                </td>
                                <td class="text-center">
                                    <strong class="text-primary">{{ number_format($animal->herd_flock_size) }}</strong>
                                </td>
                                <td class="text-end">{{ number_format($animal->farmLand->total_size_hectares, 2) }}</td>
                                <td>
                                    <span class="badge badge-soft-info">
                                        {{ ucfirst(str_replace('_', ' ', $animal->breeding_practice)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="ri-bear-smile-line fs-1 d-block mb-2"></i>
                                    <p class="mb-0">No livestock farms found</p>
                                    @if(request()->hasAny(['animal_type', 'lga_id']))
                                        <a href="{{ route('admin.farm-practices.livestock') }}" class="btn btn-sm btn-link">Clear filters</a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($livestock->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $livestock->firstItem() }} to {{ $livestock->lastItem() }} of {{ $livestock->total() }} entries
                    </div>
                    <div>
                        {{ $livestock->links() }}
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
// Animal Population Chart
const animalCtx = document.getElementById('animalPopulationChart');
if (animalCtx) {
    const animalData = @json($livestockStats['animalTypes']);
    new Chart(animalCtx, {
        type: 'pie',
        data: {
            labels: animalData.map(a => a.animal_type.charAt(0).toUpperCase() + a.animal_type.slice(1)),
            datasets: [{
                data: animalData.map(a => a.total_animals),
                backgroundColor: [
                    'rgba(244, 184, 59, 0.8)',
                    'rgba(85, 110, 230, 0.8)',
                    'rgba(52, 195, 143, 0.8)',
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}
</script>
@endpush
@endsection class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Livestock Farms</p>
                        <h4 class="mb-0">{{ number_format($livestockStats['totalLivestockFarms']) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-bear-smile-line text-warning"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Animals</p>
                        <h4 class="mb-0">{{ number_format($livestockStats['totalAnimals']) }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>All species combined</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-stack-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div