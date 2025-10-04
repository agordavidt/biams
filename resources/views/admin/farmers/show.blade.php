@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Farmer Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.farmers.index') }}">Farmers</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Farmer Information</h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Full Name:</strong> {{ $farmer->full_name }}</p>
                        <p><strong>NIN:</strong> {{ $farmer->nin }}</p>
                        <p><strong>Email:</strong> {{ $farmer->email }}</p>
                        <p><strong>Phone:</strong> {{ $farmer->phone_primary }}</p>
                        <p><strong>Gender:</strong> {{ ucfirst($farmer->gender) }}</p>
                        <p><strong>Date of Birth:</strong> {{ $farmer->date_of_birth->format('M d, Y') }}</p>
                        <p><strong>Age:</strong> {{ $farmer->age }} years</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Marital Status:</strong> {{ ucfirst($farmer->marital_status) }}</p>
                        <p><strong>Education Level:</strong> {{ ucfirst($farmer->educational_level) }}</p>
                        <p><strong>Occupation:</strong> {{ ucfirst(str_replace('_', ' ', $farmer->primary_occupation)) }}</p>
                        <p><strong>Household Size:</strong> {{ $farmer->household_size }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $farmer->status == 'active' ? 'success' : ($farmer->status == 'pending_lga_review' ? 'warning' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $farmer->status)) }}
                            </span>
                        </p>
                    </div>
                </div>

                <hr>
                <h5 class="mb-3">Location Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>LGA:</strong> {{ $farmer->lga->name }}</p>
                        <p><strong>Ward:</strong> {{ $farmer->ward }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Address:</strong> {{ $farmer->residential_address }}</p>
                    </div>
                </div>

                @if($farmer->cooperative)
                <hr>
                <h5 class="mb-3">Cooperative Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Cooperative:</strong> {{ $farmer->cooperative->name }}</p>
                        <p><strong>Registration No:</strong> {{ $farmer->cooperative->registration_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Contact Person:</strong> {{ $farmer->cooperative->contact_person ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $farmer->cooperative->phone ?? 'N/A' }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Farm Lands -->
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Farm Lands</h4>
                @foreach($farmer->farmLands as $farmLand)
                <div class="border rounded p-3 mb-3">
                    <h6>{{ $farmLand->name }} <span class="badge bg-primary text-capitalize">{{ $farmLand->farm_type }}</span></h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Size:</strong> {{ $farmLand->total_size_hectares }} hectares</p>
                            <p><strong>Ownership:</strong> {{ ucfirst($farmLand->ownership_status) }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($farmLand->cropPracticeDetails)
                            <p><strong>Crop Type:</strong> {{ $farmLand->cropPracticeDetails->crop_type }}</p>
                            <p><strong>Farming Method:</strong> {{ ucfirst(str_replace('_', ' ', $farmLand->cropPracticeDetails->farming_method)) }}</p>
                            @elseif($farmLand->livestockPracticeDetails)
                            <p><strong>Animal Type:</strong> {{ $farmLand->livestockPracticeDetails->animal_type }}</p>
                            <p><strong>Herd Size:</strong> {{ $farmLand->livestockPracticeDetails->herd_flock_size }}</p>
                            @elseif($farmLand->fisheriesPracticeDetails)
                            <p><strong>Species:</strong> {{ $farmLand->fisheriesPracticeDetails->species_raised }}</p>
                            <p><strong>Pond Size:</strong> {{ $farmLand->fisheriesPracticeDetails->pond_size_sqm }} m²</p>
                            @elseif($farmLand->orchardPracticeDetails)
                            <p><strong>Tree Type:</strong> {{ $farmLand->orchardPracticeDetails->tree_type }}</p>
                            <p><strong>Number of Trees:</strong> {{ $farmLand->orchardPracticeDetails->number_of_trees }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Enrollment Information</h4>
                <p><strong>Enrolled By:</strong> {{ $farmer->enrolledBy->name ?? 'N/A' }}</p>
                <p><strong>Enrollment Date:</strong> {{ $farmer->created_at->format('M d, Y') }}</p>
                
                @if($farmer->approvedBy)
                <p><strong>Approved By:</strong> {{ $farmer->approvedBy->name }}</p>
                <p><strong>Approval Date:</strong> {{ $farmer->approved_at?->format('M d, Y') ?? 'N/A' }}</p>
                @endif

                @if($farmer->activated_at)
                <p><strong>Activated Date:</strong> {{ $farmer->activated_at->format('M d, Y') }}</p>
                @endif

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('admin.farmers.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection