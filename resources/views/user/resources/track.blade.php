@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Track Applications</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Applications</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        {{ session('error') }}
    </div>
@endif

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('farmer.resources.track') }}" class="row g-3">
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-filter-line me-1"></i> Filter
                        </button>
                        @if(request()->hasAny(['status']))
                            <a href="{{ route('farmer.resources.track') }}" class="btn btn-secondary">
                                <i class="ri-refresh-line"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if($applications->isEmpty())
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar-md mx-auto mb-3 bg-light text-muted rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-file-list-3-line fs-2"></i>
                    </div>
                    <h5 class="text-muted">No Applications Found</h5>
                    <p class="text-muted mb-3">You haven't submitted any applications yet.</p>
                    <a href="{{ route('farmer.resources.index') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Browse Resources
                    </a>
                </div>
            </div>
        @else
            @foreach($applications as $application)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $application->resource->name }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="ri-calendar-line me-1"></i>
                                    Submitted: {{ $application->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            <span class="badge px-3 py-2
                                @switch($application->status)
                                    @case('pending')
                                        bg-warning
                                        @break
                                    @case('approved')
                                        bg-success
                                        @break
                                    @case('rejected')
                                        bg-danger
                                        @break
                                    @default
                                        bg-secondary
                                @endswitch">
                                {{ $application->getStatusLabel() }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-2 text-muted">Application Details</h6>
                            <div class="row">
                                @foreach($application->form_data as $label => $value)
                                    <div class="col-md-6 mb-2">
                                        <strong>{{ $label }}:</strong>
                                        <span class="ms-2">
                                            @if(is_array($value))
                                                @if(isset($value['original_name']))
                                                    <a href="{{ Storage::url($value['path']) }}" target="_blank" class="text-primary">
                                                        <i class="ri-file-line me-1"></i>{{ $value['original_name'] }}
                                                    </a>
                                                @else
                                                    {{ json_encode($value) }}
                                                @endif
                                            @else
                                                {{ $value ?? 'N/A' }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($application->payment_status)
                            <div class="alert alert-light border mb-3">
                                <h6 class="mb-2">
                                    <i class="ri-secure-payment-line me-1"></i>
                                    Payment Information
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="text-muted">Status:</span>
                                        <span class="ms-2 badge bg-success-subtle text-success">
                                            {{ $application->getPaymentStatusLabel() }}
                                        </span>
                                    </div>
                                    @if($application->payment_reference)
                                        <div class="col-md-6">
                                            <span class="text-muted">Reference:</span>
                                            <code class="ms-2">{{ $application->payment_reference }}</code>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                @switch($application->status)
                                    @case('pending')
                                        <i class="ri-time-line me-1"></i>
                                        Your application is pending review. We'll notify you once it's processed.
                                        @break
                                    @case('approved')
                                        <i class="ri-checkbox-circle-line me-1 text-success"></i>
                                        Your application has been approved!
                                        @break
                                    @case('rejected')
                                        <i class="ri-close-circle-line me-1 text-danger"></i>
                                        Unfortunately, your application was not approved.
                                        @break
                                @endswitch
                            </div>
                            <a href="{{ route('farmer.resources.applications.show', $application) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="ri-eye-line me-1"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            {{ $applications->links() }}
        @endif
    </div>
</div>
@endsection