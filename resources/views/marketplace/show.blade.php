@extends('layouts.frontend')

@section('title', $listing->title . ' - Marketplace')

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
                                    <li><a href="{{ route('marketplace.index') }}"><span>Marketplace</span></a></li>
                                    <li><span>{{ Str::limit($listing->title, 40) }}</span></li>
                                </ul>
                            </nav> 
                        </nav>
                    </div>
                    <h3 class="breadcrumb-title breadcrumb-title-sd mt-20">Product Details</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- page__title -end -->

<!-- product-details-area-start -->
<div class="product-details-area pt-80 pb-60">
    <div class="container">
        <div class="row">
            
            <!-- Product Images -->
            <div class="col-xl-5 col-lg-6">
                <div class="product-details-img mb-30">
                    <!-- Main Image -->
                    <div class="product-main mb-20">
                        <div class="product-content">
                            <div class="product-media">
                                <div class="product-image">
                                    <img id="mainProductImage" class="active" 
                                         src="{{ asset('storage/' . $listing->primary_image_path) }}" 
                                         alt="{{ $listing->title }}"
                                         style="width: 100%; border-radius: 8px;"
                                         onerror="this.src='{{ asset('frontend/assets/img/product/placeholder.jpg') }}'">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnail Gallery -->
                    @if($images->count() > 1)
                    <div class="product-thumb">
                        <div class="row g-2">
                            @foreach($images as $image)
                            <div class="col-3">
                                <div class="thumb-item">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         alt="{{ $listing->title }}"
                                         onclick="changeMainImage(this.src)"
                                         style="cursor: pointer; border-radius: 4px; width: 100%; height: 80px; object-fit: cover;"
                                         onerror="this.src='{{ asset('frontend/assets/img/product/placeholder.jpg') }}'">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Information -->
            <div class="col-xl-7 col-lg-6">
                <div class="product-side-info">
                    
                    <!-- Category Badge -->
                    <div class="producttop-info mb-3">
                        <span class="badge" style="background-color: #38761D; color: white; font-size: 0.9rem; padding: 6px 15px;">
                            {{ $listing->category->name }}
                        </span>
                    </div>

                    <!-- Product Title -->
                    <h4 class="product-site-title mb-3">{{ $listing->title }}</h4>

                    <!-- Price -->
                    <h5 class="product-dt-price mb-4" style="color: #38761D; font-weight: bold;">
                        ₦{{ number_format($listing->price, 2) }} <span style="font-size: 0.9rem; color: #666;">per {{ $listing->unit }}</span>
                    </h5>

                    <!-- Product Meta -->
                    <div class="quick-info mb-4">
                        <ul class="list-unstyled">
                            @if($listing->quantity)
                            <li class="mb-2">
                                <i class="fal fa-box text-success me-2"></i>
                                <strong>Available:</strong> {{ $listing->quantity }} {{ $listing->unit }}
                            </li>
                            @endif
                            <li class="mb-2">
                                <i class="fal fa-map-marker-alt text-success me-2"></i>
                                <strong>Location:</strong> {{ $listing->location }} LGA
                            </li>
                            <li class="mb-2">
                                <i class="fal fa-calendar text-success me-2"></i>
                                <strong>Listed:</strong> {{ $listing->created_at->diffForHumans() }}
                            </li>
                            @if($listing->expires_at)
                            <li class="mb-2">
                                <i class="fal fa-clock text-success me-2"></i>
                                <strong>Valid Until:</strong> {{ $listing->expires_at->format('d M, Y') }}
                                @if($listing->days_remaining <= 7)
                                    <span class="badge bg-warning text-dark ms-2">{{ $listing->days_remaining }} days left</span>
                                @endif
                            </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Description -->
                    <div class="description mb-4">
                        <h6 class="mb-2">Description:</h6>
                        <p style="line-height: 1.7; color: #555;">{{ $listing->description }}</p>
                    </div>

                    <!-- Seller Information -->
                    <div class="seller-info-box mb-4 p-3" style="background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #38761D;">
                        <h6 class="mb-2"><i class="fal fa-user-circle me-2"></i>Seller Information</h6>
                        <p class="mb-1"><strong>{{ $seller->name }}</strong></p>
                        @if($seller->farmerProfile)
                            <span class="badge bg-success mt-1">
                                <i class="fal fa-check-circle me-1"></i>Verified Farmer
                            </span>
                        @endif
                    </div>

                    <!-- Contact Button -->
                    <div class="quantity-field position-relative">
                        <div class="cart-button w-100">
                            <button type="button" class="tp-btn-h1 w-100" data-bs-toggle="modal" data-bs-target="#contactModal">
                                <i class="fal fa-phone me-2"></i>Contact Farmer
                            </button>
                        </div>
                        <p class="text-muted text-center mt-3 small">
                            <i class="fal fa-shield-check me-1"></i>
                            Direct communication between buyer and seller
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- product-details-area-end -->

<!-- Related Products -->
@if($relatedListings->count() > 0)
<div class="related-product pt-30 pb-60">
    <div class="container">
        <h5 class="related-product-title mb-40">Related Products</h5>
        <div class="row">
            @foreach($relatedListings as $related)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="product-item mb-30">
                    <div class="product-thumb gray-bg">
                        <a href="{{ route('marketplace.show', $related) }}">
                            <img src="{{ asset('storage/' . $related->primary_image_path) }}" 
                                 alt="{{ $related->title }}"
                                 onerror="this.src='{{ asset('frontend/assets/img/product/placeholder.jpg') }}'">
                        </a>
                    </div>
                    <div class="product__content mt-20">
                        <div class="rating-area mb-2">
                            <span class="category-badge">{{ $related->category->name }}</span>
                        </div>
                        <div class="product-wrapper">
                            <h3 class="product__title">
                                <a href="{{ route('marketplace.show', $related) }}">
                                    {{ Str::limit($related->title, 35) }}
                                </a>
                            </h3>
                            <span class="woo-price">₦{{ number_format($related->price, 2) }}/{{ $related->unit }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<!-- related-product-end -->

<!-- Contact Farmer Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="background-color: #38761D; color: white; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="contactModalLabel">
                    <i class="fal fa-phone-alt me-2"></i>Contact Farmer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('marketplace.contact-farmer', $listing) }}">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Provide your contact details. The farmer will reach out to you directly.</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="buyer_name" class="form-control" required placeholder="Enter your name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="buyer_phone" class="form-control" required placeholder="08012345678">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address (Optional)</label>
                        <input type="email" name="buyer_email" class="form-control" placeholder="your@email.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="inquiry_message" class="form-control" rows="4" required 
                                  placeholder="I'm interested in this product..."></textarea>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fal fa-info-circle me-2"></i>
                        Your information will only be shared with the seller.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="tp-btn-h1">
                        <i class="fal fa-paper-plane me-2"></i>Send Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function changeMainImage(src) {
    document.getElementById('mainProductImage').src = src;
}
</script>
@endpush

@push('styles')
<style>
    .product-main img {
        max-height: 450px;
        object-fit: cover;
        width: 100%;
    }
    
    .thumb-item img {
        border: 2px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .thumb-item img:hover {
        border-color: #38761D;
        transform: scale(1.05);
    }
    
    .seller-info-box {
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .product-item {
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .product-item:hover {
        box-shadow: 0 8px 20px rgba(56, 118, 29, 0.15);
        transform: translateY(-3px);
    }
    
    .product-thumb {
        height: 180px;
        overflow: hidden;
    }
    
    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .category-badge {
        background-color: #38761D;
        color: white;
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
    
    .product__title {
        font-size: 0.95rem;
        line-height: 1.4;
        margin-bottom: 8px;
    }
    
    .woo-price {
        color: #38761D;
        font-weight: bold;
        font-size: 1rem;
    }
    
    @media (max-width: 768px) {
        .product-main img {
            max-height: 300px;
        }
        
        .thumb-item img {
            height: 60px;
        }
    }
</style>
@endpush