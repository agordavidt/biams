

<div class="row">
                            <div class="col-xl-3">
                                
                            </div>
        
                            <div class="col-xl-3">  
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Crop Farming</h4>
                                        <div>
                                            <a class="popup-form btn btn-primary" href="#test-form">Register</a>
                                        </div>

                                        <div class="card mfp-hide mfp-popup-form mx-auto" id="test-form">
                                            <div class="card-body">
                                                <h4 class="mb-4">Registration for crop farming practice</h4>   
                                                <form>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="name">Name</label>
                                                                <input type="text" class="form-control" id="name" placeholder="Enter Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="email">Email</label>
                                                                <input type="email" class="form-control" id="email" placeholder="Enter Email">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="password">Password</label>
                                                                <input type="password" class="form-control" id="password" placeholder="Enter Password">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="subject">Subject</label>
                                                                <textarea class="form-control" id="subject" rows="3"></textarea>
                                                            </div>  
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="text-end mt-3">
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> <!-- end col -->
</div> <!-- end row -->
<!--======================================================================= -->

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
Copy    @if (session('success'))
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
                    @if ($user->status === 'onboarded')
                        <span class="badge bg-success">Onboarded</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
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
    @if ($user->status === 'onboarded')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Select Your Agricultural Practice</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $practices = [
                            ['route' => 'farmers.crop', 'icon' => 'seedling', 'title' => 'Crop Farming', 'description' => 'Register as a crop farmer'],
                            ['route' => 'farmers.animal', 'icon' => 'cow', 'title' => 'Animal Farming', 'description' => 'Register as an animal farmer'],
                            ['route' => 'farmers.processor', 'icon' => 'industry', 'title' => 'Processing & Value Addition', 'description' => 'Register as an agricultural processor'],
                            ['route' => 'farmers.abattoir', 'icon' => 'warehouse', 'title' => 'Abattoir', 'description' => 'Register as an abattoir operator'],
                            ['route' => '#', 'icon' => 'tractor', 'title' => 'Agricultural Services', 'description' => 'Agricultural support services'],
                            ['route' => '#', 'icon' => 'fish', 'title' => 'Aquaculture and Fisheries', 'description' => 'Fishing and aquatic farming'],
                            ['route' => '#', 'icon' => 'tree', 'title' => 'Agroforestry and Forestry', 'description' => 'Sustainable forest and agriculture management']
                        ]
                    @endphp

                    @foreach($practices as $practice)
                        <div class="col-md-4">
                            <a href="{{ route($practice['route']) }}" class="card card-body text-center hover-effect">
                                <div class="mb-3">
                                    <i class="fas fa-{{ $practice['icon'] }} fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title mb-1">{{ $practice['title'] }}</h5>
                                <p class="card-text text-muted small">{{ $practice['description'] }}</p>
                            </a>
                        </div>
                    @endforeach
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
</style>
@endpush


               