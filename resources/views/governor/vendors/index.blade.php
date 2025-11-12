@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Vendors Overview</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('governor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vendors</li>
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
                <form method="GET" action="{{ route('governor.vendors.index') }}">
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
                            <a href="{{ route('governor.vendors.index') }}" class="btn btn-secondary">Reset</a>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Vendors</p>
                        <h4 class="mb-0">{{ number_format($stats['total_vendors']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">{{ $stats['active_vendors'] }} Active</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-store-2-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Vendor Resources</p>
                        <h4 class="mb-0">{{ number_format($stats['total_resources']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">{{ $stats['active_resources'] }} Active</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-database-2-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Applications Fulfilled</p>
                        <h4 class="mb-0">{{ number_format($stats['fulfilled_applications']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted">of {{ number_format($stats['total_applications']) }} total</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-checkbox-circle-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Beneficiaries</p>
                        <h4 class="mb-0">{{ number_format($stats['beneficiaries_served']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-warning">{{ $stats['pending_review'] }} Pending</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-user-heart-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ministry vs Vendor Impact -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ministry vs Vendor Impact Comparison</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6 border-end">
                        <h6 class="text-muted mb-3">Ministry Resources</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <p class="text-muted mb-1">Resources</p>
                                <h4>{{ number_format($impactComparison['ministry']['resources']) }}</h4>
                            </div>
                            <div class="col-6 mb-3">
                                <p class="text-muted mb-1">Applications</p>
                                <h4>{{ number_format($impactComparison['ministry']['applications']) }}</h4>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1">Fulfilled</p>
                                <h4>{{ number_format($impactComparison['ministry']['fulfilled']) }}</h4>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1">Beneficiaries</p>
                                <h4>{{ number_format($impactComparison['ministry']['beneficiaries']) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Vendor Resources</h6>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <p class="text-muted mb-1">Resources</p>
                                <h4>{{ number_format($impactComparison['vendor']['resources']) }}</h4>
                            </div>
                            <div class="col-6 mb-3">
                                <p class="text-muted mb-1">Applications</p>
                                <h4>{{ number_format($impactComparison['vendor']['applications']) }}</h4>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1">Fulfilled</p>
                                <h4>{{ number_format($impactComparison['vendor']['fulfilled']) }}</h4>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1">Beneficiaries</p>
                                <h4>{{ number_format($impactComparison['vendor']['beneficiaries']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Performance Ranking -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Vendor Performance Ranking</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Vendor Name</th>
                                <th>Organization Type</th>
                                <th class="text-end">Resources</th>
                                <th class="text-end">Applications</th>
                                <th class="text-end">Fulfilled</th>
                                <th class="text-end">Success Rate</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendorPerformance as $vendor)
                            <tr>
                                <td>{{ $vendor['name'] }}</td>
                                <td>{{ $vendor['organization_type'] }}</td>
                                <td class="text-end">
                                    {{ $vendor['active_resources'] }} / {{ $vendor['total_resources'] }}
                                </td>
                                <td class="text-end">{{ number_format($vendor['total_applications']) }}</td>
                                <td class="text-end">{{ number_format($vendor['fulfilled_applications']) }}</td>
                                <td class="text-end">
                                    <span class="badge badge-soft-{{ $vendor['fulfillment_rate'] >= 70 ? 'success' : ($vendor['fulfillment_rate'] >= 50 ? 'warning' : 'danger') }}">
                                        {{ $vendor['fulfillment_rate'] }}%
                                    </span>
                                </td>
                                <td>
                                    @if($vendor['is_active'])
                                        <span class="badge badge-soft-success">Active</span>
                                    @else
                                        <span class="badge badge-soft-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No vendor data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Distribution & Focus Areas -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Vendors by Organization Type</h5>
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
                            @foreach($vendorsByType as $item)
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
                <h5 class="card-title mb-0">Top Focus Areas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Focus Area</th>
                                <th class="text-end">Vendors</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($focusAreasData->take(10) as $item)
                            <tr>
                                <td>{{ $item['area'] }}</td>
                                <td class="text-end">{{ number_format($item['count']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Geographic Coverage -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Geographic Coverage by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Active Vendors</th>
                                <th class="text-end">Beneficiaries</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($geographicCoverage as $lga)
                            <tr>
                                <td>{{ $lga->lga_name }}</td>
                                <td class="text-end">{{ number_format($lga->vendor_count) }}</td>
                                <td class="text-end">{{ number_format($lga->beneficiaries) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No data available</td>
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