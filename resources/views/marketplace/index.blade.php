@extends('layouts.frontend')

@section('title', 'Agricultural Marketplace - Benue State')

@section('content')

<!-- page__title -start -->
<div class="page__title align-items-center theme-bg-primary-h1 pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page__title-content text-center">
                    <div class="page_title__bread-crumb">
                        <nav aria-label="breadcrumb">
                            <nav aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs">
                                <ul>
                                    <li><a href="{{ url('/') }}"><span>Home</span></a></li>
                                    <li><span>Marketplace</span></li>
                                </ul>
                            </nav> 
                        </nav>
                    </div>
                    <h3 class="breadcrumb-title breadcrumb-title-sd mt-20">Agricultural Marketplace</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- page__title -end -->

<!-- shop-area-start -->
<div class="shop-area pt-80 pb-60">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-xl-3 col-lg-4 mb-40">
                <div class="shop-sidebar">
                    <h4 class="sidebar-title mb-20">Filters</h4>
                    
                    <!-- Category Filter -->
                    <div class="sidebar-widget mb-30">
                        <h5 class="widget-title">Categories</h5>
                        <form method="GET" action="{{ route('marketplace.index') }}" id="categoryForm">
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            @if(request('location')) <input type="hidden" name="location" value="{{ request('location') }}"> @endif
                            @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                            @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                            <select name="category" id="categoryFilter" onchange="document.getElementById('categoryForm').submit()" class="nice-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->active_listings_count }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="sidebar-widget mb-30">
                        <h5 class="widget-title">Price Range (NGN)</h5>
                        <form method="GET" action="{{ route('marketplace.index') }}" id="priceForm">
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            @if(request('location')) <input type="hidden" name="location" value="{{ request('location') }}"> @endif
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="tp-btn-h1 w-100 mt-10">Apply</button>
                        </form>
                    </div>

                    <!-- Sort By -->
                    <div class="sidebar-widget mb-30">
                        <h5 class="widget-title">Sort By</h5>
                        <form method="GET" action="{{ route('marketplace.index') }}" id="sortForm">
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            @if(request('location')) <input type="hidden" name="location" value="{{ request('location') }}"> @endif
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                            @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                            <select name="sort" onchange="document.getElementById('sortForm').submit()" class="nice-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </form>
                    </div>

                    @if(request()->hasAny(['search', 'category', 'location', 'min_price', 'max_price', 'sort']))
                    <div class="mb-20">
                        <a href="{{ route('marketplace.index') }}" class="tp-btn-h1 w-100 text-center">
                            <i class="fal fa-redo me-2"></i>Clear All Filters
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-xl-9 col-lg-8">
                <div class="shop-content">
                    
                    <!-- Search Bar -->
                    <div class="side-search mb-30">
                        <form method="GET" action="{{ route('marketplace.index') }}">
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request('location')) <input type="hidden" name="location" value="{{ request('location') }}"> @endif
                            @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                            @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                            <button type="submit"><i class="fal fa-search"></i></button>
                            <input type="text" name="search" placeholder="Search produce, crops, or farmers..." value="{{ request('search') }}">
                        </form>
                    </div>

                    <!-- Results Info -->
                    <div class="product-meta mb-30 d-flex justify-content-between align-items-center">
                        <p class="mb-0">Showing {{ $listings->firstItem() ?? 0 }}–{{ $listings->lastItem() ?? 0 }} of {{ $listings->total() }} products</p>
                    </div>

                    <!-- Product Items Grid -->
                    <div class="product-items">
                        <div class="row">
                            @forelse($listings as $listing)
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-30">
                                <div class="product-item">
                                    <div class="product-thumb">
                                        <a href="{{ route('marketplace.show', $listing) }}">
                                            <img src="{{ $listing->images->first()?->image_path ? asset('storage/' . $listing->images->first()->image_path) : asset('frontend/assets/img/product/placeholder.jpg') }}" 
                                                 alt="{{ $listing->title }}"
                                                 onerror="this.src='{{ asset('frontend/assets/img/product/placeholder.jpg') }}'">
                                        </a>
                                        <span class="category-badge">{{ $listing->category->name }}</span>
                                    </div>
                                    <div class="product__content mt-20">
                                        <h3 class="product__title">
                                            <a href="{{ route('marketplace.show', $listing) }}">
                                                {{ Str::limit($listing->title, 50) }}
                                            </a>
                                        </h3>
                                        <div class="product-meta-info">
                                            <span class="price">₦{{ number_format($listing->price, 2) }} / {{ $listing->unit }}</span>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fal fa-inbox" style="font-size: 60px; color: #ccc;"></i>
                                    <h4 class="mt-4">No Products Found</h4>
                                    <p class="text-muted">Try adjusting your filters or search for something else.</p>
                                    <a href="{{ route('marketplace.index') }}" class="tp-btn-h1 mt-3">
                                        <i class="fal fa-list me-2"></i>Browse All
                                    </a>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($listings->hasPages())
                    <div class="pagination-area pt-30 mb-30">
                        {{ $listings->withQueryString()->links() }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!-- shop-area-end -->

@endsection

@push('styles')
<style>
    .shop-sidebar {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        height: 100vh;
    }
    
    .widget-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    
    .product-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-item:hover {
        box-shadow: 0 8px 25px rgba(56, 118, 29, 0.15);
        transform: translateY(-5px);
        border-color: #38761D;
    }
    
    .product-thumb {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .product-item:hover .product-thumb img {
        transform: scale(1.08);
    }
    
    .category-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #38761D;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        z-index: 2;
    }
    
    .product__content {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product__title {
        font-size: 1rem;
        margin-bottom: 10px;
        line-height: 1.4;
    }
    
    .product__title a {
        color: #333;
        text-decoration: none;
    }
    
    .product__title a:hover {
        color: #38761D;
    }
    
    .product-meta-info {
        margin-bottom: 10px;
    }
    
    .price {
        font-weight: bold;
        color: #38761D;
        font-size: 1.1rem;
        display: block;
    }
    
    .product-actions {
        margin-top: auto;
    }
    
    .product-actions .tp-btn-h1 {
        font-size: 0.85rem;
        padding: 8px 12px;
    }
    
    .side-search form {
        position: relative;
    }
    
    .side-search input {
        width: 100%;
        padding: 12px 45px 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
    }
    
    .side-search button i {       
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #38761D;
        font-size: 1.1rem;
    }
    
    .nice-select {
        width: 100%;
        height: 45px;
        line-height: 45px;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 0 15px;
    }
    
    .form-control {
        height: 40px;
        font-size: 0.9rem;
    }
    
    @media (max-width: 991px) {
        
        .shop-sidebar {
            order: -1;
            height: none;
        }
    }
    
    @media (max-width: 768px) {
        .product-thumb {
            height: 160px;
        }
        
        .price {
            font-size: 1rem;
        }
        
        .product__title {
            font-size: 0.95rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit forms on change can remain as is, or enhance with JS if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add loading state, etc.
    });
</script>
@endpush