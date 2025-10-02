@extends('layouts.lga_admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Review Farmer Profile: {{ $farmer->full_name }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('lga_admin.farmers.index') }}" class="btn btn-secondary waves-effect waves-light">Back to Review List</a>
                </div>
            </div>
        </div>
    </div>

    @if($farmer->status === 'rejected')
        <div class="alert alert-danger mb-4">
            <h5 class="alert-heading"><i class="ri-close-circle-line me-2"></i> This Profile Was Rejected.</h5>
            <p class="mb-0">**Reason for Rejection:** {{ $farmer->rejection_reason }}</p>
            <p class="mt-2 mb-0">The Enrollment Agent ({{ $farmer->enrolledBy->name ?? 'N/A' }}) has been notified and can now **edit and resubmit** the profile.</p>
        </div>
    @endif
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex gap-2">
                @if($farmer->status === 'pending_lga_review')
                    <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="ri-check-line me-1"></i> Approve Profile
                    </button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="ri-forbid-line me-1"></i> Reject & Send Back
                    </button>
                @elseif($farmer->status === 'pending_activation')
                    <button type="button" class="btn btn-info waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#activateModal">
                        <i class="ri-user-follow-line me-1"></i> Final Activate Account
                    </button>
                @elseif($farmer->status === 'active')
                    <span class="btn btn-success waves-effect waves-light disabled">
                        <i class="ri-lock-line me-1"></i> Account is Active
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
                                    <div class="col-md-12 mt-3">
                                        <p class="mb-1 text-muted">Geolocation (GeoJSON Snippet):</p>
                                        <pre class="bg-light p-2 rounded small">{{ substr($farmLand->geolocation_geojson, 0, 150) }}...</pre>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="tab-pane" id="practice" role="tabpanel">
                            <h5 class="mb-3">Farming Practice Details (First Farm)</h5>
                            @php $practiceDetails = $farmer->farmLands->first()->practiceDetails ?? null; @endphp
                            
                            @if($practiceDetails)
                                <div class="row">
                                    {{-- You would dynamically render fields based on the farm type here --}}
                                    <div class="col-md-12">
                                        <p class="mb-1 text-muted">**Key Practice Detail (e.g., Crop Type):**</p>
                                        <h6>{{ $practiceDetails->crop_type ?? $practiceDetails->animal_type ?? $practiceDetails->species_raised ?? $practiceDetails->tree_type ?? 'N/A' }}</h6>
                                    </div>
                                    </div>
                            @else
                                <div class="alert alert-info">No specific practice details found for the primary farm plot.</div>
                            @endif

                            <h5 class="mt-4 mb-3">Verification Media</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Farmer Photo/ID Proof:</p>
                                    @if($farmer->photo_id_path)
                                        <a href="{{ Storage::url($farmer->photo_id_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ri-image-line me-1"></i> View Photo</a>
                                    @else
                                        <span class="text-danger">Not Uploaded</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Farm Proof of Existence:</p>
                                    @if($farmer->farm_photo_path)
                                        <a href="{{ Storage::url($farmer->farm_photo_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ri-map-pin-line me-1"></i> View Geo-Tagged Farm Photo</a>
                                    @else
                                        <span class="text-danger">Not Uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="audit" role="tabpanel">
                            <ul class="list-unstyled">
                                <li>**Enrolled By (EO):** {{ $farmer->enrolledBy->name ?? 'N/A' }} on {{ $farmer->created_at->format('M d, Y H:i') }}</li>
                                <li>**Approved By (LGA Admin):** {{ $farmer->approvedBy->name ?? 'N/A' }}</li>
                                @if($farmer->approved_at)
                                    <li>**Approval Date:** {{ $farmer->approved_at->format('M d, Y H:i') }}</li>
                                @endif
                                @if($farmer->rejection_reason)
                                    <li>**Last Rejection Reason:** {{ $farmer->rejection_reason }}</li>
                                @endif
                                <li>**Current Status:** <span class="fw-bold">{{ ucwords(str_replace('_', ' ', $farmer->status)) }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <p class="text-danger">**Warning:** Rejecting this profile will send it back to the Enrollment Agent for correction. You MUST provide a clear reason.</p>
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason *</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required></textarea>
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

    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('lga_admin.farmers.approve', $farmer) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Approve Enrollment Submission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to **Approve** this profile? This confirms the data is verified and moves the status to **Pending Activation**.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Yes, Approve Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="activateModal" tabindex="-1" aria-labelledby="activateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('lga_admin.farmers.activate', $farmer) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="activateModalLabel">Final Account Activation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-info">**Action:** This will create the **Farmer User Account**, assign the initial password, and transition the status to **Active**.</p>
                        <p>The system will use the farmer's registered **Email** and **Phone** for notification.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Yes, Activate Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection