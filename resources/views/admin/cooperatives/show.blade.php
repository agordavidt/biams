@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $cooperative->name }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.cooperatives.index') }}">Cooperatives</a></li>
                    <li class="breadcrumb-item active">{{ $cooperative->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Cooperative Header -->
<div class="row">
    <div class="col-12">
        <div class="card bg-soft-primary border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-lg">
                            <span class="avatar-title rounded-circle bg-primary fs-2">
                                {{ substr($cooperative->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-1">{{ $cooperative->name }}</h3>
                        <p class="text-muted mb-1">
                            <strong>Registration Number:</strong> {{ $cooperative->registration_number }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="ri-map-pin-line me-1"></i>
                            <strong>LGA:</strong> {{ $cooperative->lga->name }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge badge-soft-primary px-3 py-2 fs-6">
                            <i class="ri-community-line align-middle me-1"></i> Cooperative
                        </span>
                    </div>
                </div>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Members</p>
                        <h4 class="mb-0">{{ $memberStats['total_members'] }}</h4>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-team-line text-primary"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Active Members</p>
                        <h4 class="mb-0">{{ $memberStats['active_members'] }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">
                                {{ number_format($performance['member_retention_rate'], 1) }}% Retention
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-user-check-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Land</p>
                        <h4 class="mb-0">{{ number_format($cooperative->total_land_size, 1) }} ha</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                {{ number_format($performance['land_per_member'], 1) }} ha/member
                            </span>
                        </p>
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

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Leadership</p>
                        <h4 class="mb-0">{{ $memberStats['leadership_positions'] }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                Executive positions
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-user-star-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Cooperative Details -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-information-line me-1"></i> Cooperative Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td class="text-muted" width="40%">Registration Number</td>
                                <td class="fw-medium">{{ $cooperative->registration_number }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Name</td>
                                <td class="fw-medium">{{ $cooperative->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">LGA</td>
                                <td>
                                    <span class="badge bg-soft-primary">{{ $cooperative->lga->name }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Contact Person</td>
                                <td>{{ $cooperative->contact_person ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Phone</td>
                                <td>{{ $cooperative->phone ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $cooperative->email ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Reported Members</td>
                                <td>{{ $cooperative->total_member_count ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Land Size</td>
                                <td>
                                    @if($cooperative->total_land_size > 0)
                                    <span class="fw-medium text-success">{{ number_format($cooperative->total_land_size, 1) }} hectares</span>
                                    @else
                                    <span class="text-muted">Not reported</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Registered By</td>
                                <td>{{ $cooperative->registeredBy->name ?? 'System' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Registration Date</td>
                                <td>{{ $cooperative->created_at->format('M d, Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Primary Activities -->
        @if(count($activities) > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-list-check me-1"></i> Primary Activities
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($activities as $activity)
                    <span class="badge bg-soft-info text-info px-3 py-2">
                        <i class="ri-check-line me-1"></i> {{ $activity }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Members List -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-team-line me-1"></i> Cooperative Members
                    <span class="badge bg-soft-primary ms-2">{{ $memberStats['total_members'] }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($cooperative->members->count() > 0)
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-sm table-borderless">
                        <thead class="sticky-top bg-light">
                            <tr>
                                <th>Member Name</th>
                                <th>Phone</th>
                                <th>Position</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cooperative->members as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                {{ substr($member->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.users.show', $member->user_id) }}" class="text-body fw-medium">
                                                {{ $member->full_name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $member->pivot->membership_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $member->phone_primary }}</small>
                                </td>
                                <td>
                                    @if($member->pivot->position)
                                    <span class="badge bg-soft-info text-info">{{ $member->pivot->position }}</span>
                                    @else
                                    <span class="text-muted">Member</span>
                                    @endif
                                </td>
                                <td>
                                    @if($member->pivot->membership_status === 'active')
                                    <span class="badge bg-soft-success text-success">Active</span>
                                    @else
                                    <span class="badge bg-soft-warning text-warning">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="ri-user-unfollow-line font-size-48 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No Members</h5>
                    <p class="text-muted">This cooperative doesn't have any registered members yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> Performance Metrics
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-primary">{{ number_format($performance['member_retention_rate'], 1) }}%</h4>
                            <p class="text-muted mb-0">Member Retention Rate</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-success">{{ number_format($performance['land_per_member'], 1) }} ha</h4>
                            <p class="text-muted mb-0">Land per Active Member</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="py-3">
                            <h4 class="mb-1 text-info">{{ $memberStats['leadership_positions'] }}</h4>
                            <p class="text-muted mb-0">Leadership Positions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="row">
    <div class="col-12">
        <div class="text-center mt-3">
            <a href="{{ route('admin.cooperatives.index') }}" class="btn btn-light">
                <i class="ri-arrow-left-line me-1"></i> Back to Cooperatives List
            </a>
        </div>
    </div>
</div>
@endsection