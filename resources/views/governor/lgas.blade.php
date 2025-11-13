@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">LGA Analytics</h4>
            <div class="page-title-right">
                <a href="{{ route('governor.lgas.export') }}" class="btn btn-primary">Download Report</a>
            </div>
        </div>
    </div>
</div>

<!-- LGA Comparison Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">LGA Comparison</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="lgaComparisonTable">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th>Code</th>
                                <th class="text-end">Farmers</th>
                                <th class="text-end">Female</th>
                                <th class="text-end">Male</th>
                                <th class="text-end">Youth</th>
                                <th class="text-end">Farms</th>
                                <th class="text-end">Hectares</th>
                                <th class="text-end">Avg Farm Size</th>
                                <th class="text-end">Cooperatives</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lga_comparison as $lga)
                            <tr>
                                <td><strong>{{ $lga->name }}</strong></td>
                                <td>{{ $lga->code }}</td>
                                <td class="text-end">{{ number_format($lga->total_farmers) }}</td>
                                <td class="text-end">{{ number_format($lga->female_farmers) }}</td>
                                <td class="text-end">{{ number_format($lga->male_farmers) }}</td>
                                <td class="text-end">{{ number_format($lga->youth_farmers) }}</td>
                                <td class="text-end">{{ number_format($lga->total_farms) }}</td>
                                <td class="text-end">{{ number_format($lga->total_hectares, 2) }}</td>
                                <td class="text-end">{{ number_format($lga->avg_farm_size, 2) }}</td>
                                <td class="text-end">{{ number_format($lga->cooperatives) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Ranking -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Performance Ranking</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>LGA</th>
                                <th class="text-end">Farmers</th>
                                <th class="text-end">Hectares</th>
                                <th class="text-end">Farms</th>
                                <th class="text-end">Applications</th>
                                <th class="text-end">Approved</th>
                                <th class="text-end">Success Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($performance_ranking as $index => $lga)
                            <tr>
                                <td><strong>#{{ $index + 1 }}</strong></td>
                                <td>{{ $lga['lga'] }}</td>
                                <td class="text-end">{{ number_format($lga['farmers']) }}</td>
                                <td class="text-end">{{ number_format($lga['hectares']) }}</td>
                                <td class="text-end">{{ number_format($lga['farms']) }}</td>
                                <td class="text-end">{{ number_format($lga['applications']) }}</td>
                                <td class="text-end">{{ number_format($lga['approved']) }}</td>
                                <td class="text-end">{{ $lga['success_rate'] }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resource Distribution -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Resource Applications by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Approved</th>
                                <th class="text-end">Pending</th>
                                <th class="text-end">Declined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resource_distribution['applications_by_lga'] as $lga)
                            <tr>
                                <td>{{ $lga->lga }}</td>
                                <td class="text-end">{{ number_format($lga->total) }}</td>
                                <td class="text-end">{{ number_format($lga->approved) }}</td>
                                <td class="text-end">{{ number_format($lga->pending) }}</td>
                                <td class="text-end">{{ number_format($lga->declined) }}</td>
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
                <h5 class="card-title mb-0">Resource Coverage by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Total Farmers</th>
                                <th class="text-end">Beneficiaries</th>
                                <th class="text-end">Coverage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resource_distribution['coverage'] as $lga)
                            <tr>
                                <td>{{ $lga->name }}</td>
                                <td class="text-end">{{ number_format($lga->total_farmers) }}</td>
                                <td class="text-end">{{ number_format($lga->beneficiaries) }}</td>
                                <td class="text-end">{{ $lga->coverage_rate }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farm Types by LGA -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Farm Types Distribution by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="farmTypesTable">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Crops</th>
                                <th class="text-end">Livestock</th>
                                <th class="text-end">Fisheries</th>
                                <th class="text-end">Orchards</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lga_comparison as $lga)
                            <tr>
                                <td>{{ $lga->name }}</td>
                                <td class="text-end">{{ number_format($lga->crop_farms) }}</td>
                                <td class="text-end">{{ number_format($lga->livestock_farms) }}</td>
                                <td class="text-end">{{ number_format($lga->fishery_farms) }}</td>
                                <td class="text-end">{{ number_format($lga->orchard_farms) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Resources by LGA -->
@if(isset($resource_distribution['top_resources_by_lga']) && $resource_distribution['top_resources_by_lga']->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 5 Resources per LGA</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($resource_distribution['top_resources_by_lga'] as $lga => $resources)
                    <div class="col-xl-4 col-md-6 mb-3">
                        <h6 class="mb-2">{{ $lga }}</h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Resource</th>
                                        <th class="text-end">Applications</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($resources as $resource)
                                    <tr>
                                        <td class="small">{{ $resource->resource }}</td>
                                        <td class="text-end small">{{ number_format($resource->applications) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#lgaComparisonTable').DataTable({
        order: [[2, 'desc']],
        pageLength: 25,
        scrollX: true
    });
    
    $('#farmTypesTable').DataTable({
        order: [[1, 'desc']],
        pageLength: 25
    });
});
</script>
@endpush