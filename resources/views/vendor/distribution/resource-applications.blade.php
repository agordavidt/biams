@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">{{ $resource->name }} - Applications</h4>
                    <p class="text-muted mb-0">Manage paid applications for distribution</p>
                </div>
                <div>
                    <a href="{{ route('vendor.distribution.search') }}" class="btn btn-primary me-2">
                        Search Farmers
                    </a>
                    <a href="{{ route('vendor.distribution.resources') }}" class="btn btn-light">
                        <i class="ri-arrow-left-line me-1"></i> Back to Resources
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Resource Summary Card -->
    <div class="row">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-2">{{ $resource->name }}</h5>
                            <p class="text-muted mb-2">{{ $resource->description }}</p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $resource->type)) }}</span>
                                <span class="badge bg-primary">₦{{ number_format($resource->price, 2) }} per {{ $resource->unit ?? 'unit' }}</span>
                                @if($resource->requires_quantity)
                                    <span class="badge bg-success">{{ number_format($resource->available_stock) }} {{ $resource->unit }} Available</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <h3 class="mb-0 text-warning">{{ $applications->where('status', 'paid')->count() }}</h3>
                                        <small class="text-muted">Pending Fulfillment</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <h3 class="mb-0 text-success">{{ $resource->applications()->where('status', 'fulfilled')->count() }}</h3>
                                        <small class="text-muted">Fulfilled</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3" x-data="quickSearch()">
                        <div class="col-md-8">
                            <label class="form-label">Quick Search Farmer</label>
                            <div class="input-group">
                                <input type="text" class="form-control" x-model="searchQuery"
                                       placeholder="Search by name, phone, email, or NIN..."
                                       @keyup.enter="searchFarmer()">
                                <button class="btn btn-primary" type="button" @click="searchFarmer()"
                                        :disabled="searching || searchQuery.length < 3">
                                    <span x-show="!searching">
                                        Search
                                    </span>
                                    <!-- <span x-show="searching">
                                        <span class="spinner-border spinner-border-sm me-1"></span> Searching...
                                    </span> -->
                                </button>
                            </div>
                            <small class="text-muted">Search for farmers with paid applications for this resource</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Filter by Status</label>
                            <select class="form-select" onchange="window.location.href='?status=' + this.value">
                                <option value="">All Applications</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid Only</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved Only</option>
                            </select>
                        </div>

                        <!-- Search Results -->
                        <div class="col-12" x-show="searchResults.length > 0" x-cloak>
                            <hr>
                            <h6 class="mb-3">Search Results (<span x-text="searchResults.length"></span> found)</h6>
                            <template x-for="app in searchResults" :key="app.id">
                                <div class="card border mb-2">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <h6 class="mb-1" x-text="app.farmer_name"></h6>
                                                <small class="text-muted">
                                                    <i class="ri-phone-line me-1"></i><span x-text="app.phone"></span><br>
                                                    <i class="ri-mail-line me-1"></i><span x-text="app.email"></span>
                                                </small>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted d-block">Quantity & Amount</small>
                                                <strong x-text="app.quantity_paid + ' {{ $resource->unit }}'"></strong><br>
                                                <span class="text-success">₦<span x-text="formatNumber(app.amount_paid)"></span></span>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted d-block">Payment Ref</small>
                                                <code x-text="app.payment_reference"></code>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <template x-if="!app.is_fulfilled">
                                                    <button type="button" class="btn btn-success btn-sm w-100"
                                                            @click="fulfillApplication(app.id)">
                                                        <i class="ri-check-line me-1"></i> Fulfill
                                                    </button>
                                                </template>
                                                <template x-if="app.is_fulfilled">
                                                    <span class="badge bg-success fs-6">
                                                        <i class="ri-checkbox-circle-line me-1"></i> Fulfilled
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- No Results Message -->
                        <div class="col-12" x-show="searched && searchResults.length === 0" x-cloak>
                            <div class="alert alert-warning">
                                <i class="ri-information-line me-2"></i>
                                No applications found for this farmer with this resource.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Applications</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Farmer Details</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Payment Info</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $application->farmer ? $application->farmer->full_name : $application->user->name }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="ri-phone-line me-1"></i>{{ $application->farmer ? $application->farmer->phone_number : ($application->user->phone ?? 'N/A') }}<br>
                                                    <i class="ri-mail-line me-1"></i>{{ $application->user->email }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($resource->requires_quantity)
                                                <strong>{{ $application->quantity_paid ?? $application->quantity_approved ?? $application->quantity_requested }}</strong> {{ $resource->unit }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-success">₦{{ number_format($application->amount_paid ?? $application->total_amount ?? 0, 2) }}</strong>
                                        </td>
                                        <td>
                                            @if($application->payment_reference)
                                                <small>
                                                    <strong>Ref:</strong><br>
                                                    <code>{{ $application->payment_reference }}</code><br>
                                                    @if($application->paid_at)
                                                        <span class="text-muted">{{ $application->paid_at->format('M d, Y') }}</span>
                                                    @endif
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $application->getStatusBadgeClass() }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $application->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            @if($application->status === 'paid')
                                                <button type="button" class="btn btn-success btn-sm"
                                                        onclick="fulfillFromTable({{ $application->id }})">
                                                    <i class="ri-check-line me-1"></i> Fulfill
                                                </button>
                                            @elseif($application->status === 'fulfilled')
                                                <small class="text-success">
                                                    <i class="ri-checkbox-circle-line me-1"></i> Done<br>
                                                    <span class="text-muted">{{ $application->fulfilled_at->format('M d') }}</span>
                                                </small>
                                            @elseif($application->status === 'approved')
                                                <span class="badge badge-warning">Awaiting Payment</span>
                                            @else
                                                <span class="text-muted">{{ ucfirst($application->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <i class="ri-inbox-line display-4 d-block mb-3"></i>
                                            <h6>No Applications Found</h6>
                                            <p class="mb-0">There are no paid applications for this resource yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($applications->hasPages())
                        <div class="mt-3">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fulfill Modal -->
<div class="modal fade" id="fulfillModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirm Distribution</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Confirm that you have distributed this resource to the farmer.</p>
                <div class="mb-3">
                    <label class="form-label">Fulfillment Notes (Optional)</label>
                    <textarea class="form-control" id="fulfillmentNotes" rows="2"
                              placeholder="Any additional notes about the distribution..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmFulfillBtn">
                    Confirm Distribution
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
    // Quick Search Alpine Component
    function quickSearch() {
        return {
            searchQuery: '',
            searchResults: [],
            searching: false,
            searched: false,

            async searchFarmer() {
                if (this.searchQuery.length < 3) {
                    toastr.warning('Please enter at least 3 characters');
                    return;
                }

                this.searching = true;
                this.searched = false;

                try {
                    const response = await fetch('{{ route("vendor.distribution.search-farmer") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            search: this.searchQuery,
                            resource_id: {{ $resource->id }}
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.searchResults = data.applications;
                        this.searched = true;
                        
                        if (this.searchResults.length === 0) {
                            toastr.info('No applications found for this farmer');
                        }
                    } else {
                        toastr.error(data.error || 'Search failed');
                    }
                } catch (error) {
                    toastr.error('An error occurred during search');
                    console.error(error);
                } finally {
                    this.searching = false;
                }
            },

            fulfillApplication(applicationId) {
                window.currentFulfillApplicationId = applicationId;
                const modal = new bootstrap.Modal(document.getElementById('fulfillModal'));
                modal.show();
            },

            formatNumber(num) {
                return new Intl.NumberFormat('en-NG', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(num);
            }
        };
    }

    // Fulfill from table
    function fulfillFromTable(applicationId) {
        window.currentFulfillApplicationId = applicationId;
        const modal = new bootstrap.Modal(document.getElementById('fulfillModal'));
        modal.show();
    }

    // Confirm fulfillment
    document.addEventListener('DOMContentLoaded', function() {
        const confirmBtn = document.getElementById('confirmFulfillBtn');
        const notesInput = document.getElementById('fulfillmentNotes');
        const modal = document.getElementById('fulfillModal');

        if (confirmBtn) {
            confirmBtn.addEventListener('click', async function() {
                const applicationId = window.currentFulfillApplicationId;
                const notes = notesInput.value;

                if (!applicationId) return;

                confirmBtn.disabled = true;
                confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

                try {
                    const response = await fetch(`/vendor/distribution/applications/${applicationId}/fulfill`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ notes: notes })
                    });

                    const data = await response.json();

                    if (data.success) {
                        toastr.success(data.message || 'Application fulfilled successfully');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        toastr.error(data.error || 'Failed to fulfill application');
                        confirmBtn.disabled = false;
                        confirmBtn.innerHTML = '<i class="ri-check-line me-1"></i> Confirm Distribution';
                    }
                } catch (error) {
                    toastr.error('An error occurred');
                    console.error(error);
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<i class="ri-check-line me-1"></i> Confirm Distribution';
                }
            });
        }

        // Reset modal on close
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                notesInput.value = '';
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="ri-check-line me-1"></i> Confirm Distribution';
                window.currentFulfillApplicationId = null;
            });
        }
    });
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush
@endsection