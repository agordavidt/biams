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
                                <i class="fas fa-hourglass-half align-middle font-size-18 text-warning"></i> Pending
                            @else
                                <i class="fas fa-check-circle align-middle font-size-18 text-success"></i> Onboarded
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Agricultural Practices</h4>
            </div>
            <div class="card-body">
                @if($registrations->count() > 0)
                    <div class="list-group">
                        @foreach($registrations as $registration)
                            <a href="{{ route('application.details', ['id' => $registration->id]) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                {{ $registration->type }}
                                <span class="badge bg-primary rounded-pill">View Details</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        No agricultural practice registrations yet.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        @if (auth()->user()->status === 'onboarded')
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Select Your Agricultural Practice</h4> 
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('farmers.crop') }}" class="card card-body text-center hover-effect  border-outline-secondary">
                                <div class="mb-3">
                                    <i class="fas fa-seedling fa-3x icon-green"></i>
                                </div>
                                <h5 class="card-title mb-1">Crop Farming</h5>
                                <p class="card-text text-muted small">Register as a crop farmer</p>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ route('farmers.animal') }}" class="card card-body text-center hover-effect">
                                <div class="mb-3">
                                <i class="fas fa-horse fa-3x icon-green"></i>  
                                </div>
                                <h5 class="card-title mb-1">Animal Farming</h5>
                                <p class="card-text text-muted small">All types of animal husbandry</p>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ route('farmers.processor') }}" class="card card-body text-center hover-effect">
                                <div class="mb-3">
                                    <i class="fas fa-industry fa-3x icon-green"></i>
                                </div>
                                <h5 class="card-title mb-1">Processing</h5>
                                <p class="card-text text-muted small">Register as an agricultural processor</p>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ route('farmers.abattoir') }}" class="card card-body text-center hover-effect">
                                <div class="mb-3">
                                    <i class="fas fa-warehouse fa-3x icon-green"></i>
                                </div>
                                <h5 class="card-title mb-1">Abattoir</h5>
                                <p class="card-text text-muted small">Register as an abattoir operator</p>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="#" class="card card-body text-center hover-effect">
                                <div class="mb-3">
                                    <i class="fas fa-tractor fa-3x icon-green"></i>
                                </div>
                                <h5 class="card-title mb-1">Agricultural Services</h5>
                                <p class="card-text text-muted small">Agricultural support services</p>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="#" class="card card-body text-center hover-effect">
                                <div class="mb-3">
                                    <i class="fas fa-fish fa-3x icon-green"></i>
                                </div>
                                <h5 class="card-title mb-1">Aquaculture</h5>
                                <p class="card-text text-muted small">Fishing and aquatic farming</p>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="#" class="card card-body text-center hover-effect">
                                <div class="mb-3">
                                    <i class="fas fa-tree fa-3x icon-green"></i>
                                </div>
                                <h5 class="card-title mb-1">Agroforestry</h5>
                                <p class="card-text text-muted small">Sustainable forest management</p>
                            </a>
                        </div>

                        <!-- <div class="col-md-4">
                            <a href="#" class="card card-body text-center hover-effect">
                                <div class="mb-3">
                                    <i class="fas fa-horse fa-3x icon-green"></i>  </div>
                                <h5 class="card-title mb-1">Animal Farming</h5>
                                <p class="card-text text-muted small">All types of animal husbandry</p>
                            </a>
                        </div> -->
                        
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h4>Onboarding Required</h4>
                        <p>You must complete your onboarding process to access agricultural practice registration forms.</p>
                        <p>Please contact the administrator or complete your profile to proceed.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <ul>                          
    <form action="{{ route('logout') }}" method="POST" id="logout-form"> 
            @csrf 
            <li> 
                <a class="text-danger" href="#" id="logout-link"> <i class="ri-shut-down-line align-middle me-1 text-danger"></i> <span>Logout</span> </a> 
            </li> 
        </form>
    </ul>
</div>
@endsection

@push('styles')
    <style>
            .hover-effect {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .hover-effect:hover {
                transform: translateY(-10px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }


            .card {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slightly darker shadow */
                transition: transform 0.2s, box-shadow 0.2s; /* Smooth transitions */
                border: none; /* remove default border */
            }

            .card:hover {
                transform: translateY(-5px); /* Move up slightly */
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* More prominent shadow */
            }

            .card-body {
                padding: 20px; /* Adjust padding as needed */
            }

            .card-title {
                font-weight: 500; /* Slightly bolder title */
            }

            .card-text {
                color: #777; /* Slightly darker muted text */
            }

            .hover-effect:hover {
                cursor: pointer; /* indicate clickable */
            }

            .icon-green {
                color: #28a745 !important; /* Green color, override existing styles */
            }

    </style>
@endpush
