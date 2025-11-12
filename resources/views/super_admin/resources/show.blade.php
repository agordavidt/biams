@extends('layouts.super_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $resource->name }}</h4>
            <div>
                <a href="{{ route('super_admin.resources.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>

<!-- Resource Information -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Resource Information</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Name:</strong> {{ $resource->name }}</p>
                        <p class="mb-2"><strong>Type:</strong> {{ ucfirst($resource->type) }}</p>
                        <p class="mb-2"><strong>Status:</strong> 
                            <span class="badge bg-{{ $resource->status === 'active' ? 'success' : ($resource->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $resource->status)) }}
                            </span>
                        </p>
                        <p class="mb-2"><strong>Source:</strong> 
                            @if($resource->vendor_id)
                                <a href="{{ route('super_admin.vendors.show', $resource->vendor) }}">
                                    {{ $resource->vendor->legal_name }}
                                </a>
                            @else
                                Ministry
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Payment Required:</strong> {{ $resource->requires_payment ? 'Yes' : 'No' }}</p>
                        @if($resource->requires_payment)
                            <p class="mb-2"><strong>Original Price:</strong> ₦{{ number_format($resource->original_price, 2) }}</p>
                            <p class="mb-2"><strong>Current Price:</strong> ₦{{ number_format($resource->price, 2) }}</p>
                        @endif
                        @if($resource->requires_quantity)
                            <p class="mb-2"><strong>Unit:</strong> {{ $resource->unit }}</p>
                            <p class="mb-2"><strong>Total Stock:</strong> {{ number_format($resource->total_stock) }}</p>
                            <p class="mb-2"><strong>Available Stock:</strong> {{ number_format($resource->available_stock) }}</p>
                            <p class="mb-2"><strong>Max Per Farmer:</strong> {{ $resource->max_per_farmer }}</p>
                        @endif
                    </div>
                </div>

                <h6 class="mt-4 mb-2">Description</h6>
                <p>{{ $resource->description }}</p>

                @if($resource->start_date || $resource->end_date)
                <h6 class="mt-4 mb-2">Availability Period</h6>
                <p class="mb-2">
                    <strong>Start Date:</strong> {{ $resource->start_date ? $resource->start_date->format('Y-m-d') : 'N/A' }}
                </p>
                <p class="mb-2">
                    <strong>End Date:</strong> {{ $resource->end_date ? $resource->end_date->format('Y-m-d') : 'N/A' }}
                </p>
                @endif

                @if($resource->form_fields)
                <h6 class="mt-4 mb-2">Required Information</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Type</th>
                                <th>Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resource->form_fields as $field)
                            <tr>
                                <td>{{ $field['label'] }}</td>
                                <td>{{ ucfirst($field['type']) }}</td>
                                <td>{{ $field['required'] ? 'Yes' : 'No' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="mt-4">
                    <p class="mb-2"><strong>Created By:</strong> {{ $resource->createdBy ? $resource->createdBy->name : 'N/A' }}</p>
                    <p class="mb-2"><strong>Created At:</strong> {{ $resource->created_at->format('Y-m-d H:i') }}</p>
                    @if($resource->reviewed_by)
                        <p class="mb-2"><strong>Reviewed By:</strong> {{ $resource->reviewedBy->name }}</p>
                        <p class="mb-2"><strong>Reviewed At:</strong> {{ $resource->reviewed_at->format('Y-m-d H:i') }}</p>
                    @endif
                </div>

                @if($resource->rejection_reason)
                <div class="alert alert-danger mt-3">
                    <strong>Rejection Reason:</strong> {{ $resource->rejection_reason }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Application Statistics</h5>
                
                <div class="mb-3">
                    <p class="text-muted mb-1">Total Applications</p>
                    <h4>{{ number_format($stats['total_applications']) }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Pending</p>
                    <h4>{{ number_format($stats['pending']) }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Approved</p>
                    <h4>{{ number_format($stats['approved']) }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Paid</p>
                    <h4>{{ number_format($stats['paid']) }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Fulfilled</p>
                    <h4>{{ number_format($stats['fulfilled']) }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Rejected</p>
                    <h4>{{ number_format($stats['rejected']) }}</h4>
                </div>

                <hr>

                <div class="mb-3">
                    <p class="text-muted mb-1">Total Revenue</p>
                    <h4>₦{{ number_format($stats['total_revenue'], 2) }}</h4>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Quick Actions</h5>
                <a href="{{ route('super_admin.resources.applications', $resource) }}" class="btn btn-primary w-100 mb-2">
                    View All Applications
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Recent Applications</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>NIN</th>
                                <th>Phone</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resource->applications as $application)
                            <tr>
                                <td>
                                    @if($application->farmer)
                                        {{ $application->farmer->first_name }} {{ $application->farmer->last_name }}
                                    @else
                                        {{ $application->user->name }}
                                    @endif
                                </td>
                                <td>{{ $application->farmer ? $application->farmer->nin : 'N/A' }}</td>
                                <td>
                                    @if($application->farmer)
                                        {{ $application->farmer->phone_number }}
                                    @else
                                        {{ $application->user->phone ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>
                                    @if($application->quantity_requested)
                                        {{ $application->quantity_requested }} {{ $resource->unit }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $application->status === 'fulfilled' ? 'success' : 
                                        ($application->status === 'rejected' ? 'danger' : 'warning') 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($application->amount_paid)
                                        ₦{{ number_format($application->amount_paid, 2) }}
                                    @else
                                        Free
                                    @endif
                                </td>
                                <td>{{ $application->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No applications yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($resource->applications->count() >= 10)
                <div class="mt-3 text-center">
                    <a href="{{ route('super_admin.resources.applications', $resource) }}" class="btn btn-primary">
                        View All Applications
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection