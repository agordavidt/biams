@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Assigned Resources</h4>
                <p class="text-muted">View and manage resources assigned to you for distribution</p>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($resources as $resource)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $resource->name }}</h5>
                        <p class="text-muted mb-3">{{ Str::limit($resource->description, 100) }}</p>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <small class="text-muted">Paid Applications</small>
                                <h4 class="mb-0 text-success">{{ $resource->paid_count }}</h4>
                            </div>
                            <div>
                                <small class="text-muted">Fulfilled</small>
                                <h4 class="mb-0 text-info">{{ $resource->fulfilled_count }}</h4>
                            </div>
                        </div>
                        
                        <!-- <a href="{{ route('vendor.distribution.resource-applications', $resource) }}" 
                           class="btn btn-primary w-100">
                            <i class="ri-list-check me-1"></i> View Applications
                        </a> -->
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    No active resources found.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection