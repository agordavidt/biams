@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Available Resources</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resources</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    @forelse($resources as $resource)
        <div class="col-md-6 col-lg-4">
            <div class="card resource-card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">{{ $resource->name }}</h5>
                        <span class="badge bg-{{ $resource->target_practice === 'all' ? 'primary' : 'info' }}">
                            {{ str_replace('-', ' ', $resource->target_practice) }}
                        </span>
                    </div>
                    
                    <p class="card-text text-muted mb-4 flex-grow-1">
                        {{ Str::limit($resource->description, 120) }}
                    </p>

                    @if($resource->requires_payment)
                        <div class="mb-3">
                            <span class="fw-semibold">
                                <i class="ri-money-naira-circle-line me-1"></i>
                                ₦{{ number_format($resource->price, 2) }}
                            </span>
                        </div>
                    @endif

                    @php
                        $application = $resource->applications->where('user_id', auth()->id())->first();
                    @endphp

                    <div class="mt-auto">
                        @if($application)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill font-size-12 px-3 py-2
                                    @if($application->status === 'approved') bg-success
                                    @elseif($application->status === 'rejected') bg-danger
                                    @else bg-warning @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                                <a href="{{ route('user.resources.track') }}" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="ri-history-line me-1"></i> Track
                                </a>
                            </div>
                        @else
                            <a href="{{ route('user.resources.apply', $resource) }}" 
                               class="btn btn-primary w-100 waves-effect waves-light">
                                <i class="ri-edit-box-line me-1"></i> Apply Now
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-light text-primary rounded-circle">
                            <i class="ri-file-search-line fs-2"></i>
                        </div>
                    </div>
                    <h5>No Resources Available</h5>
                    <p class="text-muted">Check back later for new resources</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<style>
    .resource-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .resource-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection