@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">My Profile</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                @if($farmer->farmer_photo)
                    <img src="{{ asset('storage/' . $farmer->farmer_photo) }}" 
                         alt="Profile Photo" class="rounded-circle img-thumbnail mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-3x text-muted"></i>
                    </div>
                @endif
                
                <h4 class="mb-1">{{ $farmer->full_name }}</h4>
                <p class="text-muted mb-2">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ $farmer->ward }}, {{ $farmer->lga->name }}
                </p>
                
                <div class="badge bg-success mb-3">
                    <i class="fas fa-check-circle me-1"></i>
                    Verified Farmer
                </div>
                
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-qrcode me-1"></i>
                        View Farmer ID
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Account Information</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Member ID:</dt>
                    <dd class="col-sm-7">FARM{{ str_pad($farmer->id, 6, '0', STR_PAD_LEFT) }}</dd>
                    
                    <dt class="col-sm-5">NIN:</dt>
                    <dd class="col-sm-7">{{ $farmer->nin }}</dd>
                    
                    <dt class="col-sm-5">Status:</dt>
                    <dd class="col-sm-7">
                        <span class="badge bg-success">Active</span>
                    </dd>
                    
                    <dt class="col-sm-5">Joined:</dt>
                    <dd class="col-sm-7">{{ $farmer->created_at->format('M d, Y') }}</dd>
                    
                    <dt class="col-sm-5">Phone:</dt>
                    <dd class="col-sm-7">{{ $farmer->phone_primary }}</dd>
                    
                    @if($farmer->phone_secondary)
                    <dt class="col-sm-5">Alt Phone:</dt>
                    <dd class="col-sm-7">{{ $farmer->phone_secondary }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Edit Profile Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Profile Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('farmer.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_primary" class="form-label">Primary Phone *</label>
                            <input type="text" class="form-control @error('phone_primary') is-invalid @enderror" 
                                   id="phone_primary" name="phone_primary" 
                                   value="{{ old('phone_primary', $farmer->phone_primary) }}" required>
                            @error('phone_primary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone_secondary" class="form-label">Secondary Phone</label>
                            <input type="text" class="form-control @error('phone_secondary') is-invalid @enderror" 
                                   id="phone_secondary" name="phone_secondary" 
                                   value="{{ old('phone_secondary', $farmer->phone_secondary) }}">
                            @error('phone_secondary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="residential_address" class="form-label">Residential Address *</label>
                        <textarea class="form-control @error('residential_address') is-invalid @enderror" 
                                  id="residential_address" name="residential_address" 
                                  rows="3" required>{{ old('residential_address', $farmer->residential_address) }}</textarea>
                        @error('residential_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="educational_level" class="form-label">Education Level *</label>
                            <select class="form-select @error('educational_level') is-invalid @enderror" 
                                    id="educational_level" name="educational_level" required>
                                <option value="">Select Level</option>
                                <option value="none" {{ old('educational_level', $farmer->educational_level) == 'none' ? 'selected' : '' }}>No Formal Education</option>
                                <option value="primary" {{ old('educational_level', $farmer->educational_level) == 'primary' ? 'selected' : '' }}>Primary School</option>
                                <option value="secondary" {{ old('educational_level', $farmer->educational_level) == 'secondary' ? 'selected' : '' }}>Secondary School</option>
                                <option value="tertiary" {{ old('educational_level', $farmer->educational_level) == 'tertiary' ? 'selected' : '' }}>Tertiary Institution</option>
                            </select>
                            @error('educational_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="household_size" class="form-label">Household Size *</label>
                            <input type="number" class="form-control @error('household_size') is-invalid @enderror" 
                                   id="household_size" name="household_size" 
                                   value="{{ old('household_size', $farmer->household_size) }}" 
                                   min="1" max="50" required>
                            @error('household_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="primary_occupation" class="form-label">Primary Occupation *</label>
                        <input type="text" class="form-control @error('primary_occupation') is-invalid @enderror" 
                               id="primary_occupation" name="primary_occupation" 
                               value="{{ old('primary_occupation', $farmer->primary_occupation) }}" required>
                        @error('primary_occupation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('farmer.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection