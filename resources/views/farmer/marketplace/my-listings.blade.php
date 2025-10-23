@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">My Marketplace Listings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">My Listings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="mdi mdi-block-helper me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="mdi mdi-information me-2"></i>{{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Subscription Status Card -->
<div class="row">
    <div class="col-12">
        <div class="card border {{ $isSubscribed ? 'border-success' : 'border-warning' }}">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-{{ $isSubscribed ? 'success' : 'warning' }} rounded-circle">
                                <i class="ri-shield-star-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        @if($isSubscribed)
                            <h5 class="mb-1 text-success">✓ Active Marketplace Subscription</h5>
                            <p class="text-muted mb-0">
                                Your subscription is valid until <strong>{{ $subscriptionEndDate->format('d M, Y') }}</strong>
                                @if($daysRemaining <= 30)
                                    <span class="badge bg-warning ms-2">{{ $daysRemaining }} days remaining</span>
                                @endif
                            </p>
                        @else
                            <h5 class="mb-1 text-warning">⚠ No Active Subscription</h5>
                            <p class="text-muted mb-0">Subscribe to the marketplace to list your products and reach buyers across Benue State</p>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        @if(!$isSubscribed)
                            <form method="POST" action="{{ route('farmer.marketplace.subscribe') }}" id="subscribeForm">
                                @csrf
                                <button type="submit" class="btn btn-primary" id="subscribeBtn">
                                    <i class="ri-shopping-cart-line me-1"></i>Subscribe Now - ₦5,000/Year
                                </button>
                                <p class="text-muted mt-2 mb-0">
                                    <small><i class="ri-shield-check-line me-1"></i> Secure payment via Credo</small>
                                </p>
                            </form>
                        @else
                            <a href="{{ route('farmer.marketplace.create') }}" class="btn btn-success">
                                <i class="ri-add-line me-1"></i>Create New Listing
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Listings</p>
                        <h4 class="mb-2">{{ $stats['total'] }}</h4>
                        <p class="text-muted mb-0"><span class="text-success fw-bold me-1">{{ $stats['active'] }}</span>Active</p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-file-list-3-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Pending Review</p>
                        <h4 class="mb-2">{{ $stats['pending'] }}</h4>
                        <p class="text-muted mb-0"><span class="text-warning fw-bold me-1">{{ $stats['expired'] }}</span>Expired</p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-time-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Views</p>
                        <h4 class="mb-2">{{ number_format($stats['total_views']) }}</h4>
                        <p class="text-muted mb-0">Product impressions</p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-info rounded-3">
                            <i class="ri-eye-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Inquiries</p>
                        <h4 class="mb-2">{{ number_format($stats['total_inquiries']) }}</h4>
                        <p class="text-muted mb-0">
                            <a href="{{ route('farmer.marketplace.leads') }}">View all leads</a>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-mail-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Listings Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap mb-3">
                    <h4 class="card-title">All My Listings</h4>
                    <div class="ms-auto">
                        @if($isSubscribed)
                            <a href="{{ route('farmer.marketplace.create') }}" class="btn btn-success">
                                <i class="ri-add-line align-middle me-1"></i>New Listing
                            </a>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-nowrap mb-0" id="listingsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Location</th>
                                <th>Views</th>
                                <th>Inquiries</th>
                                <th>Status</th>
                                <th>Expires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listings as $listing)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="{{ asset('storage/' . $listing->primary_image_path) }}" 
                                                 alt="{{ $listing->title }}" 
                                                 class="avatar-sm rounded"
                                                 onerror="this.src='{{ asset('dashboard/images/placeholder.jpg') }}'">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="font-size-14 mb-1">{{ Str::limit($listing->title, 30) }}</h5>
                                            <p class="text-muted mb-0">{{ $listing->quantity }} {{ $listing->unit }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $listing->category->name }}</td>
                                <td><strong>₦{{ number_format($listing->price, 2) }}</strong></td>
                                <td>{{ $listing->location }}</td>
                                <td><span class="badge bg-info">{{ $listing->view_count }}</span></td>
                                <td><span class="badge bg-primary">{{ $listing->inquiries_count }}</span></td>
                                <td>
                                    @if($listing->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($listing->status === 'pending_review')
                                        <span class="badge bg-warning">Pending Review</span>
                                    @elseif($listing->status === 'rejected')
                                        <span class="badge bg-danger" data-bs-toggle="tooltip" 
                                              title="{{ $listing->rejection_reason }}">Rejected</span>
                                    @elseif($listing->status === 'expired')
                                        <span class="badge bg-secondary">Expired</span>
                                    @else
                                        <span class="badge bg-dark">{{ ucfirst($listing->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($listing->expires_at)
                                        <span class="text-{{ $listing->days_remaining <= 7 ? 'danger' : 'muted' }}">
                                            {{ $listing->expires_at->format('d M, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" 
                                                data-bs-toggle="dropdown">
                                            <i class="ri-more-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('marketplace.show', $listing) }}" target="_blank">
                                                    <i class="ri-eye-line me-2"></i>View Listing
                                                </a>
                                            </li>
                                            @if(in_array($listing->status, ['draft', 'rejected', 'active']))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('farmer.marketplace.edit', $listing) }}">
                                                        <i class="ri-pencil-line me-2"></i>Edit
                                                    </a>
                                                </li>
                                            @endif
                                            @if($listing->status === 'rejected')
                                                <li>
                                                    <button class="dropdown-item text-info" 
                                                            onclick="showRejectionReason('{{ addslashes($listing->rejection_reason) }}')">
                                                        <i class="ri-information-line me-2"></i>View Rejection Reason
                                                    </button>
                                                </li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-danger" 
                                                        onclick="deleteListing({{ $listing->id }})">
                                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="ri-inbox-line" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="mt-3 text-muted">You haven't created any listings yet</p>
                                    @if($isSubscribed)
                                        <a href="{{ route('farmer.marketplace.create') }}" class="btn btn-primary mt-2">
                                            Create Your First Listing
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="row mt-4">
                    <div class="col-sm-6">
                        <div>
                            <p class="mb-sm-0">
                                Showing {{ $listings->firstItem() ?? 0 }} to {{ $listings->lastItem() ?? 0 }} 
                                of {{ $listings->total() }} entries
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-end">
                            {{ $listings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialize DataTables
$(document).ready(function() {
    $('#listingsTable').DataTable({
        "paging": false,
        "searching": false,
        "info": false,
        "ordering": true,
        "order": [[7, "desc"]]
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Subscribe form loading state
document.addEventListener('DOMContentLoaded', function() {
    const subscribeForm = document.getElementById('subscribeForm');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function(e) {
            const btn = document.getElementById('subscribeBtn');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 10000);
            }
        });
    }
});

function showRejectionReason(reason) {
    Swal.fire({
        title: 'Rejection Reason',
        html: `<p class="text-start">${reason}</p>`,
        icon: 'info',
        confirmButtonText: 'Understood'
    });
}

function deleteListing(listingId) {
    Swal.fire({
        title: 'Delete this listing?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f46a6a',
        cancelButtonColor: '#74788d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/farmer/marketplace/listings/${listingId}`;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush

<style>
.subscription-card {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: none;
    border-left: 4px solid #f59e0b;
}

.subscription-card.active {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border-left: 4px solid #10b981;
}

.avatar-sm {
    width: 3rem;
    height: 3rem;
}

.btn-primary {
    background: linear-gradient(135deg, #10b981 0%, #10b981 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.card {
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.table-hover tbody tr:hover {
    background-color: #f8fafc;
}

.badge {
    font-size: 0.75em;
    font-weight: 500;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection