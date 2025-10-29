@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Available Resources</h4>
            <p class="text-muted">Browse and apply for agricultural resources</p>
        </div>
    </div>
</div>

<!-- Simple Filter Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('farmer.resources.index') }}" 
                               class="btn btn-outline-primary btn-sm {{ !request('type') && !request('payment_type') ? 'active' : '' }}">
                                All Resources
                            </a>
                            <a href="{{ route('farmer.resources.index', ['payment_type' => 'free']) }}" 
                               class="btn btn-outline-success btn-sm {{ request('payment_type') == 'free' ? 'active' : '' }}">
                                Free Resources
                            </a>
                            <a href="{{ route('farmer.resources.index', ['payment_type' => 'paid']) }}" 
                               class="btn btn-outline-warning btn-sm {{ request('payment_type') == 'paid' ? 'active' : '' }}">
                                Paid Resources
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <form action="{{ route('farmer.resources.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" 
                                       name="search" value="{{ request('search') }}" 
                                       placeholder="Search resources...">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resources Grid -->
<div class="row">
    @forelse($resources as $resource)
        <div class="col-xl-4 col-lg-6">
            <div class="card resource-card">
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-{{ $resource->requires_payment ? 'warning' : 'success' }} mb-2">
                                {{ $resource->requires_payment ? 'Paid' : 'Free' }}
                            </span>
                            <h5 class="card-title mb-1">{{ $resource->name }}</h5>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-muted mb-3">
                        {{ Str::limit($resource->description, 120) }}
                    </p>

                    <!-- Key Info -->
                    <div class="resource-info mb-3">
                        <div class="d-flex justify-content-between text-sm mb-2">
                            <span class="text-muted">Type:</span>
                            <span class="fw-medium text-capitalize">
                                {{ str_replace('_', ' ', $resource->type) }}
                            </span>
                        </div>
                        
                        @if($resource->requires_quantity)
                        <div class="d-flex justify-content-between text-sm mb-2">
                            <span class="text-muted">Available:</span>
                            <span class="fw-medium">
                                {{ number_format($resource->available_stock) }} {{ $resource->unit }}
                            </span>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between text-sm">
                            <span class="text-muted">Provider:</span>
                            <span class="fw-medium">
                                {{ $resource->vendor ? $resource->vendor->legal_name : 'Ministry' }}
                            </span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="price-section mb-3 text-center">
                        @if($resource->requires_payment)
                            <div class="price-amount">
                                <span class="text-muted">Price:</span>
                                <h4 class="text-primary mb-0">₦{{ number_format($resource->price, 2) }}</h4>
                                <small class="text-muted">per {{ $resource->unit ?? 'unit' }}</small>
                            </div>
                        @else
                            <div class="free-badge">
                                <h5 class="text-success mb-0">
                                    <i class="ri-gift-line me-1"></i> Free
                                </h5>
                            </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    <div class="d-grid">
                        @if(in_array($resource->id, $userApplications))
                            <button class="btn btn-secondary" disabled>
                                <i class="ri-check-line me-1"></i> Applied
                            </button>
                        @else
                            <a href="{{ route('farmer.resources.show', $resource) }}" class="btn btn-primary">
                                <i class="ri-eye-line me-1"></i> View Details & Apply
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
                        <div class="avatar-title bg-soft-primary text-primary display-4 rounded-circle">
                            <i class="ri-plant-line"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">No Resources Found</h5>
                    <p class="text-muted mb-3">
                        @if(request()->hasAny(['search', 'type', 'payment_type']))
                            No resources match your search criteria.
                        @else
                            There are currently no active resources available.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'type', 'payment_type']))
                        <a href="{{ route('farmer.resources.index') }}" class="btn btn-primary">
                            Clear Filters
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Simple Pagination -->
@if($resources->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $resources->links() }}
            </div>
        </div>
    </div>
@endif

@endsection

@push('styles')
<style>
.resource-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #e9ecef;
}

.resource-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.resource-info {
    background-color: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
}

.price-amount {
    padding: 12px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 8px;
}

.free-badge {
    padding: 12px;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-radius: 8px;
}

.btn.active {
    background-color: #556ee6;
    border-color: #556ee6;
    color: white;
}
</style>
@endpush