@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resources Overview</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('governor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resources</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Date Filter -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('governor.resources.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                            <a href="{{ route('governor.resources.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Resources</p>
                        <h4 class="mb-0">{{ number_format($stats['total_resources']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">{{ $stats['active_resources'] }} Active</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-database-2-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Applications</p>
                        <h4 class="mb-0">{{ number_format($stats['total_applications']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-warning">{{ $stats['pending_applications'] }} Pending</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-file-list-3-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Beneficiaries Served</p>
                        <h4 class="mb-0">{{ number_format($stats['total_beneficiaries']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">{{ $stats['fulfilled_applications'] }} Fulfilled</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-user-heart-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Value</p>
                        <h4 class="mb-0">₦{{ number_format($stats['total_value_distributed'], 2) }}</h4>
                        <p class="text-muted mb-0 mt-2">Distributed</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-money-dollar-circle-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resource Distribution & Payment Analysis -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Resources by Type</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resourcesByType as $item)
                            <tr>
                                <td>{{ $item['type'] }}</td>
                                <td class="text-end">{{ number_format($item['count']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment vs Free Resources Impact</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Paid Resources</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Resources: <strong>{{ $paymentAnalysis['paid']['count'] }}</strong></span>
                        <span>Applications: <strong>{{ $paymentAnalysis['paid']['applications'] }}</strong></span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $paymentAnalysis['paid']['count'] > 0 ? ($paymentAnalysis['paid']['beneficiaries'] / $paymentAnalysis['paid']['applications']) * 100 : 0 }}%">
                            {{ $paymentAnalysis['paid']['beneficiaries'] }} Beneficiaries
                        </div>
                    </div>
                </div>

                <div>
                    <h6 class="text-muted mb-2">Free Resources</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Resources: <strong>{{ $paymentAnalysis['free']['count'] }}</strong></span>
                        <span>Applications: <strong>{{ $paymentAnalysis['free']['applications'] }}</strong></span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-info" style="width: {{ $paymentAnalysis['free']['count'] > 0 ? ($paymentAnalysis['free']['beneficiaries'] / $paymentAnalysis['free']['applications']) * 100 : 0 }}%">
                            {{ $paymentAnalysis['free']['beneficiaries'] }} Beneficiaries
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Performing Resources -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Performing Resources</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Resource Name</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th class="text-end">Applications</th>
                                <th class="text-end">Fulfilled</th>
                                <th class="text-end">Success Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topResources as $resource)
                            <tr>
                                <td>{{ $resource->name }}</td>
                                <td>{{ ucfirst($resource->type) }}</td>
                                <td>
                                    @if($resource->vendor_id)
                                        Vendor
                                    @else
                                        Ministry
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($resource->applications_count) }}</td>
                                <td class="text-end">{{ number_format($resource->fulfilled_count) }}</td>
                                <td class="text-end">
                                    <span class="badge badge-soft-{{ $resource->applications_count > 0 && ($resource->fulfilled_count / $resource->applications_count) * 100 >= 70 ? 'success' : 'warning' }}">
                                        {{ $resource->applications_count > 0 ? round(($resource->fulfilled_count / $resource->applications_count) * 100, 1) : 0 }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LGA Distribution Efficiency -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribution Efficiency by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Applications</th>
                                <th class="text-end">Fulfilled</th>
                                <th class="text-end">Success Rate</th>
                                <th class="text-end">Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lgaDistribution as $lga)
                            <tr>
                                <td>{{ $lga->lga_name }}</td>
                                <td class="text-end">{{ number_format($lga->total_applications) }}</td>
                                <td class="text-end">{{ number_format($lga->fulfilled) }}</td>
                                <td class="text-end">
                                    <span class="badge badge-soft-{{ $lga->total_applications > 0 && ($lga->fulfilled / $lga->total_applications) * 100 >= 70 ? 'success' : 'warning' }}">
                                        {{ $lga->total_applications > 0 ? round(($lga->fulfilled / $lga->total_applications) * 100, 1) : 0 }}%
                                    </span>
                                </td>
                                <td class="text-end">₦{{ number_format($lga->total_value, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card-animate {
    transition: all 0.3s ease;
}
.card-animate:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
@endpush