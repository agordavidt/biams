@extends('layouts.new')

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

<div class="row">
    <div class="col-12">
        @if($applications->isEmpty())
            <div class="card">
                <div class="card-body text-center text-muted">
                    You haven't submitted any applications yet.
                </div>
            </div>
        @else
            @foreach($applications as $application)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title">{{ $application->resource->name }}</h5>
                                <p class="text-muted mb-0">Submitted: {{ $application->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="badge 
                                @switch($application->status)
                                    @case('pending')
                                        bg-warning
                                        @break
                                    @case('reviewing')
                                        bg-info
                                        @break
                                    @case('approved')
                                        bg-success
                                        @break
                                    @case('rejected')
                                        bg-danger
                                        @break
                                    @case('processing')
                                        bg-primary
                                        @break
                                    @case('delivered')
                                        bg-secondary
                                        @break
                                @endswitch">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-2">Application Details</h6>
                            <div class="row">
                                @foreach($application->form_data as $field => $value)
                                    <div class="col-md-6 mb-2">
                                        <span class="text-muted">{{ ucfirst($field) }}:</span>
                                        <span class="ms-2">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($application->payment_status)
                            <div class="alert alert-light mb-3">
                                <h6 class="mb-2">Payment Information</h6>
                                <div class="mb-1">
                                    <span class="text-muted">Status:</span>
                                    <span class="ms-2">{{ ucfirst($application->payment_status) }}</span>
                                </div>
                                @if($application->payment_reference)
                                    <div>
                                        <span class="text-muted">Reference:</span>
                                        <span class="ms-2">{{ $application->payment_reference }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="text-muted small">
                            @switch($application->status)
                                @case('pending')
                                    Your application is pending review.
                                    @break
                                @case('reviewing')
                                    Your application is currently being reviewed.
                                    @break
                                @case('approved')
                                    Your application has been approved!
                                    @if($application->resource->requires_payment && $application->payment_status !== 'paid')
                                        Please complete the payment to proceed.
                                    @endif
                                    @break
                                @case('rejected')
                                    Unfortunately, your application was not approved.
                                    @break
                                @case('processing')
                                    Your application is being processed.
                                    @break
                                @case('delivered')
                                    Your resource has been delivered.
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection