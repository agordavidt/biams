@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Orchard Farming Analytics</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.farm-practices.index') }}">Farm Practices</a></li>
                    <li class="breadcrumb-item active">Orchards</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Orchards</p>
                        <h4 class="mb-0">{{ number_format($orchardStats['totalOrchards']) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-plant-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Trees</p>
                        <h4 class="mb-0">{{ number_format($orchardStats['totalTrees']) }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>All species</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-leaf-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Avg Trees per Orchard</p>
                        <h4 class="mb-0">{{ number_format($orchardStats['avgTreesPerOrchard'], 0) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-bar-chart-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Tree Species</p>
                        <h4 class="mb-0">{{ $orchardStats['treeTypes']->count() }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Types cultivated</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-list-check text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tree Type & Maturity Distribution -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-soft-primary">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> Tree Type Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tree Type</th>
                                <th class="text-center">Number of Orchards</th>
                                <th class="text-end">Total Trees</th>
                                <th class="text-end">Avg Trees per Orchard</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orchardStats['treeTypes'] as $tree)
                            <tr>
                                <td><strong>{{ ucfirst($tree->tree_type) }}</strong></td>
                                <td class="text-center">
                                    <span class="badge badge-soft-primary fs-6">{{ number_format($tree->farm_count) }}</span>
                                </td>
                                <td class="text-end"><strong>{{ number_format($tree->total_trees) }}</strong></td>
                                <td class="text-end">{{ number_format($tree->total_trees / $tree->farm_count, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-center">{{ number_format($orchardStats['totalOrchards']) }}</th>
                                <th class="text-end">{{ number_format($orchardStats['totalTrees']) }}</th>
                                <th class="text-end">{{ number_format($orchardStats['avgTreesPerOrchard'], 0) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-soft-success">
                <h5 class="card-title mb-0">
                    <i class="ri-timer-line me-1"></i> Maturity Stage
                </h5>
            </div>
            <div class="card-body">
                <canvas id="maturityChart" height="300"></canvas>
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            @foreach($orchardStats['maturityDistribution'] as $maturity)
                            <tr>
                                <td><strong>{{ ucfirst(str_replace('_', ' ', $maturity->maturity_stage)) }}</strong></td>
                                <td class="text-end">
                                    <span class="badge badge-soft-success">{{ number_format($maturity->count) }}</span>
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

<!-- Farm Details with Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row g-3">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0">Orchard Details</h5>
                    </div>
                    <div class="col-md-8">
                        <form method="GET" class="row g-2">
                            <div class="col-md-4">
                                <select name="tree_type" class="form-select">
                                    <option value="">All Tree Types</option>
                                    @foreach($orchardStats['treeTypes'] as $tree)
                                        <option value="{{ $tree->tree_type }}" {{ request('tree_type') == $tree->tree_type ? 'selected' : '' }}>
                                            {{ ucfirst($tree->tree_type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="maturity_stage" class="form-select">
                                    <option value="">All Stages</option>
                                    @foreach($orchardStats['maturityDistribution'] as $stage)
                                        <option value="{{ $stage->maturity_stage }}" {{ request('maturity_stage') == $stage->maturity_stage ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $stage->maturity_stage)) }}
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
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Farm Name</th>
                                <th>Farmer</th>
                                <th>LGA</th>
                                <th>Tree Type</th>
                                <th class="text-center">Number of Trees</th>
                                <th class="text-end">Farm Size (Ha)</th>
                                <th>Maturity Stage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orchards as $orchard)
                            <tr>
                                <td>{{ $loop->iteration + ($orchards->currentPage() - 1) * $orchards->perPage() }}</td>
                                <td><strong>{{ $orchard->farmLand->name ?? 'Unnamed' }}</strong></td>
                                <td>{{ $orchard->farmLand->farmer->full_name ?? 'N/A' }}</td>
                                <td>{{ $orchard->farmLand->farmer->lga->name ?? 'N/A' }}</td>
                                <td><span class="badge badge-soft-primary">{{ ucfirst($orchard->tree_type) }}</span></td>
                                <td class="text-center"><strong>{{ number_format($orchard->number_of_trees) }}</strong></td>
                                <td class="text-end">{{ number_format($orchard->farmLand->total_size_hectares, 2) }}</td>
                                <td>
                                    <span class="badge badge-soft-success">
                                        {{ ucfirst(str_replace('_', ' ', $orchard->maturity_stage)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="ri-plant-line fs-1 d-block mb-2"></i>
                                    <p class="mb-0">No orchard data found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orchards->hasPages())
                <div class="mt-3">
                    {{ $orchards->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const maturityCtx = document.getElementById('maturityChart');
if (maturityCtx) {
    const maturityData = @json($orchardStats['maturityDistribution']);
    new Chart(maturityCtx, {
        type: 'doughnut',
        data: {
            labels: maturityData.map(m => m.maturity_stage.replace(/_/g, ' ').split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')),
            datasets: [{
                data: maturityData.map(m => m.count),
                backgroundColor: [
                    'rgba(52, 195, 143, 0.8)',
                    'rgba(244, 184, 59, 0.8)',
                    'rgba(85, 110, 230, 0.8)',
                    'rgba(80, 165, 241, 0.8)'
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
</script>
@endpush
@endsection