@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Listing Details</h4>
            <div class="page-title-right">
                <a href="{{ route('marketplace.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Listings
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
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
                    <div class="col-md-5">
                        @if($listing->image)
                            <img src="{{ Storage::url($listing->image) }}" alt="{{ $listing->title }}" class="img-fluid rounded">
                        @else
                            <div class="bg-light d-flex justify-content-center align-items-center rounded" style="height: 300px;">
                                <i class="ri-image-line ri-5x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <h3 class="mb-3">{{ $listing->title }}</h3>
                        
                        <div class="mb-3">
                            <span class="badge bg-primary">{{ $listing->category->name }}</span>
                            <span class="text-muted ms-2">
                                <i class="ri-map-pin-line"></i> {{ $listing->location }}
                            </span>
                        </div>
                        
                        <div class="d-flex align-items-baseline mb-3">
                            <h2 class="mb-0 me-2">₦{{ number_format($listing->price, 2) }}</h2>
                            @if($listing->unit)
                                <span class="text-muted">per {{ $listing->unit }}</span>
                            @endif
                        </div>
                        
                        @if($listing->quantity)
                            <p><strong>Quantity Available:</strong> {{ $listing->quantity }} {{ $listing->unit }}</p>
                        @endif
                        
                        <p class="text-muted">
                            <strong>Listed on:</strong> {{ $listing->created_at->format('M d, Y') }} 
                            <span class="ms-3">
                                <strong>Expires on:</strong> {{ $listing->expires_at->format('M d, Y') }}
                            </span>
                        </p>
                        
                        @if($listing->user_id !== auth()->id())
                            <div class="d-grid gap-2 d-md-flex mt-4">
                                <a href="{{ route('marketplace.messages.conversation', $listing) }}" class="btn btn-primary">
                                    <i class="ri-message-3-line me-1"></i> Contact Seller
                                </a>
                            </div>
                        @else
                            <div class="d-grid gap-2 d-md-flex mt-4">
                                <a href="{{ route('marketplace.edit', $listing) }}" class="btn btn-primary">
                                    <i class="ri-edit-2-line me-1"></i> Edit Listing
                                </a>
                                <a href="{{ route('marketplace.messages.conversation', $listing) }}" class="btn btn-info">
                                    <i class="ri-message-3-line me-1"></i> View Messages
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteListingModal">
                                    <i class="ri-delete-bin-line me-1"></i> Delete
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4">
                    <h4>Description</h4>
                    <div class="border-top pt-3">
                        {!! nl2br(e($listing->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Seller Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm me-3">
                        <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                            <i class="ri-user-3-line"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $seller->name }}</h5>
                        <p class="text-muted mb-0">
                            <i class="ri-map-pin-line"></i> {{ $seller->profile->location ?? 'Location not specified' }}
                        </p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="row">
                        <div class="col-6 border-end">
                            <div class="p-2">
                                <h5 class="mb-1">{{ $seller->marketplaceListings()->count() }}</h5>
                                <p class="text-muted mb-0">Listings</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2">
                                <h5 class="mb-1">{{ $seller->created_at->diffForHumans(null, true) }}</h5>
                                <p class="text-muted mb-0">Member For</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($listing->user_id !== auth()->id())
                    <div class="d-grid mt-3">
                        <a href="{{ route('marketplace.messages.conversation', $listing) }}" class="btn btn-outline-primary">
                            <i class="ri-message-3-line me-1"></i> Contact Seller
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Similar Listings</h5>
            </div>
            <div class="card-body">
                <!-- This would be populated by similar listings in the same category -->
                <p class="text-center text-muted">No similar listings found.</p>
            </div>
        </div>
    </div>
</div>

<!-- Delete Listing Modal -->
@if($listing->user_id === auth()->id())
    <div class="modal fade" id="deleteListingModal" tabindex="-1" aria-labelledby="deleteListingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteListingModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this listing? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('marketplace.destroy', $listing) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Listing</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection