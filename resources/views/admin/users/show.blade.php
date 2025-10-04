@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Staff Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Staff</a></li>
                    <li class="breadcrumb-item active">{{ $user->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-lg">
                            <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-1">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-2">
                            @foreach($user->roles as $role)
                                <span class="badge badge-soft-primary me-1">{{ $role->name }}</span>
                            @endforeach
                        </p>
                        <p class="text-muted mb-0">
                            <i class="ri-shield-user-line align-middle"></i> 
                            Staff ID: <strong>{{ $user->id }}</strong>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-soft-secondary">
                            <i class="ri-arrow-left-line"></i> Back to List
                        </a>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="mb-4">
                    @if($user->status == 'onboarded')
                        <span class="badge badge-soft-success fs-6">
                            <i class="ri-checkbox-circle-line"></i> Active
                        </span>
                    @elseif($user->status == 'pending')
                        <span class="badge badge-soft-warning fs-6">
                            <i class="ri-time-line"></i> Pending Activation
                        </span>
                    @else
                        <span class="badge badge-soft-secondary fs-6">{{ ucfirst($user->status) }}</span>
                    @endif
                    
                    @if($user->email_verified_at)
                        <span class="badge badge-soft-info fs-6 ms-2">
                            <i class="ri-shield-check-line"></i> Email Verified
                        </span>
                    @endif
                </div>

                <div class="row">
                    <!-- Contact Information -->
                    <div class="col-lg-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-soft-primary">
                                <h5 class="card-title mb-0">
                                    <i class="ri-contacts-line me-1"></i> Contact Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="fw-medium" style="width: 40%;">
                                                    <i class="ri-mail-line text-muted me-2"></i>Email:
                                                </td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">
                                                    <i class="ri-phone-line text-muted me-2"></i>Phone:
                                                </td>
                                                <td>{{ $user->phone_number ?? 'Not provided' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">
                                                    <i class="ri-calendar-line text-muted me-2"></i>Joined:
                                                </td>
                                                <td>
                                                    {{ $user->created_at->format('M d, Y') }}
                                                    <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-medium">
                                                    <i class="ri-time-line text-muted me-2"></i>Account Age:
                                                </td>
                                                <td>{{ $stats['account_age_days'] }} days</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Administrative Assignment -->
                    <div class="col-lg-6">
                        <div class="card border shadow-none">
                            <div class="card-header bg-soft-success">
                                <h5 class="card-title mb-0">
                                    <i class="ri-building-line me-1"></i> Administrative Assignment
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($user->administrativeUnit)
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-medium" style="width: 40%;">Unit Type:</td>
                                                    <td>
                                                        <span class="badge badge-soft-info">
                                                            {{ class_basename($user->administrative_type) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium">Unit Name:</td>
                                                    <td><strong>{{ $user->administrativeUnit->name }}</strong></td>
                                                </tr>
                                                @if($user->administrative_type === 'App\Models\LGA' && $user->administrativeUnit->code)
                                                <tr>
                                                    <td class="fw-medium">LGA Code:</td>
                                                    <td>{{ $user->administrativeUnit->code }}</td>
                                                </tr>
                                                @endif
                                                @if($user->administrative_type === 'App\Models\Department' && $user->administrativeUnit->abbreviation)
                                                <tr>
                                                    <td class="fw-medium">Abbreviation:</td>
                                                    <td>{{ $user->administrativeUnit->abbreviation }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="ri-error-warning-line fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">No administrative unit assigned</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles and Permissions -->
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="card border shadow-none">
                            <div class="card-header bg-soft-warning">
                                <h5 class="card-title mb-0">
                                    <i class="ri-shield-keyhole-line me-1"></i> Roles & Permissions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Roles -->
                                    <div class="col-md-6">
                                        <h6 class="mb-3">Assigned Roles</h6>
                                        @if($user->roles->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($user->roles as $role)
                                                    <span class="badge badge-soft-primary fs-6">
                                                        <i class="ri-user-star-line"></i> {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">No roles assigned</p>
                                        @endif
                                    </div>

                                    <!-- Permissions -->
                                    <div class="col-md-6">
                                        <h6 class="mb-3">Key Permissions</h6>
                                        @php
                                            $allPermissions = $user->getAllPermissions();
                                            $displayPermissions = $allPermissions->take(10);
                                        @endphp
                                        @if($allPermissions->isNotEmpty())
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($displayPermissions as $permission)
                                                    <span class="badge bg-light text-dark border">
                                                        {{ str_replace('_', ' ', ucfirst($permission->name)) }}
                                                    </span>
                                                @endforeach
                                                @if($allPermissions->count() > 10)
                                                    <span class="badge bg-secondary">
                                                        +{{ $allPermissions->count() - 10 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">No direct permissions</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Summary (if applicable) -->
                @if($user->hasRole(['LGA Admin', 'Enrollment Agent']))
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="card border shadow-none">
                            <div class="card-header bg-soft-info">
                                <h5 class="card-title mb-0">
                                    <i class="ri-line-chart-line me-1"></i> Activity Summary
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="p-3">
                                            <h4 class="mb-1 text-primary">{{ $stats['farmers_enrolled'] }}</h4>
                                            <p class="text-muted mb-0">Farmers Enrolled</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3">
                                            <h4 class="mb-1 text-success">{{ $stats['account_age_days'] }}</h4>
                                            <p class="text-muted mb-0">Days Active</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3">
                                            <h4 class="mb-1 text-info">
                                                @if($stats['last_login'])
                                                    {{ \Carbon\Carbon::parse($stats['last_login'])->diffForHumans() }}
                                                @else
                                                    Never
                                                @endif
                                            </h4>
                                            <p class="text-muted mb-0">Last Login</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Account Timeline -->
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="card border shadow-none">
                            <div class="card-header bg-soft-secondary">
                                <h5 class="card-title mb-0">
                                    <i class="ri-history-line me-1"></i> Account Timeline
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="py-2">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="ri-checkbox-circle-fill text-success fs-5"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Account Created</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $user->created_at->format('F d, Y \a\t g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    @if($user->email_verified_at)
                                    <li class="py-2">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="ri-mail-check-fill text-info fs-5"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Email Verified</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $user->email_verified_at->format('F d, Y \a\t g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($user->updated_at != $user->created_at)
                                    <li class="py-2">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <i class="ri-refresh-line text-warning fs-5"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Last Updated</h6>
                                                <p class="text-muted mb-0">
                                                    {{ $user->updated_at->format('F d, Y \a\t g:i A') }}
                                                    <small>({{ $user->updated_at->diffForHumans() }})</small>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection