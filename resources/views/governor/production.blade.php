@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Production Analytics</h4>
            <div class="page-title-right">
                <a href="{{ route('governor.production.export') }}" class="btn btn-primary">Download Report</a>
            </div>
        </div>
    </div>
</div>

<!-- Farms Overview -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Total Farms</p>
                <h3 class="mb-0">{{ number_format($farms_overview['total_farms']) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Total Hectares</p>
                <h3 class="mb-0">{{ number_format($farms_overview['total_hectares'], 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Crop Farms</p>
                <h3 class="mb-0">{{ number_format($farms_overview['by_type']['crops']['count'] ?? 0) }}</h3>
                <p class="text-muted mb-0 mt-2 small">
                    {{ number_format($farms_overview['by_type']['crops']['hectares'] ?? 0, 2) }} ha
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Livestock Farms</p>
                <h3 class="mb-0">{{ number_format($farms_overview['by_type']['livestock']['count'] ?? 0) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Farm Types Distribution -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Farm Types</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Hectares</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($farms_overview['by_type'] as $type => $data)
                            <tr>
                                <td>{{ ucfirst($type) }}</td>
                                <td class="text-end">{{ number_format($data['count']) }}</td>
                                <td class="text-end">{{ number_format($data['hectares'], 2) }}</td>
                                <td class="text-end">{{ $data['percentage'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ownership Status</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($farms_overview['by_ownership'] as $status => $data)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $status)) }}</td>
                                <td class="text-end">{{ number_format($data['count']) }}</td>
                                <td class="text-end">{{ $data['percentage'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Crop Production -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Crop Production Overview</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Total Crop Farms</p>
                        <h5 class="mb-0">{{ number_format($crop_production['total_crop_farms']) }}</h5>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Total Crop Hectares</p>
                        <h5 class="mb-0">{{ number_format($crop_production['total_crop_hectares'], 2) }}</h5>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="cropTable">
                        <thead>
                            <tr>
                                <th>Crop Type</th>
                                <th class="text-end">Farm Count</th>
                                <th class="text-end">Expected Yield (kg)</th>
                                <th class="text-end">Avg Yield/Farm (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($crop_production['by_crop_type'] as $crop)
                            <tr>
                                <td>{{ $crop->crop_type }}</td>
                                <td class="text-end">{{ number_format($crop->farm_count) }}</td>
                                <td class="text-end">{{ number_format($crop->total_expected_yield, 2) }}</td>
                                <td class="text-end">{{ number_format($crop->avg_expected_yield, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farming Methods -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Farming Methods</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($crop_production['by_method'] as $method)
                            <tr>
                                <td>{{ $method->farming_method }}</td>
                                <td class="text-end">{{ number_format($method->count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Livestock Overview</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Animal Type</th>
                                <th class="text-end">Farms</th>
                                <th class="text-end">Total Animals</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($livestock_production['by_animal_type'] as $animal)
                            <tr>
                                <td>{{ $animal->animal_type }}</td>
                                <td class="text-end">{{ number_format($animal->farm_count) }}</td>
                                <td class="text-end">{{ number_format($animal->total_animals) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fisheries & Orchards -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Fisheries</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Total Fishery Farms</p>
                <h5 class="mb-3">{{ number_format($other_production['fisheries']['total_farms']) }}</h5>
                
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Farms</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($other_production['fisheries']['by_type'] as $type)
                            <tr>
                                <td>{{ $type->fishing_type }}</td>
                                <td class="text-end">{{ number_format($type->farm_count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Orchards</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Total Orchard Farms</p>
                <h5 class="mb-3">{{ number_format($other_production['orchards']['total_farms']) }}</h5>
                
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Tree Type</th>
                                <th class="text-end">Farms</th>
                                <th class="text-end">Trees</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($other_production['orchards']['by_tree_type'] as $tree)
                            <tr>
                                <td>{{ $tree->tree_type }}</td>
                                <td class="text-end">{{ number_format($tree->farm_count) }}</td>
                                <td class="text-end">{{ number_format($tree->total_trees) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Production by LGA -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Farms by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="lgaProductionTable">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Farms</th>
                                <th class="text-end">Hectares</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($farms_overview['by_lga'] as $lga)
                            <tr>
                                <td>{{ $lga->lga }}</td>
                                <td class="text-end">{{ number_format($lga->farms) }}</td>
                                <td class="text-end">{{ number_format($lga->hectares, 2) }}</td>
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
    $('#cropTable').DataTable({
        order: [[1, 'desc']],
        pageLength: 25
    });
    
    $('#lgaProductionTable').DataTable({
        order: [[1, 'desc']],
        pageLength: 25
    });
});
</script>
@endpush