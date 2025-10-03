@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-2">Welcome, {{ $farmer->full_name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $farmer->ward }}, {{ $farmer->lga->name }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-md">
                            @if($farmer->farmer_photo)
                                <img src="{{ asset('storage/' . $farmer->farmer_photo) }}" 
                                     alt="Profile Photo" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <span class="avatar-title bg-soft-primary rounded-circle fs-3 text-primary">
                                    {{ strtoupper(substr($farmer->full_name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Farmer Status</h5>
                        <span class="badge rounded-pill font-size-12 px-3 py-2 bg-success">
                            <i class="fas fa-check-circle align-middle font-size-18 text-success"></i> Active
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Farm Summary</h4>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tractor text-success me-2"></i>
                            <span>Farm Lands</span>
                        </div>
                        <span class="badge bg-success rounded-pill">{{ $farmer->farmLands->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-ruler-combined text-info me-2"></i>
                            <span>Total Size</span>
                        </div>
                        <span class="badge bg-info rounded-pill">{{ number_format($farmer->getTotalFarmSizeAttribute(), 2) }} ha</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users text-warning me-2"></i>
                            <span>Cooperative</span>
                        </div>
                        <span class="badge bg-warning rounded-pill">{{ $farmer->cooperative ? 'Joined' : 'None' }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            <span>Member Since</span>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $farmer->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0 fw-bold">Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('farmer.profile') }}" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-user-edit fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Update Profile</h5>
                            <p class="card-text text-muted small">Manage your personal information</p>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('farmer.marketplace') }}" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-store fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Marketplace</h5>
                            <p class="card-text text-muted small">Buy and sell farm products</p>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('farmer.resources') }}" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-seedling fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Resources</h5>
                            <p class="card-text text-muted small">Agricultural resources & support</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-shadow:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endpush