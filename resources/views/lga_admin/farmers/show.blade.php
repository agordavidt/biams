@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Review Farmer Profile: {{ $farmer->full_name }}</h4>
            <div class="page-title-right">
                <a href="{{ route('lga_admin.farmers.index') }}" class="btn btn-secondary waves-effect waves-light">
                    <i class="ri-arrow-left-line me-1"></i> Back to Review List
                </a>
            </div>
        </div>
    </div>
</div>

@if($farmer->status === 'rejected')
    <div class="alert alert-danger mb-4">
        <h5 class="alert-heading"><i class="ri-close-circle-line me-2"></i> This Profile Was Rejected.</h5>
        <p class="mb-0"><strong>Reason for Rejection:</strong> {{ $farmer->rejection_reason }}</p>
        <p class="mt-2 mb-0">The Enrollment Agent ({{ $farmer->enrolledBy->name ?? 'N/A' }}) has been notified and can now edit and resubmit the profile.</p>
    </div>
@endif

@if($farmer->status === 'pending_activation')
    <div class="alert alert-info mb-4">
        <h5 class="alert-heading"><i class="ri-user-add-line me-2"></i> Profile Approved - Ready for Farmer Login</h5>
        <p class="mb-0">This farmer profile has been approved and a user account has been created.</p>
        <p class="mt-2 mb-0">
            <strong>Farmer can login with:</strong><br>
            Email: {{ $farmer->email }}<br>
            Password: {{ $farmer->initial_password }}
        </p>
        <div class="mt-2">
            <a href="{{ route('lga_admin.farmers.view-credentials', $farmer) }}" class="btn btn-sm btn-outline-primary">
                <i class="ri-key-line me-1"></i> View Credentials
            </a>
        </div>
    </div>
@endif

@if($farmer->status === 'active')
    <div class="alert alert-success mb-4">
        <h5 class="alert-heading"><i class="ri-checkbox-circle-line me-2"></i> Account Active</h5>
        <p class="mb-0">This farmer has successfully activated their account by logging in and changing their password.</p>
        <p class="mt-2 mb-0">
            <strong>Activated on:</strong> {{ $farmer->activated_at?->format('M d, Y H:i') ?? 'N/A' }}
        </p>
    </div>
@endif

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex gap-2">
            @if($farmer->status === 'pending_lga_review')
                <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#approveModal">
                    Approve & Create Account
                </button>
                <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#rejectModal">
                   Reject & Send Back
                </button>
            @elseif(in_array($farmer->status, ['pending_activation', 'active']))
                <a href="{{ route('lga_admin.farmers.view-credentials', $farmer) }}" class="btn btn-info waves-effect waves-light">
                    <i class="ri-key-line me-1"></i> View Login Credentials
                </a>
            @endif
            
            @if($farmer->status === 'pending_activation')
                <span class="btn btn-warning waves-effect waves-light">
                    <i class="ri-time-line me-1"></i> Awaiting First Login
                </span>
            @elseif($farmer->status === 'active')
                <span class="btn btn-success waves-effect waves-light">
                    <i class="ri-user-check-line me-1"></i> Account Active
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Profile Details (Status: <span class="fw-bold text-{{ $farmer->status === 'rejected' ? 'danger' : ($farmer->status === 'active' ? 'success' : 'warning') }}">{{ ucwords(str_replace('_', ' ', $farmer->status)) }}</span>)</h4>

                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#personal" role="tab">Personal & Contact</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#farm_data" role="tab">Farm Land Details</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#practice" role="tab">Practice & Media</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#audit" role="tab">Audit Trail</a></li>
                </ul>
                
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="personal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="mb-1 text-muted">Full Name:</p>
                                <h6 class="text-primary">{{ $farmer->full_name }}</h6>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 text-muted">NIN:</p>
                                <h6>{{ $farmer->nin }}</h6>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 text-muted">LGA:</p>
                                <h6>{{ $farmer->lga->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Primary Phone:</p>
                                <h6>{{ $farmer->phone_primary }}</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Email:</p>
                                <h6>{{ $farmer->email }}</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Secondary Phone:</p>
                                <h6>{{ $farmer->phone_secondary ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Date of Birth:</p>
                                <h6>{{ $farmer->date_of_birth->format('M d, Y') }} ({{ $farmer->age }} years)</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Gender:</p>
                                <h6>{{ ucfirst($farmer->gender) }}</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Marital Status:</p>
                                <h6>{{ ucfirst($farmer->marital_status) }}</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Education Level:</p>
                                <h6>{{ ucfirst($farmer->educational_level) }}</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Household Size:</p>
                                <h6>{{ $farmer->household_size }} person(s)</h6>
                            </div>
                            <div class="col-md-4 mt-3">
                                <p class="mb-1 text-muted">Primary Occupation:</p>
                                <h6>
                                    {{ ucwords(str_replace('_', ' ', $farmer->primary_occupation)) }}
                                    @if($farmer->primary_occupation === 'other' && $farmer->other_occupation)
                                        - {{ $farmer->other_occupation }}
                                    @endif
                                </h6>
                            </div>
                            <div class="col-md-12 mt-3">
                                <p class="mb-1 text-muted">Residential Address:</p>
                                <h6>{{ $farmer->residential_address }}</h6>
                            </div>
                            <div class="col-md-6 mt-3">
                                <p class="mb-1 text-muted">Ward:</p>
                                <h6>{{ $farmer->ward }}</h6>
                            </div>
                            <div class="col-md-6 mt-3">
                                <p class="mb-1 text-muted">Coordinates:</p>
                                <h6>
                                    @if($farmer->residence_latitude && $farmer->residence_longitude)
                                        {{ number_format($farmer->residence_latitude, 6) }}, {{ number_format($farmer->residence_longitude, 6) }}
                                    @else
                                        N/A
                                    @endif
                                </h6>
                            </div>
                            <div class="col-md-12 mt-3">
                                <p class="mb-1 text-muted">Cooperative:</p>
                                <h6>{{ $farmer->cooperative->name ?? 'Individual Farmer (No Cooperative)' }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="farm_data" role="tabpanel">
                        @foreach($farmer->farmLands as $farmLand)
                            <h6 class="mt-3">Farm Plot: {{ $farmLand->name ?? 'N/A' }}</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Farm Type:</p>
                                    <h6>{{ ucwords($farmLand->farm_type) }}</h6>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Size (Hectares):</p>
                                    <h6>{{ $farmLand->total_size_hectares }} Ha</h6>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-1 text-muted">Ownership Status:</p>
                                    <h6>{{ ucfirst($farmLand->ownership_status) }}</h6>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <p class="mb-1 text-muted">Geolocation (GeoJSON Snippet):</p>
                                    <pre class="bg-light p-2 rounded small">{{ substr($farmLand->geolocation_geojson, 0, 150) }}...</pre>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="my-4">
                            @endif
                        @endforeach
                        
                        @if($farmer->farmLands->count() === 0)
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                No farm lands registered for this farmer.
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane" id="practice" role="tabpanel">
                        <h5 class="mb-3">Farming Practice Details</h5>
                        @php $practiceDetails = $farmer->farmLands->first()->practiceDetails ?? null; @endphp
                        
                        @if($practiceDetails)
                            <div class="row">
                                @switch($farmer->farmLands->first()->farm_type)
                                    @case('crops')
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Crop Type:</p>
                                            <h6>{{ $practiceDetails->crop_type }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Variety:</p>
                                            <h6>{{ $practiceDetails->variety ?? 'N/A' }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Farming Method:</p>
                                            <h6>{{ ucwords(str_replace('_', ' ', $practiceDetails->farming_method)) }}</h6>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <p class="mb-1 text-muted">Expected Yield:</p>
                                            <h6>{{ $practiceDetails->expected_yield_kg ? number_format($practiceDetails->expected_yield_kg) . ' kg' : 'N/A' }}</h6>
                                        </div>
                                        @break
                                    
                                    @case('livestock')
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Animal Type:</p>
                                            <h6>{{ $practiceDetails->animal_type }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Herd/Flock Size:</p>
                                            <h6>{{ number_format($practiceDetails->herd_flock_size) }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Breeding Practice:</p>
                                            <h6>{{ ucwords(str_replace('_', ' ', $practiceDetails->breeding_practice)) }}</h6>
                                        </div>
                                        @break
                                    
                                    @case('fisheries')
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Fishing Type:</p>
                                            <h6>{{ ucwords(str_replace('_', ' ', $practiceDetails->fishing_type)) }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Species Raised:</p>
                                            <h6>{{ $practiceDetails->species_raised }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Pond Size:</p>
                                            <h6>{{ $practiceDetails->pond_size_sqm ? number_format($practiceDetails->pond_size_sqm) . ' m²' : 'N/A' }}</h6>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <p class="mb-1 text-muted">Expected Harvest:</p>
                                            <h6>{{ $practiceDetails->expected_harvest_kg ? number_format($practiceDetails->expected_harvest_kg) . ' kg' : 'N/A' }}</h6>
                                        </div>
                                        @break
                                    
                                    @case('orchards')
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Tree Type:</p>
                                            <h6>{{ $practiceDetails->tree_type }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Number of Trees:</p>
                                            <h6>{{ number_format($practiceDetails->number_of_trees) }}</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted">Maturity Stage:</p>
                                            <h6>{{ ucfirst($practiceDetails->maturity_stage) }}</h6>
                                        </div>
                                        @break
                                @endswitch
                            </div>
                        @else
                            <div class="alert alert-info">No specific practice details found for the primary farm plot.</div>
                        @endif

                        <h5 class="mt-4 mb-3">Verification Media</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Farmer Photo/ID Proof:</p>
                                @if($farmer->farmer_photo)
                                    <a href="{{ Storage::url($farmer->farmer_photo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-image-line me-1"></i> View Photo
                                    </a>
                                @else
                                    <span class="text-danger">Not Uploaded</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">Farm Proof of Existence:</p>
                                @if(isset($farmer->additional_info['farm_photo_path']))
                                    <a href="{{ Storage::url($farmer->additional_info['farm_photo_path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-map-pin-line me-1"></i> View Farm Photo
                                    </a>
                                @else
                                    <span class="text-danger">Not Uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="audit" role="tabpanel">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Enrolled By (EO):</strong> 
                                {{ $farmer->enrolledBy->name ?? 'N/A' }} on {{ $farmer->created_at->format('M d, Y H:i') }}
                            </li>
                            <li class="mb-2">
                                <strong>Approved By (LGA Admin):</strong> 
                                {{ $farmer->approvedBy->name ?? 'N/A' }}
                            </li>
                            @if($farmer->approved_at)
                                <li class="mb-2">
                                    <strong>Approval Date:</strong> 
                                    {{ $farmer->approved_at->format('M d, Y H:i') }}
                                </li>
                            @endif
                            @if($farmer->activated_at)
                                <li class="mb-2">
                                    <strong>Activated Date:</strong> 
                                    {{ $farmer->activated_at->format('M d, Y H:i') }}
                                </li>
                            @endif
                            @if($farmer->rejection_reason)
                                <li class="mb-2">
                                    <strong>Last Rejection Reason:</strong> 
                                    {{ $farmer->rejection_reason }}
                                </li>
                            @endif
                            <li class="mb-2">
                                <strong>Current Status:</strong> 
                                <span class="fw-bold">{{ ucwords(str_replace('_', ' ', $farmer->status)) }}</span>
                            </li>
                            @if($farmer->initial_password && $farmer->status === 'pending_activation')
                                <li class="mb-2">
                                    <strong>Temporary Password:</strong> 
                                    <code>{{ $farmer->initial_password }}</code>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Updated Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lga_admin.farmers.approve', $farmer) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Enrollment & Create Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>This action will:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Approve the farmer profile</li>
                            <li>Create a user account for the farmer</li>
                            <li>Generate login credentials</li>
                            <li>Make the account ready for first login</li>
                        </ul>
                    </div>
                    <p>The farmer will be able to login with:</p>
                    <div class="bg-light p-3 rounded">
                        <strong>Email:</strong> {{ $farmer->email }}<br>
                        <strong>Password:</strong> Will be generated and displayed after approval
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Yes, Approve & Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('lga_admin.farmers.reject', $farmer) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Enrollment Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger"><strong>Warning:</strong> Rejecting this profile will send it back to the Enrollment Agent for correction.</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason *</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required 
                                  placeholder="Please provide a clear reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection