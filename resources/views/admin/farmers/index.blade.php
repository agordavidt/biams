@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Farmers Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Farmers</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Farmers</p>
                        <h4 class="mb-2">{{ number_format($stats['totalFarmers']) }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-user-3-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Active Farmers</p>
                        <h4 class="mb-2">{{ number_format($stats['activeFarmers']) }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-user-follow-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Pending Approval</p>
                        <h4 class="mb-2">{{ number_format($stats['pendingApproval']) }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-time-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Farm Types</p>
                        <h4 class="mb-2">{{ $stats['byFarmType']->count() }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-info rounded-3">
                            <i class="ri-landscape-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farm Type Distribution -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Farmers by Farm Type</h5>
                <div class="row">
                    @foreach($stats['byFarmType'] as $type)
                    <div class="col-md-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                            <span class="text-capitalize">{{ $type->farm_type }}</span>
                            <span class="badge bg-primary">{{ number_format($type->farmer_count) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.farmers.index') }}">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <input type="text" name="search" class="form-control" placeholder="Search farmers..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="lga_id" class="form-control">
                                <option value="">All LGAs</option>
                                @foreach($lgas as $lga)
                                <option value="{{ $lga->id }}" {{ request('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="farm_type" class="form-control">
                                <option value="">All Farm Types</option>
                                @foreach($farmTypes as $type)
                                <option value="{{ $type }}" {{ request('farm_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="crop_type" class="form-control">
                                <option value="">All Crop Types</option>
                                @foreach($cropTypes as $type)
                                <option value="{{ $type }}" {{ request('crop_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="animal_type" class="form-control">
                                <option value="">All Animal Types</option>
                                @foreach($animalTypes as $type)
                                <option value="{{ $type }}" {{ request('animal_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending_lga_review" {{ request('status') == 'pending_lga_review' ? 'selected' : '' }}>Pending Review</option>
                                <option value="pending_activation" {{ request('status') == 'pending_activation' ? 'selected' : '' }}>Pending Activation</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('admin.farmers.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Farmers Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Farmers Overview</h4>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Farmer</th>
                                <th>Contact</th>
                                <th>Location</th>
                                <th>Farm Types</th>
                                <th>Status</th>
                                <th>Date Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($farmers as $farmer)
                            <tr>
                                <td>
                                    <strong>{{ $farmer->full_name }}</strong>
                                    <br>
                                    <small class="text-muted">NIN: {{ $farmer->nin }}</small>
                                    <br>
                                    <small class="text-muted">ID: {{ $farmer->id }}</small>
                                </td>
                                <td>
                                    {{ $farmer->email }}
                                    <br>
                                    <small class="text-muted">{{ $farmer->phone_primary }}</small>
                                </td>
                                <td>
                                    <strong>{{ $farmer->lga->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $farmer->ward }}</small>
                                </td>
                                <td>
                                    @php
                                        $farmTypes = $farmer->farmLands->pluck('farm_type')->unique();
                                    @endphp
                                    @foreach($farmTypes as $type)
                                    <span class="badge bg-{{ $type == 'crops' ? 'success' : ($type == 'livestock' ? 'warning' : ($type == 'fisheries' ? 'info' : 'primary')) }} mb-1">
                                        {{ ucfirst($type) }}
                                    </span>
                                    @endforeach
                                    <br>
                                    <small class="text-muted">{{ $farmer->farmLands->count() }} farm(s)</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $farmer->status == 'active' ? 'success' : ($farmer->status == 'pending_lga_review' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $farmer->status)) }}
                                    </span>
                                </td>
                                <td>{{ $farmer->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.farmers.show', $farmer) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-eye-line"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $farmers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LGA Distribution -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Farmers Distribution by LGA</h4>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>LGA</th>
                                <th class="text-center">Total Farmers</th>
                                <th class="text-center">Active</th>
                                <th class="text-center">Pending</th>
                                <th class="text-center">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['byLGA'] as $lgaData)
                            <tr>
                                <td>
                                    <strong>{{ $lgaData->lga->name ?? 'Unknown LGA' }}</strong>
                                </td>
                                <td class="text-center">
                                    <strong>{{ number_format($lgaData->count) }}</strong>
                                </td>
                                <td class="text-center">
                                    {{ number_format(Farmer::where('lga_id', $lgaData->lga_id)->where('status', 'active')->count()) }}
                                </td>
                                <td class="text-center">
                                    {{ number_format(Farmer::where('lga_id', $lgaData->lga_id)->whereIn('status', ['pending_lga_review', 'pending_activation'])->count()) }}
                                </td>
                                <td class="text-center">
                                    {{ number_format(($lgaData->count / $stats['totalFarmers']) * 100, 1) }}%
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
@endsection