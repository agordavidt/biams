@extends('layouts.super_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resources Analytics</h4>
            <div>
                <a href="{{ route('super_admin.resources.index') }}" class="btn btn-secondary">Back to Resources</a>
            </div>
        </div>
    </div>
</div>

<!-- Date Filter -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('super_admin.resources.analytics') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-3">
                            <label>To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                            <a href="{{ route('super_admin.resources.analytics') }}" class="btn btn-secondary ms-2">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Resource Distribution by Type -->
<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Resources by Type</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resourcesByType as $item)
                            <tr>
                                <td>{{ ucfirst($item->type) }}</td>
                                <td>{{ number_format($item->count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Resource Status Distribution -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Resources by Status</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statusDistribution as $item)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                                <td>{{ number_format($item->count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Resources by Applications -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Top Resources by Applications</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Resource Name</th>
                                <th>Type</th>
                                <th>Source</th>
                                <th>Status</th>
                                <th>Total Applications</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topResources as $resource)
                            <tr>
                                <td>
                                    <a href="{{ route('super_admin.resources.show', $resource) }}">
                                        {{ $resource->name }}
                                    </a>
                                </td>
                                <td>{{ ucfirst($resource->type) }}</td>
                                <td>
                                    @if($resource->vendor_id)
                                        Vendor
                                    @else
                                        Ministry
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $resource->status === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($resource->status) }}
                                    </span>
                                </td>
                                <td>{{ number_format($resource->applications_count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Performance -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Top Vendors by Applications</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Vendor Name</th>
                                <th>Total Resources</th>
                                <th>Active Resources</th>
                                <th>Total Applications</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendorPerformance as $vendor)
                            <tr>
                                <td>
                                    <a href="{{ route('super_admin.vendors.show', $vendor['id']) }}">
                                        {{ $vendor['name'] }}
                                    </a>
                                </td>
                                <td>{{ number_format($vendor['total_resources']) }}</td>
                                <td>{{ number_format($vendor['active_resources']) }}</td>
                                <td>{{ number_format($vendor['total_applications']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Applications Over Time -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Applications Over Time</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Applications</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicationsOverTime as $item)
                            <tr>
                                <td>{{ $item->date }}</td>
                                <td>{{ number_format($item->count) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">No data available for selected period</td>
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