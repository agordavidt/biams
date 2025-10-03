@extends('layouts.enrollment_agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Farmer Profile Details</h4>
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

    @if(session('success'))
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
    @endif

    <!-- Status Alert -->
    <div class="row">
        <div class="col-12">
            @php
                $statusConfig = [
                    'pending_lga_review' => ['class' => 'alert-warning', 'icon' => 'ri-time-line', 'text' => 'Awaiting LGA Review'],
                    'rejected' => ['class' => 'alert-danger', 'icon' => 'ri-close-circle-line', 'text' => 'Submission Rejected'],
                    'pending_activation' => ['class' => 'alert-info', 'icon' => 'ri-user-add-line', 'text' => 'Approved - Pending Activation'],
                    'active' => ['class' => 'alert-success', 'icon' => 'ri-checkbox-circle-line', 'text' => 'Active Farmer'],
                ][$farmer->status] ?? ['class' => 'alert-secondary', 'icon' => 'ri-information-line', 'text' => 'Unknown Status'];
            @endphp
            <div class="alert {{ $statusConfig['class'] }} alert-dismissible fade show" role="alert">
                <i class="{{ $statusConfig['icon'] }} me-2"></i>
                <strong>Status: {{ $statusConfig['text'] }}</strong>
                @if($farmer->status === 'rejected' && $farmer->rejection_reason)
                    - {{ $farmer->rejection_reason }}
                @endif
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
                <div>
                    @if(in_array($farmer->status, ['pending_lga_review', 'rejected']))
                        <a href="{{ route('enrollment.farmers.edit', $farmer) }}" class="btn btn-warning waves-effect waves-light me-2">
                            <i class="ri-pencil-line align-middle me-1"></i> Edit/Resubmit
                        </a>
                    @endif
                    
                    @if(in_array($farmer->status, ['pending_lga_review', 'rejected']))
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="ri-delete-bin-line align-middle me-1"></i> Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
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
            <div class="card mt-4">
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

        <!-- Socio-Economic & Farm Information -->
        <div class="col-lg-6">
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

            <!-- Farm Information -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-plants-line align-middle me-2"></i>Farm Information
                    </h5>
                    
                    @if($farmer->farmLands->count() > 0)
                        @foreach($farmer->farmLands as $farmLand)
                            <div class="farm-land-details {{ !$loop->first ? 'mt-4 pt-3 border-top' : '' }}">
                                <h6 class="text-primary mb-3">{{ $farmLand->name }}</h6>
                                
                                <div class="table-responsive">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tr>
                                            <td class="fw-bold" width="40%">Farm Type:</td>
                                            <td>{{ ucfirst($farmLand->farm_type) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Size:</td>
                                            <td>{{ number_format($farmLand->total_size_hectares, 2) }} hectares</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Ownership:</td>
                                            <td>{{ ucfirst($farmLand->ownership_status) }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Practice Details -->
                                @if($farmLand->practiceDetails)
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">Practice Details:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-sm mb-0">
                                                @switch($farmLand->farm_type)
                                                    @case('crops')
                                                        @if($farmLand->cropPracticeDetails)
                                                            <tr><td class="fw-bold">Crop Type:</td><td>{{ $farmLand->cropPracticeDetails->crop_type }}</td></tr>
                                                            <tr><td class="fw-bold">Variety:</td><td>{{ $farmLand->cropPracticeDetails->variety ?? 'N/A' }}</td></tr>
                                                            <tr><td class="fw-bold">Farming Method:</td><td>{{ ucwords(str_replace('_', ' ', $farmLand->cropPracticeDetails->farming_method)) }}</td></tr>
                                                            <tr><td class="fw-bold">Expected Yield:</td><td>{{ $farmLand->cropPracticeDetails->expected_yield_kg ? number_format($farmLand->cropPracticeDetails->expected_yield_kg) . ' kg' : 'N/A' }}</td></tr>
                                                        @endif
                                                        @break
                                                    
                                                    @case('livestock')
                                                        @if($farmLand->livestockPracticeDetails)
                                                            <tr><td class="fw-bold">Animal Type:</td><td>{{ $farmLand->livestockPracticeDetails->animal_type }}</td></tr>
                                                            <tr><td class="fw-bold">Herd/Flock Size:</td><td>{{ number_format($farmLand->livestockPracticeDetails->herd_flock_size) }}</td></tr>
                                                            <tr><td class="fw-bold">Breeding Practice:</td><td>{{ ucwords(str_replace('_', ' ', $farmLand->livestockPracticeDetails->breeding_practice)) }}</td></tr>
                                                        @endif
                                                        @break
                                                    
                                                    @case('fisheries')
                                                        @if($farmLand->fisheriesPracticeDetails)
                                                            <tr><td class="fw-bold">Fishing Type:</td><td>{{ ucwords(str_replace('_', ' ', $farmLand->fisheriesPracticeDetails->fishing_type)) }}</td></tr>
                                                            <tr><td class="fw-bold">Species Raised:</td><td>{{ $farmLand->fisheriesPracticeDetails->species_raised }}</td></tr>
                                                            <tr><td class="fw-bold">Pond Size:</td><td>{{ $farmLand->fisheriesPracticeDetails->pond_size_sqm ? number_format($farmLand->fisheriesPracticeDetails->pond_size_sqm) . ' m²' : 'N/A' }}</td></tr>
                                                            <tr><td class="fw-bold">Expected Harvest:</td><td>{{ $farmLand->fisheriesPracticeDetails->expected_harvest_kg ? number_format($farmLand->fisheriesPracticeDetails->expected_harvest_kg) . ' kg' : 'N/A' }}</td></tr>
                                                        @endif
                                                        @break
                                                    
                                                    @case('orchards')
                                                        @if($farmLand->orchardPracticeDetails)
                                                            <tr><td class="fw-bold">Tree Type:</td><td>{{ $farmLand->orchardPracticeDetails->tree_type }}</td></tr>
                                                            <tr><td class="fw-bold">Number of Trees:</td><td>{{ number_format($farmLand->orchardPracticeDetails->number_of_trees) }}</td></tr>
                                                            <tr><td class="fw-bold">Maturity Stage:</td><td>{{ ucfirst($farmLand->orchardPracticeDetails->maturity_stage) }}</td></tr>
                                                        @endif
                                                        @break
                                                @endswitch
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="ri-alert-line me-2"></i>No farm lands registered for this farmer.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Metadata -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="ri-history-line align-middle me-2"></i>Enrollment History
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr>
                                        <td class="fw-bold" width="40%">Enrolled By:</td>
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
                        <div class="col-md-6">
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
                                </table>
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
.status-badge {
    padding: 0.35rem 0.65rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 0.25rem;
    text-transform: capitalize;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-verified {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status-unverified {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.farm-land-details {
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    padding: 1rem;
}
</style>
@endpush