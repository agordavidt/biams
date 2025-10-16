@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Marketplace Listings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.marketplace.dashboard') }}">Marketplace</a></li>
                    <li class="breadcrumb-item active">Listings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Status Overview Cards -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">All Listings</p>
                        <h2 class="mt-4 ff-secondary fw-semibold">{{ $statusCounts['all'] }}</h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                <i class="ri-shopping-bag-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Pending Review</p>
                        <h2 class="mt-4 ff-secondary fw-semibold text-warning">{{ $statusCounts['pending_review'] }}</h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-2">
                                <i class="ri-time-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Active</p>
                        <h2 class="mt-4 ff-secondary fw-semibold text-success">{{ $statusCounts['active'] }}</h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded-circle fs-2">
                                <i class="ri-checkbox-circle-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="fw-medium text-muted mb-0">Expired</p>
                        <h2 class="mt-4 ff-secondary fw-semibold text-danger">{{ $statusCounts['expired'] }}</h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-danger rounded-circle fs-2">
                                <i class="ri-close-circle-line text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Filter Listings</h5>
                <form action="{{ route('admin.marketplace.listings') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Search title, description or farmer name" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-search-line me-1"></i> Apply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Listings Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">All Listings ({{ $listings->total() }})</h5>
                    @if(request()->hasAny(['search', 'status', 'category']))
                        <a href="{{ route('admin.marketplace.listings') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ri-restart-line me-1"></i> Clear Filters
                        </a>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Farmer</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listings as $listing)
                            <tr>
                                <td><strong>#{{ $listing->id }}</strong></td>
                                <td>
                                    @if($listing->primaryImage)
                                        <img src="{{ Storage::url($listing->primaryImage->thumbnail_path ?? $listing->primaryImage->image_path) }}" 
                                             alt="{{ $listing->title }}" 
                                             class="avatar-sm rounded"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-light text-muted rounded">
                                                <i class="ri-image-line"></i>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h6 class="mb-1">{{ Str::limit($listing->title, 40) }}</h6>
                                    <p class="text-muted mb-0 small">
                                        <i class="ri-eye-line me-1"></i>{{ $listing->view_count }} views
                                        <span class="mx-1">•</span>
                                        <i class="ri-question-line me-1"></i>{{ $listing->inquiries_count }} inquiries
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar-xs me-2">
                                            <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                {{ substr($listing->user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $listing->user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-info">{{ $listing->category->name }}</span>
                                </td>
                                <td>
                                    <strong>₦{{ number_format($listing->price, 2) }}</strong>
                                    @if($listing->unit)
                                        <small class="text-muted d-block">per {{ $listing->unit }}</small>
                                    @endif
                                </td>
                                <td>{{ $listing->location }}</td>
                                <td>
                                    @switch($listing->status)
                                        @case('active')
                                            <span class="badge badge-soft-success">
                                                <i class="ri-checkbox-circle-line me-1"></i>Active
                                            </span>
                                            @break
                                        @case('pending_review')
                                            <span class="badge badge-soft-warning">
                                                <i class="ri-time-line me-1"></i>Pending Review
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="badge badge-soft-danger">
                                                <i class="ri-close-circle-line me-1"></i>Rejected
                                            </span>
                                            @break
                                        @case('expired')
                                            <span class="badge badge-soft-secondary">
                                                <i class="ri-calendar-close-line me-1"></i>Expired
                                            </span>
                                            @break
                                        @case('sold_out')
                                            <span class="badge badge-soft-dark">
                                                <i class="ri-shopping-cart-line me-1"></i>Sold Out
                                            </span>
                                            @break
                                        @default
                                            <span class="badge badge-soft-secondary">{{ ucfirst($listing->status) }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <small class="text-muted">{{ $listing->created_at->format('d M, Y') }}</small>
                                    @if($listing->expires_at)
                                        <small class="d-block {{ $listing->is_expired ? 'text-danger' : 'text-muted' }}">
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ $listing->is_expired ? 'Expired' : 'Expires' }}: {{ $listing->expires_at->format('d M, Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- View Details -->
                                        <button type="button" 
                                                class="btn btn-sm btn-soft-info view-details-btn" 
                                                data-listing="{{ json_encode([
                                                    'id' => $listing->id,
                                                    'title' => $listing->title,
                                                    'description' => $listing->description,
                                                    'price' => $listing->price,
                                                    'unit' => $listing->unit,
                                                    'quantity' => $listing->quantity,
                                                    'location' => $listing->location,
                                                    'contact' => $listing->contact,
                                                    'category' => $listing->category->name,
                                                    'farmer' => $listing->user->name,
                                                    'farmer_email' => $listing->user->email,
                                                    'status' => $listing->status,
                                                    'created_at' => $listing->created_at->format('d M, Y h:i A'),
                                                    'expires_at' => $listing->expires_at ? $listing->expires_at->format('d M, Y') : null,
                                                    'approved_at' => $listing->approved_at ? $listing->approved_at->format('d M, Y h:i A') : null,
                                                    'approved_by' => $listing->approvedBy ? $listing->approvedBy->name : null,
                                                    'rejection_reason' => $listing->rejection_reason,
                                                    'view_count' => $listing->view_count,
                                                    'inquiry_count' => $listing->inquiry_count,
                                                    'image' => $listing->primaryImage ? Storage::url($listing->primaryImage->image_path) : null,
                                                    'images' => $listing->images->map(fn($img) => Storage::url($img->image_path))
                                                ]) }}"
                                                title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </button>

                                        <!-- Approve (Only for pending_review) -->
                                        @if($listing->status === 'pending_review')
                                            <form action="{{ route('admin.marketplace.approve', $listing) }}" 
                                                  method="POST" 
                                                  class="approve-form">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-soft-success" 
                                                        title="Approve Listing">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                </button>
                                            </form>

                                            <button type="button" 
                                                    class="btn btn-sm btn-soft-danger reject-btn" 
                                                    data-listing-id="{{ $listing->id }}"
                                                    data-listing-title="{{ $listing->title }}"
                                                    title="Reject Listing">
                                                <i class="ri-close-circle-line"></i>
                                            </button>
                                        @endif

                                        <!-- Delete -->
                                        <form action="{{ route('admin.marketplace.remove', $listing) }}" 
                                              method="POST" 
                                              class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-soft-danger" 
                                                    title="Delete Listing">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ri-inbox-line display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">No listings found</h5>
                                        <p class="text-muted">Try adjusting your filters</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($listings->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $listings->firstItem() }} to {{ $listings->lastItem() }} of {{ $listings->total() }} entries
                    </div>
                    <div>
                        {{ $listings->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Listing Details Modal -->
<div class="modal fade" id="listingDetailsModal" tabindex="-1" aria-labelledby="listingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listingDetailsModalLabel">Listing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Image Section -->
                    <div class="col-md-5 mb-3 mb-md-0">
                        <div id="listing-image-container">
                            <div id="listingImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner" id="carousel-images">
                                    <!-- Images will be populated here -->
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#listingImagesCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#listingImagesCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>
                            <p id="no-image-text" class="text-center text-muted mt-3 d-none">
                                <i class="ri-image-line fs-1 d-block mb-2"></i>
                                No image available
                            </p>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="col-md-7">
                        <h4 id="listing-title" class="mb-3"></h4>
                        
                        <div class="mb-3">
                            <span class="badge badge-soft-primary me-2" id="listing-category"></span>
                            <span class="badge" id="listing-status-badge"></span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold" style="width: 140px;">Price:</td>
                                        <td>
                                            <span id="listing-price" class="text-success fw-bold"></span>
                                            <span id="listing-unit"></span>
                                        </td>
                                    </tr>
                                    <tr id="quantity-row">
                                        <td class="fw-semibold">Quantity:</td>
                                        <td id="listing-quantity"></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Farmer:</td>
                                        <td>
                                            <div id="listing-farmer"></div>
                                            <small class="text-muted" id="listing-farmer-email"></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Location:</td>
                                        <td id="listing-location"></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Contact:</td>
                                        <td id="listing-contact"></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Posted:</td>
                                        <td id="listing-created"></td>
                                    </tr>
                                    <tr id="expires-row">
                                        <td class="fw-semibold">Expires:</td>
                                        <td id="listing-expires"></td>
                                    </tr>
                                    <tr id="approved-row">
                                        <td class="fw-semibold">Approved:</td>
                                        <td>
                                            <div id="listing-approved"></div>
                                            <small class="text-muted" id="listing-approved-by"></small>
                                        </td>
                                    </tr>
                                    <tr id="rejection-row" class="d-none">
                                        <td class="fw-semibold text-danger">Rejection Reason:</td>
                                        <td>
                                            <div class="alert alert-danger mb-0 py-2" id="listing-rejection-reason"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Engagement:</td>
                                        <td>
                                            <span id="listing-views"></span> views • 
                                            <span id="listing-inquiries"></span> inquiries
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <h6 class="mb-2">Description</h6>
                        <p id="listing-description" class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="rejection-form" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectionModalLabel">Reject Listing</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to reject: <strong id="reject-listing-title"></strong></p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="rejection_reason" 
                                  name="rejection_reason" 
                                  rows="4" 
                                  required 
                                  placeholder="Please provide a clear reason for rejection so the farmer can make necessary corrections..."></textarea>
                        <div class="form-text">This will be sent to the farmer</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-close-circle-line me-1"></i> Reject Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Details Modal
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const listing = JSON.parse(this.getAttribute('data-listing'));
            
            // Set basic details
            document.getElementById('listing-title').textContent = listing.title;
            document.getElementById('listing-description').textContent = listing.description;
            document.getElementById('listing-price').textContent = '₦' + parseFloat(listing.price).toLocaleString('en-NG', {minimumFractionDigits: 2});
            document.getElementById('listing-unit').textContent = listing.unit ? ' / ' + listing.unit : '';
            document.getElementById('listing-category').textContent = listing.category;
            document.getElementById('listing-farmer').textContent = listing.farmer;
            document.getElementById('listing-farmer-email').textContent = listing.farmer_email;
            document.getElementById('listing-location').textContent = listing.location;
            document.getElementById('listing-contact').textContent = listing.contact;
            document.getElementById('listing-created').textContent = listing.created_at;
            document.getElementById('listing-views').textContent = listing.view_count;
            document.getElementById('listing-inquiries').textContent = listing.inquiry_count;
            
            // Quantity
            if (listing.quantity) {
                document.getElementById('listing-quantity').textContent = listing.quantity;
                document.getElementById('quantity-row').classList.remove('d-none');
            } else {
                document.getElementById('quantity-row').classList.add('d-none');
            }
            
            // Expiry
            if (listing.expires_at) {
                document.getElementById('listing-expires').textContent = listing.expires_at;
                document.getElementById('expires-row').classList.remove('d-none');
            } else {
                document.getElementById('expires-row').classList.add('d-none');
            }
            
            // Approval info
            if (listing.approved_at) {
                document.getElementById('listing-approved').textContent = listing.approved_at;
                document.getElementById('listing-approved-by').textContent = 'by ' + listing.approved_by;
                document.getElementById('approved-row').classList.remove('d-none');
            } else {
                document.getElementById('approved-row').classList.add('d-none');
            }
            
            // Rejection reason
            if (listing.rejection_reason) {
                document.getElementById('listing-rejection-reason').textContent = listing.rejection_reason;
                document.getElementById('rejection-row').classList.remove('d-none');
            } else {
                document.getElementById('rejection-row').classList.add('d-none');
            }
            
            // Status badge
            const statusBadge = document.getElementById('listing-status-badge');
            statusBadge.textContent = listing.status.replace('_', ' ').toUpperCase();
            statusBadge.className = 'badge ';
            
            switch(listing.status) {
                case 'active':
                    statusBadge.className += 'badge-soft-success';
                    break;
                case 'pending_review':
                    statusBadge.className += 'badge-soft-warning';
                    break;
                case 'rejected':
                    statusBadge.className += 'badge-soft-danger';
                    break;
                case 'expired':
                    statusBadge.className += 'badge-soft-secondary';
                    break;
                case 'sold_out':
                    statusBadge.className += 'badge-soft-dark';
                    break;
            }
            
            // Handle images
            const carouselImages = document.getElementById('carousel-images');
            const noImageText = document.getElementById('no-image-text');
            const carousel = document.getElementById('listingImagesCarousel');
            
            if (listing.images && listing.images.length > 0) {
                carouselImages.innerHTML = '';
                listing.images.forEach((image, index) => {
                    const div = document.createElement('div');
                    div.className = 'carousel-item' + (index === 0 ? ' active' : '');
                    div.innerHTML = `<img src="${image}" class="d-block w-100 rounded" alt="Listing image ${index + 1}" style="max-height: 400px; object-fit: cover;">`;
                    carouselImages.appendChild(div);
                });
                carousel.classList.remove('d-none');
                noImageText.classList.add('d-none');
            } else {
                carousel.classList.add('d-none');
                noImageText.classList.remove('d-none');
            }
            
            // Show modal
            new bootstrap.Modal(document.getElementById('listingDetailsModal')).show();
        });
    });
    
    // Approve Listing
    document.querySelectorAll('.approve-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Approve Listing?',
                text: "This listing will be published to the marketplace and visible to all users.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ab39c',
                cancelButtonColor: '#f06548',
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
    
    // Reject Listing
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function() {
            const listingId = this.getAttribute('data-listing-id');
            const listingTitle = this.getAttribute('data-listing-title');
            
            document.getElementById('reject-listing-title').textContent = listingTitle;
            document.getElementById('rejection-form').action = `/admin/marketplace/listings/${listingId}/reject`;
            document.getElementById('rejection_reason').value = '';
            
            new bootstrap.Modal(document.getElementById('rejectionModal')).show();
        });
    });
    
    // Rejection Form Submit
    document.getElementById('rejection-form').addEventListener('submit', function(e) {
        const reason = document.getElementById('rejection_reason').value.trim();
        if (reason.length < 10) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Reason',
                text: 'Please provide a detailed reason (at least 10 characters)',
            });
        }
    });
    
    // Delete Listing
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Delete Listing?',
                text: "This action cannot be undone. The listing and all associated data will be permanently removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f06548',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endpush