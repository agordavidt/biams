@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Cooperatives Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Cooperatives</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- State-wide Statistics -->
<div class="row">
    <div class="col-xl-2 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Cooperatives</p>
                        <h4 class="mb-0">{{ number_format($stats['total_cooperatives']) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-community-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Members</p>
                        <h4 class="mb-0">{{ number_format($stats['total_members']) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-team-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Active Members</p>
                        <h4 class="mb-0">{{ number_format($stats['active_members']) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-user-check-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Land Managed</p>
                        <h4 class="mb-0">{{ number_format($stats['total_land_managed'], 1) }} ha</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-map-pin-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">With Land Data</p>
                        <h4 class="mb-0">{{ number_format($stats['cooperatives_with_land']) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-purple rounded-circle fs-3">
                                <i class="ri-landscape-line text-purple"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Avg Members</p>
                        <h4 class="mb-0">{{ number_format($stats['average_members_per_cooperative'], 1) }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-orange rounded-circle fs-3">
                                <i class="ri-bar-chart-line text-orange"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.cooperatives.index') }}" method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Search cooperatives...">
                        </div>
                        <div class="col-md-3">
                            <label for="lga_id" class="form-label">Filter by LGA</label>
                            <select class="form-select" id="lga_id" name="lga_id">
                                <option value="">All LGAs</option>
                                @foreach($lgas as $lga)
                                <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>
                                    {{ $lga->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Registered</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="total_member_count" {{ request('sort') == 'total_member_count' ? 'selected' : '' }}>Member Count</option>
                                <option value="total_land_size" {{ request('sort') == 'total_land_size' ? 'selected' : '' }}>Land Size</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="direction" class="form-label">Direction</label>
                            <select class="form-select" id="direction" name="direction">
                                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-search-line me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-light">
                                <i class="ri-refresh-line me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cooperatives List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Cooperatives in the State</h5>
                <div>
                    <a href="{{ route('admin.cooperatives.export') }}" class="btn btn-success btn-sm">
                        <i class="ri-file-excel-line me-1"></i> Export
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($cooperatives->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Registration No.</th>
                                <th>Cooperative Name</th>
                                <th>LGA</th>
                                <th>Contact Person</th>
                                <th>Members</th>
                                <th>Land Size (ha)</th>
                                <th>Activities</th>
                                <th>Registered On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cooperatives as $cooperative)
                            <tr>
                                <td>
                                    <span class="fw-medium text-primary">{{ $cooperative->registration_number }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                {{ substr($cooperative->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $cooperative->name }}</h6>
                                            <small class="text-muted">{{ $cooperative->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-soft-secondary">{{ $cooperative->lga->name }}</span>
                                </td>
                                <td>
                                    @if($cooperative->contact_person)
                                    <div>
                                        <span class="fw-medium">{{ $cooperative->contact_person }}</span>
                                        @if($cooperative->phone)
                                        <br><small class="text-muted">{{ $cooperative->phone }}</small>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-center">
                                        <h6 class="mb-0">{{ $cooperative->active_members_count }}</h6>
                                        <small class="text-muted">active</small>
                                        @if($cooperative->total_members_count > $cooperative->active_members_count)
                                        <br><small class="text-warning">{{ $cooperative->total_members_count - $cooperative->active_members_count }} inactive</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($cooperative->total_land_size > 0)
                                    <span class="fw-medium text-success">{{ number_format($cooperative->total_land_size, 1) }} ha</span>
                                    @else
                                    <span class="text-muted">Not reported</span>
                                    @endif
                                </td>
                                <td>
                                    @if($cooperative->primary_activities && count($cooperative->primary_activities) > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(array_slice($cooperative->primary_activities, 0, 2) as $activity)
                                        <span class="badge bg-soft-info text-info font-size-11">{{ $activity }}</span>
                                        @endforeach
                                        @if(count($cooperative->primary_activities) > 2)
                                        <span class="badge bg-soft-secondary font-size-11">+{{ count($cooperative->primary_activities) - 2 }} more</span>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $cooperative->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.cooperatives.show', $cooperative) }}" class="btn btn-sm btn-soft-primary">
                                        <i class="ri-eye-line"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $cooperatives->links() }}
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="ri-community-line font-size-48 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No Cooperatives Found</h5>
                    <p class="text-muted">No cooperatives match your search criteria.</p>
                    <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-primary">
                        <i class="ri-refresh-line me-1"></i> Clear Filters
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- LGA Distribution -->
@if($lgaDistribution->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Cooperatives Distribution by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless">
                        <tbody>
                            @foreach($lgaDistribution as $distribution)
                            <tr>
                                <td width="60%">
                                    <span class="fw-medium">{{ $distribution->lga->name }}</span>
                                </td>
                                <td width="30%">
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: {{ ($distribution->count / $stats['total_cooperatives']) * 100 }}%" 
                                             aria-valuenow="{{ ($distribution->count / $stats['total_cooperatives']) * 100 }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>
                                <td width="10%" class="text-end">
                                    <span class="fw-medium text-primary">{{ $distribution->count }}</span>
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
@endif
@endsection

@push('scripts')
<script>
    // Auto-submit form when filters change
    document.getElementById('filterForm').addEventListener('change', function() {
        this.submit();
    });

    // DataTable initialization for better sorting and searching
    $(document).ready(function() {
        $('table').DataTable({
            "pageLength": 25,
            "ordering": false, // Disable DataTable sorting as we have custom sorting
            "searching": false, // Disable DataTable search as we have custom search
            "info": false,
            "paging": false // Disable DataTable pagination as we use Laravel pagination
        });
    });
</script>
@endpush