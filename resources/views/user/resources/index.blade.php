@extends('layouts.new')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="m-0">Available Resources</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resources</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    @forelse($resources as $resource)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column p-4">
                    <!-- Header -->
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="card-title">{{ $resource->name }}</h5>
                        <span class="badge bg-{{ $resource->target_practice === 'all' ? 'primary' : 'info' }}">
                            {{ ucfirst(str_replace('-', ' ', $resource->target_practice)) }}
                        </span>
                    </div>
                    
                    <!-- Description -->
                    <p class="card-text text-muted mb-3 flex-grow-1">
                        {{ Str::limit($resource->description, 120) }}
                    </p>

                    <!-- Price (if applicable) -->
                    @if($resource->requires_payment)
                        <div class="mb-3">
                            <span class="fw-semibold">
                                <i class="ri-money-naira-circle-line me-1"></i>
                                ₦{{ number_format($resource->price, 2) }}
                            </span>
                        </div>
                    @endif

                    <!-- Application Status or Apply Button -->
                    @php
                        $application = $resource->applications->where('user_id', auth()->id())->first();
                    @endphp

                    <div class="mt-2">
                        @if($application)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill px-3 py-2
                                    @if($application->status === 'approved') bg-success
                                    @elseif($application->status === 'rejected') bg-danger
                                    @else bg-warning @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                                <a href="{{ route('user.resources.track') }}" 
                                   class="btn btn-sm btn-outline-info">
                                    Track Application
                                </a>
                            </div>
                        @else
                            <a href="{{ route('user.resources.apply', $resource) }}" 
                               class="btn btn-primary w-100">
                                Apply Now
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="avatar-md mx-auto mb-3 bg-light text-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-file-search-line fs-2"></i>
                    </div>
                    <h5>No Resources Available</h5>
                    <p class="text-muted">Check back later for new resources</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 0.5rem;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.08) !important;
    }
</style>
@endsection