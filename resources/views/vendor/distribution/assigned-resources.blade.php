
@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">My Resources</h4>
                    <p class="text-muted mb-0">Resources available for distribution</p>
                </div>
                <div>
                    <a href="{{ route('vendor.distribution.search') }}" class="btn btn-primary me-2">
                        <i class="ri-search-line me-1"></i> Search Farmers
                    </a>
                    <a href="{{ route('vendor.distribution.dashboard') }}" class="btn btn-light">
                        <i class="ri-dashboard-line me-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Access Status Banner --}}
    <div class="row mb-3">
        <div class="col-12">
            @if($hasAssignments)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-lock-line font-size-24"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">Restricted Access Mode</h5>
                            <p class="mb-0">
                                You have been assigned <strong>{{ $resources->count() }} specific resource(s)</strong> by your vendor manager. 
                                You can only view and fulfill applications for your assigned resources below.
                            </p>
                            <hr class="my-2">
                            <p class="mb-0">
                                <small class="text-muted">
                                    <i class="ri-information-line me-1"></i>
                                    Need access to additional resources? Contact your vendor manager.
                                </small>
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @else
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-global-line font-size-24"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">Full Access Mode</h5>
                            <p class="mb-0">
                                You have full access to <strong>all {{ $resources->count() }} vendor resource(s)</strong>. 
                                You can view and fulfill applications for any resource below.
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Resources Grid --}}
    <div class="row">
        @forelse($resources as $resource)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 {{ $hasAssignments ? 'border-warning' : '' }}">
                    @if($hasAssignments)
                        <div class="card-header bg-warning text-dark d-flex align-items-center justify-content-between">
                            <span class="badge bg-white text-warning">
                                <i class="ri-shield-check-line me-1"></i>Assigned to You
                            </span>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $resource->name }}</h5>
                                <span class="badge bg-info">{{ ucfirst($resource->type) }}</span>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-soft-primary text-primary rounded">
                                    <i class="ri-box-3-line font-size-20"></i>
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-muted mb-3">{{ Str::limit($resource->description, 80) }}</p>
                        
                        {{-- Statistics --}}
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="border rounded p-2 text-center">
                                    <h4 class="mb-0 text-warning">{{ $resource->paid_count }}</h4>
                                    <small class="text-muted">Paid</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2 text-center">
                                    <h4 class="mb-0 text-success">{{ $resource->fulfilled_count }}</h4>
                                    <small class="text-muted">Fulfilled</small>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Stock Info --}}
                        @if($resource->requires_quantity)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Available Stock</small>
                                    <small>
                                        <strong>{{ number_format($resource->available_stock) }}</strong> / {{ number_format($resource->total_stock) }} {{ $resource->unit }}
                                    </small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    @php
                                        $stockPercentage = $resource->total_stock > 0 
                                            ? ($resource->available_stock / $resource->total_stock) * 100 
                                            : 0;
                                        $stockColor = $stockPercentage > 50 ? 'success' : ($stockPercentage > 20 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="progress-bar bg-{{ $stockColor }}" 
                                         role="progressbar" 
                                         style="width: {{ $stockPercentage }}%">
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Price --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Price per {{ $resource->unit ?? 'unit' }}</span>
                                <h5 class="mb-0 text-primary">₦{{ number_format($resource->price, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-grid gap-2">
                            <a href="{{ route('vendor.distribution.resource-applications', $resource) }}" 
                               class="btn btn-primary">
                                <i class="ri-file-list-line me-1"></i> View Applications ({{ $resource->paid_count }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ri-inbox-line display-3 text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">No Resources Available</h5>
                        @if($hasAssignments)
                            <p class="text-muted mb-3">
                                You haven't been assigned any resources yet. Contact your vendor manager for assignments.
                            </p>
                        @else
                            <p class="text-muted mb-3">
                                No active resources found for your vendor.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Assignment Summary (if restricted) --}}
    @if($hasAssignments && $resources->isNotEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="ri-information-line me-2"></i>Your Resource Assignments
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">You are currently assigned to fulfill these resources:</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($resources as $resource)
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    <i class="ri-checkbox-circle-line me-1"></i>{{ $resource->name }}
                                </span>
                            @endforeach
                        </div>
                        <hr class="my-3">
                        <div class="alert alert-info mb-0">
                            <small>
                                <strong>Note:</strong> You can only search for and fulfill applications related to these resources. 
                                If you attempt to access other resources, you will receive an "Access Denied" message.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection