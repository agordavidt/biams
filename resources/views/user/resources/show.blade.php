@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $resource->name }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.resources.index') }}">Resources</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <h5 class="card-title mb-3">Description</h5>
                    <p class="text-muted">{{ $resource->description }}</p>
                </div>

                <div class="mb-4">
                    <h5 class="card-title mb-3">Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                            <i class="ri-file-list-3-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Type</p>
                                    <h5 class="font-size-14">{{ ucfirst($resource->target_practice) }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                            <i class="ri-money-naira-circle-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Cost</p>
                                    <h5 class="font-size-14">
                                        @if($resource->requires_payment)
                                            ₦{{ number_format($resource->price, 2) }}
                                        @else
                                            Free
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($existingApplication)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="ri-alert-line me-3 align-middle"></i>
                        You have already applied for this resource.
                        <a href="{{ route('user.resources.track') }}" class="alert-link">Track your application</a>
                    </div>
                @else
                    <div class="mt-4">
                        <a href="{{ route('user.resources.apply', $resource) }}" 
                           class="btn btn-primary waves-effect waves-light">
                            Apply Now
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection