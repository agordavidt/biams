@extends('layouts.new')
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
                        <h5 class="card-title mb-2">Welcome, {{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-md">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3 text-primary">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Profile Status</h5>
                        <span class="badge rounded-pill font-size-12 px-3 py-2 
                            @if (auth()->user()->status === 'pending')
                                bg-warning
                            @else
                                bg-success
                            @endif">
                            @if (auth()->user()->status === 'pending')
                                <i class="fas fa-hourglass-half align-middle font-size-18 text-warning"></i> Inactive
                            @else
                                <i class="fas fa-check-circle align-middle font-size-18 text-success"></i> Active
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Agricultural Practices</h4>
                @if($registrations->count() > 0)
                    <a href="{{ route('farmers.submissions') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                @endif
            </div>
            <div class="card-body">
                @if($registrations->count() > 0)
                    <div class="list-group">
                        @foreach($registrations->take(5) as $registration)
                            <a href="{{ route('farmers.submission.view', ['type' => $registration->type_slug, 'id' => $registration->id]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $registration->type }}</span>
                                        <span class="badge @switch($registration->status)
                                            @case('approved')
                                                bg-success
                                                @break
                                            @case('pending')
                                                bg-warning
                                                @break
                                            @case('rejected')
                                                bg-danger
                                                @break
                                            @default
                                                bg-secondary
                                        @endswitch">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </div>
                                    <small class="text-muted">Submitted {{ $registration->created_at->diffForHumans() }}</small>
                                </div>
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        @endforeach
                    </div>
                    @if($registrations->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('farmers.submissions') }}" class="btn btn-link">
                                View {{ $registrations->count() - 5 }} more submissions
                            </a>
                        </div>
                    @endif
                @else
                    <div class="alert alert-info text-center mb-0">
                        <i class="ri-information-line fs-4 d-block mb-2"></i>
                        No agricultural practice registrations yet.
                        <div class="mt-2">
                            <small class="text-muted">Get started by selecting a practice below</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-8">
    @if (auth()->user()->status === 'onboarded')
        <div class="card shadow-sm">
            <div class="card-header text-white">
                <h4 class="card-title mb-0 fw-bold">Select Your Agricultural Practice</h4> 
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('farmers.crop') }}" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-seedling fa-3x text-secondary"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Crop Farming</h5>
                            <p class="card-text text-muted small">Register as a crop farmer</p>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('farmers.animal') }}" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-horse fa-3x text-secondary"></i>  
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Animal Farming</h5>
                            <p class="card-text text-muted small">All types of animal husbandry</p>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('farmers.processor') }}" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-industry fa-3x text-secondary"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Processing</h5>
                            <p class="card-text text-muted small">Register as an agricultural processor</p>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="{{ route('farmers.abattoir') }}" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-warehouse fa-3x text-secondary"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Abattoir</h5>
                            <p class="card-text text-muted small">Register as an abattoir operator</p>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="#" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-tractor fa-3x text-secondary"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Agricultural Services</h5>
                            <p class="card-text text-muted small">Agricultural support services</p>
                        </a>
                    </div>

                    <div class="col-md-4">
                        <a href="#" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-fish fa-3x text-secondary"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Aquaculture</h5>
                            <p class="card-text text-muted small">Fishing and aquatic farming</p>
                        </a>
                    </div>

                    <!-- <div class="col-md-4">
                        <a href="#" class="card card-body text-center h-100 text-decoration-none shadow-sm hover-shadow">
                            <div class="mb-3">
                                <i class="fas fa-tree fa-3x text-secondary"></i>
                            </div>
                            <h5 class="card-title mb-1 fw-semibold">Agroforestry</h5>
                            <p class="card-text text-muted small">Sustainable forest management</p>
                        </a>
                    </div> -->
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                    <h4 class="fw-bold">Activation Required</h4>
                    <p>Your account must be activated before yu to access agricultural practice registration forms.</p>
                    <p>Please contact the administrator or complete your profile to proceed.</p>
                </div>
            </div>
        </div>
    @endif
</div>
    
</div>
@endsection