@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Farmer Analytics</h4>
            <div class="page-title-right">
                <a href="{{ route('governor.farmers.export') }}" class="btn btn-primary">Download Report</a>
            </div>
        </div>
    </div>
</div>

<!-- Demographics Summary -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Total Active Farmers</p>
                <h3 class="mb-0">{{ number_format($demographics['total']) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Female Farmers</p>
                <h3 class="mb-0">{{ number_format($demographics['gender']['Female']['count'] ?? 0) }}</h3>
                <p class="text-muted mb-0 mt-2 small">
                    {{ $demographics['gender']['Female']['percentage'] ?? 0 }}% of total
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Male Farmers</p>
                <h3 class="mb-0">{{ number_format($demographics['gender']['Male']['count'] ?? 0) }}</h3>
                <p class="text-muted mb-0 mt-2 small">
                    {{ $demographics['gender']['Male']['percentage'] ?? 0 }}% of total
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Avg. Household Size</p>
                <h3 class="mb-0">{{ number_format($demographics['avg_household_size'], 1) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Age Distribution -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Age Distribution</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Age Group</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demographics['age_groups'] as $group => $data)
                            <tr>
                                <td>{{ $group }}</td>
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

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Marital Status</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demographics['marital_status'] as $status => $data)
                            <tr>
                                <td>{{ ucfirst($status) }}</td>
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

<!-- Education Breakdown -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Education Level</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Education Level</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($education as $level)
                            <tr>
                                <td>{{ $level->educational_level }}</td>
                                <td class="text-end">{{ number_format($level->count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farmers by LGA -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Farmers by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="farmersByLgaTable">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Active</th>
                                <th class="text-end">Pending</th>
                                <th class="text-end">Female</th>
                                <th class="text-end">Male</th>
                                <th class="text-end">Youth</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($by_lga as $lga)
                            <tr>
                                <td>{{ $lga->lga }}</td>
                                <td class="text-end">{{ number_format($lga->active) }}</td>
                                <td class="text-end">{{ number_format($lga->pending) }}</td>
                                <td class="text-end">{{ number_format($lga->female) }}</td>
                                <td class="text-end">{{ number_format($lga->male) }}</td>
                                <td class="text-end">{{ number_format($lga->youth) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cooperative Statistics -->
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Cooperative Membership</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td>Total Cooperatives</td>
                                <td class="text-end fw-bold">{{ number_format($cooperatives['total_cooperatives']) }}</td>
                            </tr>
                            <tr>
                                <td>Farmers in Cooperatives</td>
                                <td class="text-end fw-bold">{{ number_format($cooperatives['farmers_in_cooperatives']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Cooperatives by Membership</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Cooperative</th>
                                <th>LGA</th>
                                <th class="text-end">Members</th>
                                <th class="text-end">Land Size (ha)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cooperatives['top_cooperatives'] as $coop)
                            <tr>
                                <td>{{ $coop->name }}</td>
                                <td>{{ $coop->lga }}</td>
                                <td class="text-end">{{ number_format($coop->total_member_count) }}</td>
                                <td class="text-end">{{ number_format($coop->total_land_size, 2) }}</td>
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
    $('#farmersByLgaTable').DataTable({
        order: [[1, 'desc']],
        pageLength: 25
    });
});
</script>
@endpush