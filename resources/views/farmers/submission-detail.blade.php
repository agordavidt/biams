@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Submission Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmers.submissions') }}">Registrations</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>        
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">{{ $submission->type }} - Submission #{{ $submission->id }}</h4>
                    <span class="badge @switch($submission->status)
                        @case('approved')
                            bg-success
                            @break
                        @case('pending')
                            bg-warning
                            @break
                        @case('rejected')
                            bg-danger
                            @break
                        @default
                            bg-secondary
                    @endswitch fs-6">
                        {{ ucfirst($submission->status) }}
                    </span>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="text-muted mb-2"><strong>Submission ID:</strong> {{ $submission->id }}</p>
                        <p class="text-muted mb-2"><strong>Application Date:</strong> {{ $submission->created_at->format('M d, Y h:i A') }}</p>
                        <p class="text-muted mb-2"><strong>Last Updated:</strong> {{ $submission->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-2"><strong>Type:</strong> {{ $submission->type }}</p>
                        <p class="text-muted mb-2"><strong>Status:</strong> 
                            <span class="badge @switch($submission->status)
                                @case('approved')
                                    bg-success
                                    @break
                                @case('pending')
                                    bg-warning
                                    @break
                                @case('rejected')
                                    bg-danger
                                    @break
                                @default
                                    bg-secondary
                            @endswitch">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Submission Information</h5>

                @if($type === 'crop')
                    {{-- Crop Farming Details --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Crop Type</label>
                            <p class="text-muted">{{ $submission->crop }}</p>
                        </div>
                        @if($submission->other_crop)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Other Crop (Specified)</label>
                            <p class="text-muted">{{ $submission->other_crop }}</p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Farm Size (Hectares)</label>
                            <p class="text-muted">{{ number_format($submission->farm_size, 2) }} ha</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Farming Methods</label>
                            <p class="text-muted">{{ $submission->farming_methods }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Seasonal Pattern</label>
                            <p class="text-muted">{{ $submission->seasonal_pattern }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Farm Location</label>
                            <p class="text-muted">{{ $submission->farm_location }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Coordinates</label>
                            <p class="text-muted">Latitude: {{ $submission->latitude }}, Longitude: {{ $submission->longitude }}</p>
                        </div>
                    </div>

                @elseif($type === 'animal')
                    {{-- Animal Farming Details --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Livestock Type</label>
                            <p class="text-muted">{{ $submission->livestock }}</p>
                        </div>
                        @if($submission->other_livestock)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Other Livestock (Specified)</label>
                            <p class="text-muted">{{ $submission->other_livestock }}</p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Herd Size</label>
                            <p class="text-muted">{{ number_format($submission->herd_size) }} animals</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Facility Type</label>
                            <p class="text-muted">{{ $submission->facility_type }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Breeding Program</label>
                            <p class="text-muted">{{ $submission->breeding_program }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Farm Location</label>
                            <p class="text-muted">{{ $submission->farm_location }}</p>
                        </div>
                    </div>

                @elseif($type === 'abattoir')
                    {{-- Abattoir Operator Details --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Facility Type</label>
                            <p class="text-muted">{{ $submission->facility_type }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Operational Capacity</label>
                            <p class="text-muted">{{ $submission->operational_capacity }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Facility Specifications</label>
                            <p class="text-muted">{{ $submission->facility_specs }}</p>
                        </div>
                        @if($submission->certifications && count($submission->certifications) > 0)
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Certifications</label>
                            <ul class="list-unstyled">
                                @foreach($submission->certifications as $cert)
                                    <li class="text-muted"><i class="ri-checkbox-circle-line text-success"></i> {{ $cert }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                @elseif($type === 'processor')
                    {{-- Processor Details --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Processing Capacity</label>
                            <p class="text-muted">{{ number_format($submission->processing_capacity, 1) }} units</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Equipment Type</label>
                            <p class="text-muted">{{ $submission->equipment_type }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Equipment Specifications</label>
                            <p class="text-muted">{{ $submission->equipment_specs }}</p>
                        </div>
                        @if($submission->processed_items)
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Processed Items</label>
                            @php
                                $items = is_string($submission->processed_items) 
                                    ? json_decode($submission->processed_items, true) 
                                    : $submission->processed_items;
                            @endphp
                            @if(is_array($items) && count($items) > 0)
                                <ul class="list-unstyled">
                                    @foreach($items as $item)
                                        <li class="text-muted"><i class="ri-checkbox-circle-line text-success"></i> {{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">{{ $submission->processed_items }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                @endif

                @if($submission->status === 'rejected' && $submission->rejection_comments)
                    <hr>
                    <div class="alert alert-danger" role="alert">
                        <h5 class="alert-heading"><i class="ri-error-warning-line"></i> Rejection Comments</h5>
                        <p class="mb-0">{{ $submission->rejection_comments }}</p>
                    </div>
                @endif

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('farmers.submissions') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line"></i> Back to Submissions
                    </a>
                    
                    @if($submission->status === 'approved')
                        <button class="btn btn-success" disabled>
                            <i class="ri-checkbox-circle-line"></i> Approved
                        </button>
                    @elseif($submission->status === 'pending')
                        <button class="btn btn-warning" disabled>
                            <i class="ri-time-line"></i> Under Review
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection