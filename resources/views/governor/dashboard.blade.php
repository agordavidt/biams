@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Governor's Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Farmers</p>
                        <h4 class="mb-0">{{ number_format($summary['total_farmers']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Farms</p>
                        <h4 class="mb-0">{{ number_format($summary['total_farms']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Hectares</p>
                        <h4 class="mb-0">{{ number_format($summary['total_hectares'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Active Resources</p>
                        <h4 class="mb-0">{{ number_format($summary['active_resources']) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Analytics Reports</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('governor.overview') }}" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    <h6 class="mb-2">System Overview</h6>
                                    <p class="text-muted mb-0 small">Complete system summary</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('governor.farmers') }}" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    <h6 class="mb-2">Farmer Analytics</h6>
                                    <p class="text-muted mb-0 small">Demographics & statistics</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('governor.production') }}" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    <h6 class="mb-2">Production Analytics</h6>
                                    <p class="text-muted mb-0 small">Farm production data</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3">
                        <a href="{{ route('governor.lgas') }}" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body text-center">
                                    <h6 class="mb-2">LGA Analytics</h6>
                                    <p class="text-muted mb-0 small">LGA comparison & ranking</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LGA Summary -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">LGA Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Farmers</th>
                                <th class="text-end">Farms</th>
                                <th class="text-end">Hectares</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lga_summary as $lga)
                            <tr>
                                <td>{{ $lga->name }}</td>
                                <td class="text-end">{{ number_format($lga->farmers) }}</td>
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