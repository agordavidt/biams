@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Cooperative Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.cooperatives.index') }}">Cooperatives</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <!-- Header Card -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ $cooperative->name }}</h4>
                        <p class="text-muted mb-2">
                            <span class="badge bg-soft-primary text-primary">{{ $cooperative->registration_number }}</span>
                        </p>
                        <div class="d-flex gap-3 text-muted">
                            <span><i class="ri-map-pin-line me-1"></i>{{ $cooperative->lga->name }} LGA</span>
                            <span><i class="ri-calendar-line me-1"></i>Registered: {{ $cooperative->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="d-flex gap-2">
                            <a href="{{ route('lga_admin.cooperatives.members', $cooperative) }}" class="btn btn-success">
                                <i class="ri-team-line me-1"></i>Manage Members
                            </a>
                            <a href="{{ route('lga_admin.cooperatives.edit', $cooperative) }}" class="btn btn-warning">
                                <i class="ri-pencil-line me-1"></i>Edit
                            </a>
                            <a href="{{ route('lga_admin.cooperatives.index') }}" class="btn btn-light">
                                <i class="ri-arrow-left-line me-1"></i>Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Membership Statistics -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Membership Overview</h5>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Active Members</span>
                        <span class="badge bg-soft-success text-success">{{ number_format($memberStats['active_members']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Inactive Members</span>
                        <span class="badge bg-soft-warning text-warning">{{ number_format($memberStats['inactive_members']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Total Enrolled</span>
                        <span class="badge bg-soft-info text-info">{{ number_format($memberStats['total_enrolled']) }}</span>
                    </div>
                </div>
                <hr>
                <div class="mt-3">
                    <p class="text-muted mb-1">Reported Member Count</p>
                    <h4 class="mb-0">{{ number_format($cooperative->total_member_count) }}</h4>
                    <small class="text-muted">As declared by cooperative</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Basic Information</h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Registration Number</label>
                        <p class="mb-0 fw-medium">{{ $cooperative->registration_number }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Cooperative Name</label>
                        <p class="mb-0 fw-medium">{{ $cooperative->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Contact Person</label>
                        <p class="mb-0 fw-medium">{{ $cooperative->contact_person }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Phone Number</label>
                        <p class="mb-0 fw-medium">
                            <i class="ri-phone-line text-muted"></i> {{ $cooperative->phone }}
                        </p>
                    </div>
                    @if($cooperative->email)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email Address</label>
                        <p class="mb-0 fw-medium">
                            <i class="ri-mail-line text-muted"></i> {{ $cooperative->email }}
                        </p>
                    </div>
                    @endif
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Total Land Managed</label>
                        <p class="mb-0 fw-medium">{{ number_format($cooperative->total_land_size ?? 0, 2) }} hectares</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="text-muted small">Primary Activities</label>
                        <div class="mt-2">
                            @if($cooperative->primary_activities)
                                @foreach($cooperative->primary_activities as $activity)
                                <span class="badge bg-soft-primary text-primary me-1 mb-1">{{ $activity }}</span>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No activities specified</p>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Registered By</label>
                        <p class="mb-0">{{ $cooperative->registeredBy->name }}</p>
                        <small class="text-muted">{{ $cooperative->registeredBy->email }}</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Registration Date</label>
                        <p class="mb-0">{{ $cooperative->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    @if($cooperative->updated_at != $cooperative->created_at)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Last Updated</label>
                        <p class="mb-0">{{ $cooperative->updated_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Members -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Members</h5>
                <a href="{{ route('lga_admin.cooperatives.members', $cooperative) }}" class="btn btn-sm btn-primary">
                    View All Members <i class="ri-arrow-right-line ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                @if($cooperative->members->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Member Name</th>
                                <th>Phone</th>
                                <th>Membership No.</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cooperative->members->take(10) as $member)
                            <tr>
                                <td>
                                    <a href="{{ route('lga_admin.farmers.show', $member) }}" class="text-body fw-medium">
                                        {{ $member->full_name }}
                                    </a>
                                </td>
                                <td>{{ $member->phone_primary }}</td>
                                <td>
                                    <span class="badge bg-soft-secondary text-secondary">
                                        {{ $member->pivot->membership_number }}
                                    </span>
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
                                <td>{{ \Carbon\Carbon::parse($member->pivot->joined_date)->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="ri-team-line font-size-48 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-3">No members registered yet</p>
                    <a href="{{ route('lga_admin.cooperatives.members', $cooperative) }}" class="btn btn-sm btn-primary">
                        <i class="ri-user-add-line me-1"></i>Add First Member
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection