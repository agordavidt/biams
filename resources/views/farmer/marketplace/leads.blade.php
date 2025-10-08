@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">My Leads & Inquiries</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.marketplace.my-listings') }}">My Listings</a></li>
                    <li class="breadcrumb-item active">Leads</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Inquiries</p>
                        <h4 class="mb-2">{{ $stats['total'] }}</h4>
                        <p class="text-muted mb-0">All time leads</p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-mail-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">New Inquiries</p>
                        <h4 class="mb-2">{{ $stats['new'] }}</h4>
                        <p class="text-muted mb-0">Requires attention</p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-notification-3-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inquiries List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">All Inquiries</h4>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="inquiriesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Buyer Details</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $inquiry)
                            <tr class="{{ $inquiry->status === 'new' ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $inquiry->created_at->format('d M, Y') }}</strong><br>
                                    <small class="text-muted">{{ $inquiry->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $inquiry->listing->primary_image_path) }}" 
                                             class="avatar-sm rounded me-2"
                                             onerror="this.src='{{ asset('dashboard/images/placeholder.jpg') }}'">
                                        <div>
                                            <h6 class="mb-0">{{ Str::limit($inquiry->listing->title, 30) }}</h6>
                                            <small class="text-muted">₦{{ number_format($inquiry->listing->price, 2) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $inquiry->buyer_name }}</strong><br>
                                        <i class="ri-phone-line"></i> {{ $inquiry->buyer_phone }}<br>
                                        @if($inquiry->buyer_email)
                                            <i class="ri-mail-line"></i> {{ $inquiry->buyer_email }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-link" 
                                            onclick="showMessage('{{ addslashes($inquiry->message) }}')">
                                        <i class="ri-message-2-line"></i> View Message
                                    </button>
                                </td>
                                <td>
                                    @if($inquiry->status === 'new')
                                        <span class="badge bg-warning">New</span>
                                    @elseif($inquiry->status === 'contacted')
                                        <span class="badge bg-info">Contacted</span>
                                    @elseif($inquiry->status === 'converted')
                                        <span class="badge bg-success">Converted</span>
                                    @else
                                        <span class="badge bg-secondary">Archived</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="tel:{{ $inquiry->buyer_phone }}" class="btn btn-sm btn-success">
                                        <i class="ri-phone-line"></i> Call
                                    </a>
                                    @if($inquiry->buyer_email)
                                        <a href="mailto:{{ $inquiry->buyer_email }}" class="btn btn-sm btn-info">
                                            <i class="ri-mail-line"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="ri-inbox-line" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="mt-3 text-muted">No inquiries received yet</p>
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
                                Showing {{ $inquiries->firstItem() ?? 0 }} to {{ $inquiries->lastItem() ?? 0 }} 
                                of {{ $inquiries->total() }} entries
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-end">
                            {{ $inquiries->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#inquiriesTable').DataTable({
        "paging": false,
        "searching": true,
        "info": false,
        "ordering": true,
        "order": [[0, "desc"]]
    });
});

function showMessage(message) {
    Swal.fire({
        title: 'Buyer Inquiry',
        html: `<p class="text-start">${message}</p>`,
        icon: 'info',
        confirmButtonText: 'Close'
    });
}
</script>
@endpush
@endsection