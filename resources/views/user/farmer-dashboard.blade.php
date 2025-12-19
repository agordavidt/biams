@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Farmer Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if (session('error'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

@if (session('success'))
<div class="row">
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif

<!-- Quick Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Farm Lands</p>
                        <h4 class="mb-0">{{ $farmer->farmLands->count() }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                <i class="ri-checkbox-circle-line align-middle"></i> Active Farms
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-landscape-line text-success"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Farm Size</p>
                        <h4 class="mb-0">{{ number_format($farmer->getTotalFarmSizeAttribute(), 2) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                Hectares
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-ruler-line text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Cooperative Status</p>
                        <h4 class="mb-0">{{ $farmer->cooperative ? 'Member' : 'None' }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            @if($farmer->cooperative)
                                <span class="badge badge-soft-success">
                                    <i class="ri-team-line align-middle"></i> Joined
                                </span>
                            @else
                                <span class="badge badge-soft-warning">
                                    <i class="ri-information-line align-middle"></i> Not Joined
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-group-line text-warning"></i>
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
                        <p class="text-uppercase fw-medium text-muted mb-1">Member Since</p>
                        <h4 class="mb-0">{{ $farmer->created_at->format('M Y') }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                {{ $farmer->created_at->diffForHumans() }}
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-calendar-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Farmer Profile & Quick Actions -->
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-user-line me-1"></i> Farmer Profile
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="profile-user position-relative d-inline-block mx-auto mb-3">
                        @if($farmer->farmer_photo)
                            <img src="{{ asset('storage/' . $farmer->farmer_photo) }}" 
                                 alt="Profile Photo" 
                                 class="rounded-circle avatar-xl img-thumbnail user-profile-image">
                        @else
                            <div class="avatar-xl mx-auto">
                                <span class="avatar-title rounded-circle bg-soft-primary text-primary fs-2">
                                    {{ strtoupper(substr($farmer->full_name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h5 class="mb-1">{{ $farmer->full_name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <div class="mb-3">
                        <span class="badge badge-soft-success px-3 py-2">
                            <i class="ri-checkbox-circle-line align-middle"></i> Active Farmer
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-phone-line me-2"></i>Phone
                                </td>
                                <td class="text-end fw-medium">{{ $farmer->phone_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-map-pin-line me-2"></i>Location
                                </td>
                                <td class="text-end fw-medium">{{ $farmer->ward }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-building-line me-2"></i>LGA
                                </td>
                                <td class="text-end fw-medium">{{ $farmer->lga->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-user-settings-line me-2"></i>Status
                                </td>
                                <td class="text-end">
                                    <span class="badge badge-soft-success">Verified</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <a href="{{ route('farmer.profile') }}" class="btn btn-primary w-100">
                         Update Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Quick Actions -->
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ri-flashlight-line me-1"></i> Quick Actions
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('farmer.profile') }}" class="text-decoration-none">
                        <div class="card module-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                        <i class="ri-user-settings-line text-primary"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">My Profile</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Manage personal info
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('farmer.marketplace.index') }}" class="text-decoration-none">
                        <div class="card module-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                        <i class="ri-store-line text-success"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">Marketplace</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Buy & sell products
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('farmer.resources.index') }}" class="text-decoration-none">
                        <div class="card module-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-info fs-3">
                                        <i class="ri-plant-line text-info"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">Resources</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Agric resources
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('farmer.support.index') }}" class="text-decoration-none">
                        <div class="card module-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-warning fs-3">
                                        <i class="ri-customer-service-2-line text-warning"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">Support</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Get help & support
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('farmer.marketplace.my-listings') }}" class="text-decoration-none">
                        <div class="card module-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                        <i class="ri-list-check text-primary"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">My Listings</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    Manage products
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('farmer.resources.track') }}" class="text-decoration-none">
                        <div class="card module-card border h-100">
                            <div class="card-body text-center">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                        <i class="ri-file-check-line text-success"></i>
                                    </span>
                                </div>
                                <h6 class="mb-2 text-dark">Track Applications</h6>
                                <p class="text-muted mb-0 font-size-13">
                                    View resource status
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Farm Statistics -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> Farm Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-xl-3 col-md-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-success">{{ $farmer->farmLands->count() }}</h4>
                            <p class="text-muted mb-0">Total Farms</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-info">{{ number_format($farmer->getTotalFarmSizeAttribute(), 2) }}</h4>
                            <p class="text-muted mb-0">Hectares</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-warning">{{ $farmer->cooperative ? '1' : '0' }}</h4>
                            <p class="text-muted mb-0">Cooperatives</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="py-3">
                            <h4 class="mb-1 text-primary">{{ $farmer->lga->name }}</h4>
                            <p class="text-muted mb-0">LGA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.module-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.module-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    border-color: #556ee6 !important;
}

.card-animate {
    transition: all 0.3s ease;
}

.card-animate:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.user-profile-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
}
</style>
@endpush