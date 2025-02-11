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
    @foreach($resources as $resource)
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ $resource->name }}</h5>
                    <p class="card-text text-muted mb-4">{{ Str::limit($resource->description, 100) }}</p>

                    @php
                        $application = $applications->where('resource_id', $resource->id)->first();
                    @endphp

                    @if($application)
                        <div class="mb-3">
                            <span class="badge rounded-pill font-size-12 px-3 py-2
                                @if($application->status === 'approved') bg-success
                                @elseif($application->status === 'rejected') bg-danger
                                @else bg-warning
                                @endif">
                                Status: {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="font-size-14">
                            @if($resource->requires_payment)
                                <i class="ri-money-naira-circle-line me-1"></i>
                                {{ number_format($resource->price, 2) }}
                            @else
                                <span class="text-success">
                                    <i class="ri-checkbox-circle-line me-1"></i> Free
                                </span>
                            @endif
                        </span>

                        @if(!$application)
                            <a href="{{ route('user.resources.show', $resource) }}" 
                               class="btn btn-primary btn-sm waves-effect waves-light">
                                View Details
                            </a>
                        @else
                            <a href="{{ route('user.resources.track') }}" 
                               class="btn btn-info btn-sm waves-effect waves-light">
                                Track Application
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection