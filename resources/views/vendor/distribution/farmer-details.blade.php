@extends('layouts.vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Farmer Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.distribution.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.distribution.search') }}">Search</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Farmer Information -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-lg">
                            <span class="avatar-title bg-primary rounded-circle font-size-24">
                                {{ strtoupper(substr($farmer->full_name ?? 'F', 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ $farmer->full_name }}</h4>
                        <p class="text-muted mb-0">NIN: {{ $farmer->nin }}</p>
                    </div>
                    <div>
                        <span class="badge badge-soft-success font-size-14">Verified</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Phone Number</p>
                        <p class="mb-3"><strong>{{ $farmer->phone_primary }}</strong></p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">LGA</p>
                        <p class="mb-3">{{ $farmer->lga->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Ward</p>
                        <p class="mb-3">{{ $farmer->ward ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paid Applications -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Paid Applications Ready for Fulfillment</h4>

                @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Resource</th>
                                <th>Quantity Paid</th>
                                <th>Amount Paid</th>
                                <th>Payment Ref</th>
                                <th>Paid On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->resource->name }}</strong>
                                    <br><small class="text-muted">{{ $application->resource->type }}</small>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $application->quantity_paid }} {{ $application->resource->unit }}</strong>
                                </td>
                                <td>
                                    <strong class="text-success">₦{{ number_format($application->amount_paid, 2) }}</strong>
                                </td>
                                <td>
                                    <code>{{ $application->payment_reference }}</code>
                                </td>
                                <td>
                                    {{ $application->paid_at->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $application->paid_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-success btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#fulfillModal{{ $application->id }}">
                                        <i class="ri-check-double-line me-1"></i> Mark as Fulfilled
                                    </button>

                                    <!-- Fulfill Modal -->
                                    <div class="modal fade" id="fulfillModal{{ $application->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('vendor.distribution.mark-fulfilled', $application) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Fulfillment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-warning">
                                                            <i class="ri-error-warning-line me-2"></i>
                                                            <strong>Important:</strong> This action is final and cannot be undone. Please verify:
                                                        </div>

                                                        <div class="mb-3">
                                                            <h6>Resource Details:</h6>
                                                            <ul>
                                                                <li><strong>Resource:</strong> {{ $application->resource->name }}</li>
                                                                <li><strong>Quantity:</strong> {{ $application->quantity_paid }} {{ $application->resource->unit }}</li>
                                                                <li><strong>Farmer:</strong> {{ $farmer->full_name }}</li>
                                                            </ul>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="fulfillment_notes{{ $application->id }}" class="form-label">Fulfillment Notes (Optional)</label>
                                                            <textarea class="form-control" 
                                                                      id="fulfillment_notes{{ $application->id }}" 
                                                                      name="fulfillment_notes" 
                                                                      rows="3" 
                                                                      maxlength="500"
                                                                      placeholder="Any additional notes about the delivery..."></textarea>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" 
                                                                   type="checkbox" 
                                                                   id="confirm{{ $application->id }}" 
                                                                   required>
                                                            <label class="form-check-label" for="confirm{{ $application->id }}">
                                                                I confirm that I have delivered the exact quantity to the farmer
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="ri-check-line me-1"></i> Confirm Fulfillment
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="ri-inbox-line display-4 text-muted"></i>
                    <p class="text-muted mt-3">This farmer has no paid applications for your vendor's resources.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <a href="{{ route('vendor.distribution.search') }}" class="btn btn-secondary">
            <i class="ri-arrow-left-line me-1"></i> Search Another Farmer
        </a>
    </div>
</div>
@endsection