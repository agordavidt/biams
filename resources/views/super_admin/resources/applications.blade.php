@extends('layouts.super_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Applications for {{ $resource->name }}</h4>
            <div>
                <a href="{{ route('super_admin.resources.show', $resource) }}" class="btn btn-secondary">Back to Resource</a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('super_admin.resources.applications', $resource) }}" class="mb-0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="payment_pending" {{ request('status') === 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="fulfilled" {{ request('status') === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('super_admin.resources.applications', $resource) }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Applications Table -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Applicant</th>
                                <th>NIN</th>
                                <th>Contact</th>
                                <th>Quantity Requested</th>
                                <th>Quantity Approved</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Applied Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $application)
                            <tr>
                                <td>{{ $application->id }}</td>
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
                                        {{ $application->farmer->phone_number }}<br>
                                        <small class="text-muted">{{ $application->user->email }}</small>
                                    @else
                                        {{ $application->user->phone ?? 'N/A' }}<br>
                                        <small class="text-muted">{{ $application->user->email }}</small>
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
                                    @if($application->quantity_approved)
                                        {{ $application->quantity_approved }} {{ $resource->unit }}
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
                                <td colspan="9" class="text-center">No applications found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection