@extends('layouts.enrollment_agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Farmer Profile: {{ $farmer->full_name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('enrollment.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enrollment.farmers.index') }}">Farmers</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif -->

    <!-- Enhanced Status Alert with Actions -->
    <div class="row">
        <div class="col-12">
            @php
                $statusConfig = [
                    'pending_lga_review' => [
                        'class' => 'alert-warning', 
                        'icon' => 'ri-time-line', 
                        'text' => 'Awaiting LGA Review',
                        'description' => 'Your submission is pending review by LGA Admin.'
                    ],
                    'rejected' => [
                        'class' => 'alert-danger', 
                        'icon' => 'ri-close-circle-line', 
                        'text' => 'Submission Rejected',
                        'description' => 'LGA Admin has rejected this submission.'
                    ],
                    'pending_activation' => [
                        'class' => 'alert-info', 
                        'icon' => 'ri-user-add-line', 
                        'text' => 'Approved - Ready for Activation',
                        'description' => 'Profile approved! Farmer can now login.'
                    ],
                    'active' => [
                        'class' => 'alert-success', 
                        'icon' => 'ri-checkbox-circle-line', 
                        'text' => 'Active Farmer',
                        'description' => 'Farmer has activated their account.'
                    ],
                    'suspended' => [
                        'class' => 'alert-secondary', 
                        'icon' => 'ri-pause-circle-line', 
                        'text' => 'Account Suspended',
                        'description' => 'This account has been suspended by administration.'
                    ]
                ][$farmer->status] ?? ['class' => 'alert-secondary', 'icon' => 'ri-information-line', 'text' => 'Unknown Status', 'description' => ''];
            @endphp
            
            <div class="alert {{ $statusConfig['class'] }} alert-dismissible fade show" role="alert">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <i class="{{ $statusConfig['icon'] }} me-2 fs-16"></i>
                            <strong class="me-3">Status: {{ $statusConfig['text'] }}</strong>
                            @if($farmer->status === 'pending_activation' || $farmer->status === 'active')
                                <a href="{{ route('enrollment.farmers.credentials', $farmer) }}" 
                                   class="btn btn-sm btn-outline-{{ $farmer->status === 'pending_activation' ? 'info' : 'success' }}">
                                     View Credentials
                                </a>
                            @endif
                        </div>
                        
                        @if($farmer->status === 'rejected' && $farmer->rejection_reason)
                            <div class="mb-2">
                                <strong>Rejection Reason:</strong> {{ $farmer->rejection_reason }}
                            </div>
                        @endif
                        
                        <div class="text-muted small">
                            <i class="ri-information-line me-1"></i>
                            {{ $statusConfig['description'] }}
                        </div>
                    </div>
                    
                    @if(in_array($farmer->status, ['pending_activation', 'active']))
                        <div class="flex-shrink-0 ms-3">
                            <a href="{{ route('enrollment.farmers.farmlands.create', $farmer) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="ri-add-circle-line me-1"></i> Add Farmland
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('enrollment.farmers.index') }}" class="btn btn-secondary waves-effect waves-light">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
                <div class="d-flex gap-2">
                    @if(in_array($farmer->status, ['pending_lga_review', 'rejected']))
                        <a href="{{ route('enrollment.farmers.edit', $farmer) }}" class="btn btn-warning waves-effect waves-light">
                            <i class="ri-pencil-line align-middle me-1"></i> 
                            {{ $farmer->status === 'rejected' ? 'Resubmit' : 'Edit' }}
                        </a>
                    @endif
                    
                    @if(in_array($farmer->status, ['pending_activation', 'active']))
                        <a href="{{ route('enrollment.farmers.credentials', $farmer) }}" class="btn btn-info waves-effect waves-light">
                             View Credentials
                        </a>
                        
                        <a href="{{ route('enrollment.farmers.farmlands.create', $farmer) }}" class="btn btn-primary waves-effect waves-light">
                             Add Farmland
                        </a>
                    @endif
                    
                    @if(in_array($farmer->status, ['pending_lga_review', 'rejected']))
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#deleteModal">
                             Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Interface -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personal" role="tab">
                                <i class="ri-user-line fs-20 d-block mb-1"></i>
                                <span class="d-none d-sm-block">Personal Info</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#farmlands" role="tab">
                                <i class="ri-map-pin-line fs-20 d-block mb-1"></i>
                                <span class="d-none d-sm-block">Farmlands ({{ $farmer->farmLands->count() }})</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#review" role="tab">
                                <i class="ri-history-line fs-20 d-block mb-1"></i>
                                <span class="d-none d-sm-block">Review History</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane active" id="personal" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="ri-user-line align-middle me-2"></i>Personal Information
                                            </h5>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr>
                                                        <td class="fw-bold" width="40%">Full Name:</td>
                                                        <td>{{ $farmer->full_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">NIN:</td>
                                                        <td>{{ $farmer->nin }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Email:</td>
                                                        <td>{{ $farmer->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Primary Phone:</td>
                                                        <td>{{ $farmer->phone_primary }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Secondary Phone:</td>
                                                        <td>{{ $farmer->phone_secondary ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Date of Birth:</td>
                                                        <td>{{ $farmer->date_of_birth->format('M d, Y') }} ({{ $farmer->age }} years)</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Gender:</td>
                                                        <td>{{ ucfirst($farmer->gender) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Marital Status:</td>
                                                        <td>{{ ucfirst($farmer->marital_status) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location Information -->
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="ri-map-pin-line align-middle me-2"></i>Location Information
                                            </h5>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr>
                                                        <td class="fw-bold" width="40%">LGA:</td>
                                                        <td>{{ $farmer->lga->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Ward:</td>
                                                        <td>{{ $farmer->ward }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Residential Address:</td>
                                                        <td>{{ $farmer->residential_address }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Coordinates:</td>
                                                        <td>
                                                            @if($farmer->residence_latitude && $farmer->residence_longitude)
                                                                {{ number_format($farmer->residence_latitude, 6) }}, {{ number_format($farmer->residence_longitude, 6) }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <!-- Socio-Economic Profile -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="ri-community-line align-middle me-2"></i>Socio-Economic Profile
                                            </h5>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr>
                                                        <td class="fw-bold" width="40%">Education Level:</td>
                                                        <td>{{ ucfirst($farmer->educational_level) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Household Size:</td>
                                                        <td>{{ $farmer->household_size }} person(s)</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Primary Occupation:</td>
                                                        <td>
                                                            {{ ucwords(str_replace('_', ' ', $farmer->primary_occupation)) }}
                                                            @if($farmer->primary_occupation === 'other' && $farmer->other_occupation)
                                                                - {{ $farmer->other_occupation }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Cooperative:</td>
                                                        <td>{{ $farmer->cooperative->name ?? 'Individual Farmer (No Cooperative)' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Media Files -->
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="ri-image-line align-middle me-2"></i>Verification Media
                                            </h5>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Farmer Photo</label>
                                                    <div>
                                                        @if($farmer->farmer_photo)
                                                            <a href="{{ Storage::url($farmer->farmer_photo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                View Photo
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Farm Photo</label>
                                                    <div>
                                                        @if(isset($farmer->additional_info['farm_photo_path']))
                                                            <a href="{{ Storage::url($farmer->additional_info['farm_photo_path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                 View Farm Photo
                                                            </a>
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Farmlands Tab -->
                        <div class="tab-pane" id="farmlands" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Farmland Details</h5>
                                @if(in_array($farmer->status, ['pending_activation', 'active']))
                                    <a href="{{ route('enrollment.farmers.farmlands.create', $farmer) }}" class="btn btn-primary btn-sm">
                                         Add New Farmland
                                    </a>
                                @endif
                            </div>

                            @if($farmer->farmLands->count() > 0)
                                <div class="row">
                                    @foreach($farmer->farmLands as $farmLand)
                                        <div class="col-lg-6 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <h6 class="card-title text-primary mb-0">{{ $farmLand->name }}</h6>
                                                        <span class="badge bg-info">{{ ucfirst($farmLand->farm_type) }}</span>
                                                    </div>
                                                    
                                                    <div class="table-responsive">
                                                        <table class="table table-borderless table-sm mb-3">
                                                            <tr>
                                                                <td class="fw-bold" width="40%">Size:</td>
                                                                <td>{{ number_format($farmLand->total_size_hectares, 2) }} hectares</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-bold">Ownership:</td>
                                                                <td>{{ ucfirst($farmLand->ownership_status) }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <!-- Practice Details -->
                                                    @php
                                                        $practiceDetails = null;
                                                        switch($farmLand->farm_type) {
                                                            case 'crops':
                                                                $practiceDetails = $farmLand->cropPracticeDetails;
                                                                break;
                                                            case 'livestock':
                                                                $practiceDetails = $farmLand->livestockPracticeDetails;
                                                                break;
                                                            case 'fisheries':
                                                                $practiceDetails = $farmLand->fisheriesPracticeDetails;
                                                                break;
                                                            case 'orchards':
                                                                $practiceDetails = $farmLand->orchardPracticeDetails;
                                                                break;
                                                        }
                                                    @endphp

                                                    @if($practiceDetails)
                                                        <div class="border-top pt-3">
                                                            <h6 class="text-muted mb-2">Practice Details:</h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-borderless table-sm mb-0">
                                                                    @switch($farmLand->farm_type)
                                                                        @case('crops')
                                                                            <tr><td class="fw-bold">Crop Type:</td><td>{{ $practiceDetails->crop_type }}</td></tr>
                                                                            <tr><td class="fw-bold">Variety:</td><td>{{ $practiceDetails->variety ?? 'N/A' }}</td></tr>
                                                                            <tr><td class="fw-bold">Farming Method:</td><td>{{ ucwords(str_replace('_', ' ', $practiceDetails->farming_method)) }}</td></tr>
                                                                            <tr><td class="fw-bold">Expected Yield:</td><td>{{ $practiceDetails->expected_yield_kg ? number_format($practiceDetails->expected_yield_kg) . ' kg' : 'N/A' }}</td></tr>
                                                                            @break
                                                                        
                                                                        @case('livestock')
                                                                            <tr><td class="fw-bold">Animal Type:</td><td>{{ $practiceDetails->animal_type }}</td></tr>
                                                                            <tr><td class="fw-bold">Herd/Flock Size:</td><td>{{ number_format($practiceDetails->herd_flock_size) }}</td></tr>
                                                                            <tr><td class="fw-bold">Breeding Practice:</td><td>{{ ucwords(str_replace('_', ' ', $practiceDetails->breeding_practice)) }}</td></tr>
                                                                            @break
                                                                        
                                                                        @case('fisheries')
                                                                            <tr><td class="fw-bold">Fishing Type:</td><td>{{ ucwords(str_replace('_', ' ', $practiceDetails->fishing_type)) }}</td></tr>
                                                                            <tr><td class="fw-bold">Species Raised:</td><td>{{ $practiceDetails->species_raised }}</td></tr>
                                                                            <tr><td class="fw-bold">Pond Size:</td><td>{{ $practiceDetails->pond_size_sqm ? number_format($practiceDetails->pond_size_sqm) . ' m²' : 'N/A' }}</td></tr>
                                                                            <tr><td class="fw-bold">Expected Harvest:</td><td>{{ $practiceDetails->expected_harvest_kg ? number_format($practiceDetails->expected_harvest_kg) . ' kg' : 'N/A' }}</td></tr>
                                                                            @break
                                                                        
                                                                        @case('orchards')
                                                                            <tr><td class="fw-bold">Tree Type:</td><td>{{ $practiceDetails->tree_type }}</td></tr>
                                                                            <tr><td class="fw-bold">Number of Trees:</td><td>{{ number_format($practiceDetails->number_of_trees) }}</td></tr>
                                                                            <tr><td class="fw-bold">Maturity Stage:</td><td>{{ ucfirst($practiceDetails->maturity_stage) }}</td></tr>
                                                                            @break
                                                                    @endswitch
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="ri-map-pin-line display-4 text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">No Farmlands Added</h5>
                                    <p class="text-muted mb-4">This farmer doesn't have any farmlands registered yet.</p>
                                    @if(in_array($farmer->status, ['pending_activation', 'active']))
                                        <a href="{{ route('enrollment.farmers.farmlands.create', $farmer) }}" class="btn btn-primary">
                                            <i class="ri-add-circle-line me-1"></i> Add First Farmland
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Review History Tab -->
                        <div class="tab-pane" id="review" role="tabpanel">
                            <h5 class="card-title mb-4">Review & Approval History</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">Submission Timeline</h6>
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tr>
                                                        <td class="fw-bold" width="40%">Submitted By:</td>
                                                        <td>{{ $farmer->enrolledBy->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Submission Date:</td>
                                                        <td>{{ $farmer->created_at->format('M d, Y \a\t h:i A') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Last Updated:</td>
                                                        <td>{{ $farmer->updated_at->format('M d, Y \a\t h:i A') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">LGA Review</h6>
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-sm mb-0">
                                                    @if($farmer->approved_by)
                                                        <tr>
                                                            <td class="fw-bold" width="40%">Approved By:</td>
                                                            <td>{{ $farmer->approvedBy->name ?? 'N/A' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">Approval Date:</td>
                                                            <td>{{ $farmer->approved_at?->format('M d, Y \a\t h:i A') ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endif
                                                    @if($farmer->activated_at)
                                                        <tr>
                                                            <td class="fw-bold">Activated Date:</td>
                                                            <td>{{ $farmer->activated_at->format('M d, Y \a\t h:i A') }}</td>
                                                        </tr>
                                                    @endif
                                                    @if($farmer->rejection_reason)
                                                        <tr>
                                                            <td class="fw-bold text-danger">Rejection Reason:</td>
                                                            <td class="text-danger">{{ $farmer->rejection_reason }}</td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Timeline -->
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Status Timeline</h6>
                                    <div class="activity-timeline">
                                        <div class="activity-item d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xs activity-avatar">
                                                    <div class="avatar-title bg-success rounded-circle">
                                                        <i class="ri-user-add-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">Profile Created</h6>
                                                <p class="text-muted mb-0">Farmer profile submitted for review</p>
                                                <small class="text-muted">{{ $farmer->created_at->format('M d, Y \a\t h:i A') }}</small>
                                            </div>
                                        </div>

                                        @if($farmer->approved_at)
                                            <div class="activity-item d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xs activity-avatar">
                                                        <div class="avatar-title bg-info rounded-circle">
                                                            <i class="ri-check-double-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Approved by LGA</h6>
                                                    <p class="text-muted mb-0">Profile approved and user account created</p>
                                                    <small class="text-muted">{{ $farmer->approved_at->format('M d, Y \a\t h:i A') }}</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if($farmer->activated_at)
                                            <div class="activity-item d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xs activity-avatar">
                                                        <div class="avatar-title bg-success rounded-circle">
                                                            <i class="ri-user-check-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Account Activated</h6>
                                                    <p class="text-muted mb-0">Farmer logged in and activated account</p>
                                                    <small class="text-muted">{{ $farmer->activated_at->format('M d, Y \a\t h:i A') }}</small>
                                                </div>
                                            </div>
                                        @endif

                                        @if($farmer->status === 'rejected')
                                            <div class="activity-item d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-xs activity-avatar">
                                                        <div class="avatar-title bg-danger rounded-circle">
                                                            <i class="ri-close-line"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Submission Rejected</h6>
                                                    <p class="text-muted mb-0">{{ $farmer->rejection_reason }}</p>
                                                    <small class="text-muted">{{ $farmer->updated_at->format('M d, Y \a\t h:i A') }}</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if(in_array($farmer->status, ['pending_lga_review', 'rejected']))
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the farmer profile for <strong>{{ $farmer->full_name }}</strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone. All associated farm data will be permanently deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('enrollment.farmers.destroy', $farmer) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Farmer Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.activity-timeline {
    position: relative;
    padding-left: 20px;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 16px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.activity-item {
    position: relative;
    padding-bottom: 24px;
}

.activity-item:last-child {
    padding-bottom: 0;
}

.activity-avatar {
    position: relative;
    z-index: 2;
}

.farm-land-details {
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    padding: 1rem;
    border: 1px solid #e9ecef;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tabs if needed
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    });
});
</script>
@endpush