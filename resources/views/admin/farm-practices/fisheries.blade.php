@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Fisheries & Aquaculture Analytics</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.farm-practices.index') }}">Farm Practices</a></li>
                    <li class="breadcrumb-item active">Fisheries</li>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Fish Farms</p>
                        <h4 class="mb-0">{{ number_format($fisheriesStats['totalFishFarms']) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-ship-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Pond Size</p>
                        <h4 class="mb-0">{{ number_format($fisheriesStats['totalPondSize'], 2) }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Square meters</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-water-flash-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Expected Harvest</p>
                        <h4 class="mb-0">{{ number_format($fisheriesStats['totalExpectedHarvest']) }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Kilograms</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-line-chart-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Avg Pond Size</p>
                        <h4 class="mb-0">{{ number_format($fisheriesStats['avgPondSize'], 2) }}</h4>
                        <p class="text-muted mb-0 mt-1"><small>Sq. meters</small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-dashboard-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Species Distribution -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-soft-info">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> Fish Species Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Species</th>
                                <th class="text-center">Number of Farms</th>
                                <th class="text-end">Total Expected Harvest (kg)</th>
                                <th class="text-end">Avg Harvest per Farm (kg)</th>
                                <th class="text-center">% of Total Production</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fisheriesStats['speciesRaised'] as $species)
                            <tr>
                                <td><strong>{{ ucfirst($species->species_raised) }}</strong></td>
                                <td class="text-center">
                                    <span class="badge badge-soft-info fs-6">{{ number_format($species->farm_count) }}</span>
                                </td>
                                <td class="text-end"><strong>{{ number_format($species->total_expected) }}</strong></td>
                                <td class="text-end">{{ number_format($species->total_expected / $species->farm_count, 2) }}</td>
                                <td class="text-center">
                                    {{ number_format(($species->total_expected / $fisheriesStats['totalExpectedHarvest']) * 100, 1) }}%
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
                        <h5 class="card-title mb-0">Fish Farm Details</h5>
                    </div>
                    <div class="col-md-8">
                        <form method="GET" class="row g-2">
                            <div class="col-md-5">
                                <select name="fishing_type" class="form-select">
                                    <option value="">All Fishing Types</option>
                                    @foreach($fisheriesStats['fishingTypes'] as $type)
                                        <option value="{{ $type->fishing_type }}" {{ request('fishing_type') == $type->fishing_type ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $type->fishing_type)) }}
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
                                <th>Fishing Type</th>
                                <th>Species</th>
                                <th class="text-end">Pond Size (sqm)</th>
                                <th class="text-end">Expected Harvest (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fisheries as $fish)
                            <tr>
                                <td>{{ $loop->iteration + ($fisheries->currentPage() - 1) * $fisheries->perPage() }}</td>
                                <td><strong>{{ $fish->farmLand->name ?? 'Unnamed' }}</strong></td>
                                <td>{{ $fish->farmLand->farmer->full_name ?? 'N/A' }}</td>
                                <td>{{ $fish->farmLand->farmer->lga->name ?? 'N/A' }}</td>
                                <td><span class="badge badge-soft-info">{{ ucfirst(str_replace('_', ' ', $fish->fishing_type)) }}</span></td>
                                <td><span class="badge badge-soft-primary">{{ ucfirst($fish->species_raised) }}</span></td>
                                <td class="text-end">{{ number_format($fish->pond_size_sqm, 2) }}</td>
                                <td class="text-end"><strong>{{ number_format($fish->expected_harvest_kg) }}</strong></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="ri-ship-line fs-1 d-block mb-2"></i>
                                    <p class="mb-0">No fisheries data found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($fisheries->hasPages())
                <div class="mt-3">
                    {{ $fisheries->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection