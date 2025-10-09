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
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search title, description or seller" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
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
                    <h5 class="card-title">All Listings</h5>
                    @if(request()->has('search') || request()->has('status') || request()->has('category_id'))
                        <a href="{{ route('admin.marketplace.listings') }}" class="btn btn-outline-secondary">
                            <i class="ri-restart-line me-1"></i> Clear Filters
                        </a>
                    @endif
                </div>
Copy            <div class="table-responsive">
                <table id="listings-table" class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Seller</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Expires</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listings as $listing)
                        <tr>
                            <td>{{ $listing->id }}</td>
                            <td>
                                @if($listing->image)
                                    <a href="{{ Storage::url($listing->image) }}" class="image-popup-no-margins">
                                        <img src="{{ Storage::url($listing->image) }}" alt="{{ $listing->title }}" class="img-fluid" style="max-height: 50px; max-width: 50px;">
                                    </a>
                                @else
                                    <span class="text-muted"><i class="ri-image-line"></i> No Image</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;">{{ $listing->title }}</div>
                            </td>
                            <td>{{ number_format($listing->price, 2) }}</td>
                            <td>{{ $listing->category->name }}</td>
                            <td>{{ $listing->user->name }}</td>
                            <td>
                                @if($listing->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($listing->status == 'sold')
                                    <span class="badge bg-warning">Sold</span>
                                @elseif($listing->status == 'pending')
                                    <span class="badge bg-info">Pending</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $listing->created_at->format('d M Y') }}</td>
                            <td>
                                @if($listing->expires_at)
                                    @if($listing->expires_at < now())
                                        <span class="text-danger">{{ $listing->expires_at->format('d M Y') }}</span>
                                    @else
                                        {{ $listing->expires_at->format('d M Y') }}
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- <a href="{{ route('marketplace.show', $listing) }}" class="btn btn-sm btn-info" target="_blank" title="View Listing">
                                        <i class="ri-eye-line"></i>
                                    </a> -->
                                    <button type="button" class="btn btn-sm btn-primary view-details-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#listingDetailsModal" 
                                            data-id="{{ $listing->id }}"
                                            data-title="{{ $listing->title }}"
                                            data-description="{{ $listing->description }}"
                                            data-price="{{ $listing->price }}"
                                            data-category="{{ $listing->category->name }}"
                                            data-seller="{{ $listing->user->name }}"
                                            data-status="{{ $listing->status }}"
                                            data-created="{{ $listing->created_at->format('d M Y') }}"
                                            data-expires="{{ $listing->expires_at ? $listing->expires_at->format('d M Y') : 'N/A' }}"
                                            data-image="{{ $listing->image ? Storage::url($listing->image) : '' }}"
                                            title="View Details">
                                        <i class="ri-file-list-line"></i>
                                    </button>
                                    <form action="{{ route('admin.marketplace.remove', $listing) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete Listing">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $listings->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
</div>
<!-- Listing Details Modal -->
<div class="modal fade" id="listingDetailsModal" tabindex="-1" aria-labelledby="listingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listingDetailsModalLabel">Listing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <div id="listing-image-container" class="text-center">
                            <img id="listing-image" src="" alt="Listing Image" class="img-fluid rounded" style="max-height: 300px;">
                            <p id="no-image-text" class="text-muted mt-3 d-none">
                                <i class="ri-image-line me-1"></i> No image available
                            </p>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h4 id="listing-title" class="mb-3"></h4>
                        <div class="mb-3">
                            <span class="badge bg-primary me-2" id="listing-category"></span>
                            <span class="badge" id="listing-status"></span>
                        </div>
                        <p class="mb-2"><strong>Price:</strong> <span id="listing-price"></span></p>
                        <p class="mb-2"><strong>Seller:</strong> <span id="listing-seller"></span></p>
                        <p class="mb-2"><strong>Created:</strong> <span id="listing-created"></span></p>
                        <p class="mb-2"><strong>Expires:</strong> <span id="listing-expires"></span></p>
                        <hr>
                        <h5>Description</h5>
                        <p id="listing-description" class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="view-on-site-btn" href="#" target="_blank" class="btn btn-primary">View on Site</a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable with custom settings
        $('#listings-table').DataTable({
            paging: false,
            info: false,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [1, 9] }
            ]
        });
        
        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This listing will be permanently removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
        
        // Modal details population
        const detailButtons = document.querySelectorAll('.view-details-btn');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                const price = this.getAttribute('data-price');
                const category = this.getAttribute('data-category');
                const seller = this.getAttribute('data-seller');
                const status = this.getAttribute('data-status');
                const created = this.getAttribute('data-created');
                const expires = this.getAttribute('data-expires');
                const image = this.getAttribute('data-image');
                
                document.getElementById('listing-title').textContent = title;
                document.getElementById('listing-description').textContent = description;
                document.getElementById('listing-price').textContent = new Intl.NumberFormat('en-NG', { 
                    style: 'currency', 
                    currency: 'NGN' 
                }).format(price);
                document.getElementById('listing-category').textContent = category;
                document.getElementById('listing-seller').textContent = seller;
                document.getElementById('listing-created').textContent = created;
                document.getElementById('listing-expires').textContent = expires;
                document.getElementById('view-on-site-btn').href = `{{ url('/marketplace/listings') }}/${id}`;
                
                // Set status with appropriate color
                const statusElement = document.getElementById('listing-status');
                statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                
                switch(status) {
                    case 'active':
                        statusElement.className = 'badge bg-success';
                        break;
                    case 'sold':
                        statusElement.className = 'badge bg-warning';
                        break;
                    case 'pending':
                        statusElement.className = 'badge bg-info';
                        break;
                    default:
                        statusElement.className = 'badge bg-danger';
                }
                
                // Handle image
                const imageContainer = document.getElementById('listing-image-container');
                const imageElement = document.getElementById('listing-image');
                const noImageText = document.getElementById('no-image-text');
                
                if (image) {
                    imageElement.src = image;
                    imageElement.classList.remove('d-none');
                    noImageText.classList.add('d-none');
                } else {
                    imageElement.classList.add('d-none');
                    noImageText.classList.remove('d-none');
                }
            });
        });
    });
</script>
@endpush