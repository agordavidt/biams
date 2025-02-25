@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Agricultural Marketplace</h4>
            <div class="page-title-right">
                <a href="{{ route('marketplace.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-middle me-1"></i> Create Listing
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('marketplace.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="min_price" class="form-label">Min Price</label>
                            <input type="number" class="form-control" id="min_price" name="min_price" value="{{ request('min_price') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="max_price" class="form-label">Max Price</label>
                            <input type="number" class="form-control" id="max_price" name="max_price" value="{{ request('max_price') }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ request('location') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="search" class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    @forelse($listings as $listing)
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="position-relative">
                                    @if($listing->image)
                                        <img src="{{ Storage::url($listing->image) }}" class="card-img-top" alt="{{ $listing->title }}" style="height: 180px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex justify-content-center align-items-center" style="height: 180px;">
                                            <i class="ri-image-line ri-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <span class="badge bg-primary">{{ $listing->category->name }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-truncate">{{ $listing->title }}</h5>
                                    <p class="card-text text-muted mb-1">
                                        <i class="ri-map-pin-line"></i> {{ $listing->location }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fs-5 fw-bold">₦{{ number_format($listing->price, 2) }}</span>
                                        @if($listing->unit)
                                            <span class="text-muted">per {{ $listing->unit }}</span>
                                        @endif
                                    </div>
                                    <div class="d-grid">
                                        <a href="{{ route('marketplace.show', $listing) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                                    </div>
                                </div>
                                <div class="card-footer text-muted small">
                                    <div class="d-flex justify-content-between">
                                        <span>Listed by: {{ $listing->user->name }}</span>
                                        <span>{{ $listing->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                No listings found. Please adjust your search criteria or check back later.
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $listings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection