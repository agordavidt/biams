@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">My Marketplace Listings</h4>
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

                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                            <tr>
                                <th>Listing</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Listed On</th>
                                <th>Expires On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listings as $listing)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                @if($listing->image)
                                                    <img src="{{ Storage::url($listing->image) }}" alt="{{ $listing->title }}" class="rounded" height="48">
                                                @else
                                                    <div class="avatar-sm">
                                                        <span class="avatar-title bg-soft-primary text-primary rounded">
                                                            <i class="ri-shopping-bag-line font-size-20"></i>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="font-size-14 mb-1">
                                                    <a href="{{ route('marketplace.show', $listing) }}" class="text-dark">{{ $listing->title }}</a>
                                                </h5>
                                                <p class="text-muted mb-0">
                                                    <i class="ri-map-pin-line"></i> {{ $listing->location }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $listing->category->name }}</td>
                                    <td>₦{{ number_format($listing->price, 2) }}
                                        @if($listing->unit)
                                            <small class="text-muted">/ {{ $listing->unit }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm {{ $listing->availability == 'available' ? 'btn-success' : 'btn-secondary' }} dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ ucfirst($listing->availability) }}
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('marketplace.update-status', $listing) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="availability" value="available">
                                                        <button type="submit" class="dropdown-item {{ $listing->availability == 'available' ? 'active' : '' }}">Available</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('marketplace.update-status', $listing) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="availability" value="sold">
                                                        <button type="submit" class="dropdown-item {{ $listing->availability == 'sold' ? 'active' : '' }}">Sold</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>{{ $listing->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($listing->expires_at->isPast())
                                            <span class="badge bg-danger">Expired</span>
                                        @else
                                            {{ $listing->expires_at->format('M d, Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('marketplace.messages.conversation', $listing) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Messages">
                                                <i class="ri-message-3-line"></i>
                                            </a>
                                            <a href="{{ route('marketplace.edit', $listing) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ri-edit-2-line"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteListingModal{{ $listing->id }}" title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ri-shopping-bag-line ri-3x text-muted mb-3"></i>
                                            <h5>You haven't created any listings yet</h5>
                                            <p class="text-muted">Start selling your agricultural products by creating your first listing.</p>
                                            <a href="{{ route('marketplace.create') }}" class="btn btn-primary mt-2">
                                                <i class="ri-add-line align-middle me-1"></i> Create Listing
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $listings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Listing Modals -->
@foreach($listings as $listing)
    <div class="modal fade" id="deleteListingModal{{ $listing->id }}" tabindex="-1" aria-labelledby="deleteListingModalLabel{{ $listing->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteListingModalLabel{{ $listing->id }}">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete "<strong>{{ $listing->title }}</strong>"? This action cannot be undone.</p>
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
@endforeach
@endsection