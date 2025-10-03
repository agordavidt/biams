@extends('layouts.enrollment_agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Enroll New Farmer</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('enrollment.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enrollment.farmers.index') }}">Farmers</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Farmer Enrollment Survey</h5>
                    <p class="text-muted">Complete all sections accurately. Fields marked with <span class="text-danger">*</span> are required.</p>

                    <form id="enrollment-form" action="{{ route('enrollment.farmers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Progress Indicator --}}
                        <div class="progress mb-4" style="height: 3px;">
                            <div class="progress-bar" id="progress-bar" role="progressbar" style="width: 33.33%"></div>
                        </div>

                        {{-- Tab Navigation --}}
                        <ul class="nav nav-pills nav-justified mb-3" id="enrollment-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="step1-tab" data-bs-toggle="pill" data-bs-target="#step1" type="button" role="tab">
                                    <i class="ri-user-line d-block fs-20 mb-1"></i>
                                    <span class="d-none d-sm-block">Personal Info</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="step2-tab" data-bs-toggle="pill" data-bs-target="#step2" type="button" role="tab" disabled>
                                    <i class="ri-map-pin-line d-block fs-20 mb-1"></i>
                                    <span class="d-none d-sm-block">Farm Details</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="step3-tab" data-bs-toggle="pill" data-bs-target="#step3" type="button" role="tab" disabled>
                                    <i class="ri-plants-line d-block fs-20 mb-1"></i>
                                    <span class="d-none d-sm-block">Practice & Media</span>
                                </button>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content" id="enrollment-content">
                            
                            {{-- STEP 1: Personal & Identity Information --}}
                            <div class="tab-pane fade show active" id="step1" role="tabpanel">
                                <h5 class="mb-3">Personal Information</h5>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="nin" class="form-label">NIN (National ID) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nin') is-invalid @enderror" 
                                               id="nin" name="nin" value="{{ old('nin') }}" required 
                                               maxlength="15" placeholder="e.g., 12345678901">
                                        @error('nin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                               id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="phone_primary" class="form-label">Primary Phone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('phone_primary') is-invalid @enderror" 
                                               id="phone_primary" name="phone_primary" value="{{ old('phone_primary') }}" 
                                               required placeholder="080XXXXXXXX">
                                        @error('phone_primary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="phone_secondary" class="form-label">Secondary Phone</label>
                                        <input type="tel" class="form-control @error('phone_secondary') is-invalid @enderror" 
                                               id="phone_secondary" name="phone_secondary" value="{{ old('phone_secondary') }}" 
                                               placeholder="080XXXXXXXX">
                                        @error('phone_secondary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                               id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" 
                                               required max="{{ date('Y-m-d') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select @error('gender') is-invalid @enderror" 
                                                id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('marital_status') is-invalid @enderror" 
                                                id="marital_status" name="marital_status" required>
                                            <option value="">Select Status</option>
                                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                            <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                        @error('marital_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="educational_level" class="form-label">Education Level <span class="text-danger">*</span></label>
                                        <select class="form-select @error('educational_level') is-invalid @enderror" 
                                                id="educational_level" name="educational_level" required>
                                            <option value="">Select Level</option>
                                            <option value="none" {{ old('educational_level') == 'none' ? 'selected' : '' }}>No Formal Education</option>
                                            <option value="primary" {{ old('educational_level') == 'primary' ? 'selected' : '' }}>Primary</option>
                                            <option value="secondary" {{ old('educational_level') == 'secondary' ? 'selected' : '' }}>Secondary</option>
                                            <option value="tertiary" {{ old('educational_level') == 'tertiary' ? 'selected' : '' }}>Tertiary</option>
                                            <option value="vocational" {{ old('educational_level') == 'vocational' ? 'selected' : '' }}>Vocational</option>
                                        </select>
                                        @error('educational_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Location & Socio-Economic</h5>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="lga_id" class="form-label">LGA <span class="text-danger">*</span></label>
                                        <select class="form-select @error('lga_id') is-invalid @enderror" 
                                                id="lga_id" name="lga_id" required>
                                            <option value="">Select LGA</option>
                                            @foreach($lgas as $lga)
                                                <option value="{{ $lga->id }}" {{ old('lga_id') == $lga->id ? 'selected' : '' }}>
                                                    {{ $lga->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lga_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="ward" class="form-label">Ward <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('ward') is-invalid @enderror" 
                                               id="ward" name="ward" value="{{ old('ward') }}" required>
                                        @error('ward')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="cooperative_id" class="form-label">Cooperative (Optional)</label>
                                        <select class="form-select @error('cooperative_id') is-invalid @enderror" 
                                                id="cooperative_id" name="cooperative_id">
                                            <option value="">None - Individual Farmer</option>
                                            @foreach($cooperatives as $coop)
                                                <option value="{{ $coop->id }}" {{ old('cooperative_id') == $coop->id ? 'selected' : '' }}>
                                                    {{ $coop->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cooperative_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="residential_address" class="form-label">Residential Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('residential_address') is-invalid @enderror" 
                                                  id="residential_address" name="residential_address" rows="2" required>{{ old('residential_address') }}</textarea>
                                        @error('residential_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="residence_latitude" class="form-label">Residence Latitude</label>
                                        <input type="number" step="0.00000001" class="form-control @error('residence_latitude') is-invalid @enderror" 
                                               id="residence_latitude" name="residence_latitude" value="{{ old('residence_latitude') }}" 
                                               placeholder="e.g., 7.7400">
                                        @error('residence_latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="residence_longitude" class="form-label">Residence Longitude</label>
                                        <input type="number" step="0.00000001" class="form-control @error('residence_longitude') is-invalid @enderror" 
                                               id="residence_longitude" name="residence_longitude" value="{{ old('residence_longitude') }}" 
                                               placeholder="e.g., 8.5200">
                                        @error('residence_longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="household_size" class="form-label">Household Size <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('household_size') is-invalid @enderror" 
                                               id="household_size" name="household_size" value="{{ old('household_size', 1) }}" 
                                               required min="1">
                                        @error('household_size')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="primary_occupation" class="form-label">Primary Occupation <span class="text-danger">*</span></label>
                                        <select class="form-select @error('primary_occupation') is-invalid @enderror" 
                                                id="primary_occupation" name="primary_occupation" required>
                                            <option value="">Select Occupation</option>
                                            <option value="full_time_farmer" {{ old('primary_occupation') == 'full_time_farmer' ? 'selected' : '' }}>Full-Time Farmer</option>
                                            <option value="part_time_farmer" {{ old('primary_occupation') == 'part_time_farmer' ? 'selected' : '' }}>Part-Time Farmer</option>
                                            <option value="civil_servant" {{ old('primary_occupation') == 'civil_servant' ? 'selected' : '' }}>Civil Servant</option>
                                            <option value="trader" {{ old('primary_occupation') == 'trader' ? 'selected' : '' }}>Trader</option>
                                            <option value="artisan" {{ old('primary_occupation') == 'artisan' ? 'selected' : '' }}>Artisan</option>
                                            <option value="student" {{ old('primary_occupation') == 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="other" {{ old('primary_occupation') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('primary_occupation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3" id="other_occupation_wrapper" style="display: {{ old('primary_occupation') == 'other' ? 'block' : 'none' }};">
                                        <label for="other_occupation" class="form-label">Specify Other Occupation <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('other_occupation') is-invalid @enderror" 
                                               id="other_occupation" name="other_occupation" value="{{ old('other_occupation') }}">
                                        @error('other_occupation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary" id="next-to-step2">
                                        Next: Farm Details <i class="ri-arrow-right-line"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- STEP 2: Farm & Land Details --}}
                            <div class="tab-pane fade" id="step2" role="tabpanel">
                                <h5 class="mb-3">Farm Plot Information</h5>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="name" class="form-label">Farm Name/Reference <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" 
                                               required placeholder="e.g., Home Farm, River Plot">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="farm_type" class="form-label">Farm Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('farm_type') is-invalid @enderror" 
                                                id="farm_type" name="farm_type" required>
                                            <option value="">Select Farm Type</option>
                                            <option value="crops" {{ old('farm_type') == 'crops' ? 'selected' : '' }}>Crops/Arable Farming</option>
                                            <option value="livestock" {{ old('farm_type') == 'livestock' ? 'selected' : '' }}>Livestock</option>
                                            <option value="fisheries" {{ old('farm_type') == 'fisheries' ? 'selected' : '' }}>Fisheries/Aquaculture</option>
                                            <option value="orchards" {{ old('farm_type') == 'orchards' ? 'selected' : '' }}>Orchards/Perennial</option>
                                            <option value="forestry" {{ old('farm_type') == 'forestry' ? 'selected' : '' }}>Forestry</option>
                                        </select>
                                        @error('farm_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">This determines the practice details in Step 3</small>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="total_size_hectares" class="form-label">Total Size (Hectares) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control @error('total_size_hectares') is-invalid @enderror" 
                                               id="total_size_hectares" name="total_size_hectares" value="{{ old('total_size_hectares') }}" 
                                               required min="0.01" placeholder="e.g., 2.5">
                                        @error('total_size_hectares')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="ownership_status" class="form-label">Ownership Status <span class="text-danger">*</span></label>
                                        <select class="form-select @error('ownership_status') is-invalid @enderror" 
                                                id="ownership_status" name="ownership_status" required>
                                            <option value="">Select Status</option>
                                            <option value="owned" {{ old('ownership_status') == 'owned' ? 'selected' : '' }}>Owned</option>
                                            <option value="leased" {{ old('ownership_status') == 'leased' ? 'selected' : '' }}>Leased</option>
                                            <option value="shared" {{ old('ownership_status') == 'shared' ? 'selected' : '' }}>Shared</option>
                                            <option value="communal" {{ old('ownership_status') == 'communal' ? 'selected' : '' }}>Communal</option>
                                        </select>
                                        @error('ownership_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Geospatial Data</h5>
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    Use a GPS device or mobile app to capture the farm boundaries as GeoJSON format.
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="geolocation_geojson" class="form-label">Farm Boundaries (GeoJSON) <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('geolocation_geojson') is-invalid @enderror" 
                                                  id="geolocation_geojson" name="geolocation_geojson" rows="6" required 
                                                  placeholder='{"type": "Polygon", "coordinates": [[[8.52, 7.74], [8.53, 7.74], [8.53, 7.75], [8.52, 7.75], [8.52, 7.74]]]}'>{{ old('geolocation_geojson') }}</textarea>
                                        @error('geolocation_geojson')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Paste the GeoJSON coordinates captured from your GPS tool</small>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary" id="back-to-step1">
                                        <i class="ri-arrow-left-line"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" id="next-to-step3">
                                        Next: Practice Details <i class="ri-arrow-right-line"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- STEP 3: Practice Details & Media --}}
                            <div class="tab-pane fade" id="step3" role="tabpanel">
                                <h5 class="mb-3">Farming Practice Details</h5>

                                <div id="practice-container">
                                    {{-- Default Message --}}
                                    <div id="practice-default" class="alert alert-warning" style="display: {{ old('farm_type') ? 'none' : 'block' }};">
                                        <i class="ri-alert-line me-2"></i>
                                        Please select a <strong>Farm Type</strong> in Step 2 to display the relevant practice details form.
                                    </div>

                                    {{-- Crops Practice Details --}}
                                    <div id="practice-crops" class="practice-section" style="display: {{ old('farm_type') == 'crops' ? 'block' : 'none' }};">
                                        <h6 class="mb-3">Crop Farming Details</h6>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="crop_type" class="form-label">Primary Crop Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('crop_type') is-invalid @enderror" 
                                                       id="crop_type" name="crop_type" value="{{ old('crop_type') }}" 
                                                       placeholder="e.g., Maize, Rice, Cassava">
                                                @error('crop_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="variety" class="form-label">Variety</label>
                                                <input type="text" class="form-control @error('variety') is-invalid @enderror" 
                                                       id="variety" name="variety" value="{{ old('variety') }}" 
                                                       placeholder="e.g., Yellow Maize">
                                                @error('variety')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="expected_yield_kg" class="form-label">Expected Yield (kg)</label>
                                                <input type="number" step="0.01" class="form-control @error('expected_yield_kg') is-invalid @enderror" 
                                                       id="expected_yield_kg" name="expected_yield_kg" value="{{ old('expected_yield_kg') }}" 
                                                       min="0" placeholder="e.g., 5000">
                                                @error('expected_yield_kg')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="farming_method" class="form-label">Farming Method <span class="text-danger">*</span></label>
                                                <select class="form-select @error('farming_method') is-invalid @enderror" 
                                                        id="farming_method" name="farming_method">
                                                    <option value="">Select Method</option>
                                                    <option value="irrigation" {{ old('farming_method') == 'irrigation' ? 'selected' : '' }}>Irrigation</option>
                                                    <option value="rain_fed" {{ old('farming_method') == 'rain_fed' ? 'selected' : '' }}>Rain-fed</option>
                                                    <option value="organic" {{ old('farming_method') == 'organic' ? 'selected' : '' }}>Organic</option>
                                                    <option value="mixed" {{ old('farming_method') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                                </select>
                                                @error('farming_method')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Livestock Practice Details --}}
                                    <div id="practice-livestock" class="practice-section" style="display: {{ old('farm_type') == 'livestock' ? 'block' : 'none' }};">
                                        <h6 class="mb-3">Livestock Farming Details</h6>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="animal_type" class="form-label">Animal Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('animal_type') is-invalid @enderror" 
                                                       id="animal_type" name="animal_type" value="{{ old('animal_type') }}" 
                                                       placeholder="e.g., Cattle, Goats, Poultry">
                                                @error('animal_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="herd_flock_size" class="form-label">Herd/Flock Size <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('herd_flock_size') is-invalid @enderror" 
                                                       id="herd_flock_size" name="herd_flock_size" value="{{ old('herd_flock_size') }}" 
                                                       min="1" placeholder="e.g., 50">
                                                @error('herd_flock_size')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="breeding_practice" class="form-label">Breeding Practice <span class="text-danger">*</span></label>
                                                <select class="form-select @error('breeding_practice') is-invalid @enderror" 
                                                        id="breeding_practice" name="breeding_practice">
                                                    <option value="">Select Practice</option>
                                                    <option value="open_grazing" {{ old('breeding_practice') == 'open_grazing' ? 'selected' : '' }}>Open Grazing</option>
                                                    <option value="ranching" {{ old('breeding_practice') == 'ranching' ? 'selected' : '' }}>Ranching</option>
                                                    <option value="intensive" {{ old('breeding_practice') == 'intensive' ? 'selected' : '' }}>Intensive</option>
                                                    <option value="semi_intensive" {{ old('breeding_practice') == 'semi_intensive' ? 'selected' : '' }}>Semi-Intensive</option>
                                                </select>
                                                @error('breeding_practice')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Fisheries Practice Details --}}
                                    <div id="practice-fisheries" class="practice-section" style="display: {{ old('farm_type') == 'fisheries' ? 'block' : 'none' }};">
                                        <h6 class="mb-3">Fisheries/Aquaculture Details</h6>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="fishing_type" class="form-label">Fishing Type <span class="text-danger">*</span></label>
                                                <select class="form-select @error('fishing_type') is-invalid @enderror" 
                                                        id="fishing_type" name="fishing_type">
                                                    <option value="">Select Type</option>
                                                    <option value="aquaculture_pond" {{ old('fishing_type') == 'aquaculture_pond' ? 'selected' : '' }}>Aquaculture Pond</option>
                                                    <option value="riverine" {{ old('fishing_type') == 'riverine' ? 'selected' : '' }}>Riverine</option>
                                                    <option value="reservoir" {{ old('fishing_type') == 'reservoir' ? 'selected' : '' }}>Reservoir</option>
                                                </select>
                                                @error('fishing_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="species_raised" class="form-label">Species Raised <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('species_raised') is-invalid @enderror" 
                                                       id="species_raised" name="species_raised" value="{{ old('species_raised') }}" 
                                                       placeholder="e.g., Catfish, Tilapia">
                                                @error('species_raised')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="pond_size_sqm" class="form-label">Pond Size (m²)</label>
                                                <input type="number" step="0.01" class="form-control @error('pond_size_sqm') is-invalid @enderror" 
                                                       id="pond_size_sqm" name="pond_size_sqm" value="{{ old('pond_size_sqm') }}" 
                                                       min="0" placeholder="e.g., 100">
                                                @error('pond_size_sqm')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="expected_harvest_kg" class="form-label">Expected Harvest (kg)</label>
                                                <input type="number" step="0.01" class="form-control @error('expected_harvest_kg') is-invalid @enderror" 
                                                       id="expected_harvest_kg" name="expected_harvest_kg" value="{{ old('expected_harvest_kg') }}" 
                                                       min="0" placeholder="e.g., 2000">
                                                @error('expected_harvest_kg')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Orchards Practice Details --}}
                                    <div id="practice-orchards" class="practice-section" style="display: {{ old('farm_type') == 'orchards' ? 'block' : 'none' }};">
                                        <h6 class="mb-3">Orchard/Perennial Farming Details</h6>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="tree_type" class="form-label">Tree Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('tree_type') is-invalid @enderror" 
                                                       id="tree_type" name="tree_type" value="{{ old('tree_type') }}" 
                                                       placeholder="e.g., Mango, Orange, Cashew">
                                                @error('tree_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="number_of_trees" class="form-label">Number of Trees <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control @error('number_of_trees') is-invalid @enderror" 
                                                       id="number_of_trees" name="number_of_trees" value="{{ old('number_of_trees') }}" 
                                                       min="1" placeholder="e.g., 100">
                                                @error('number_of_trees')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="maturity_stage" class="form-label">Maturity Stage <span class="text-danger">*</span></label>
                                                <select class="form-select @error('maturity_stage') is-invalid @enderror" 
                                                        id="maturity_stage" name="maturity_stage">
                                                    <option value="">Select Stage</option>
                                                    <option value="seedling" {{ old('maturity_stage') == 'seedling' ? 'selected' : '' }}>Seedling</option>
                                                    <option value="immature" {{ old('maturity_stage') == 'immature' ? 'selected' : '' }}>Immature</option>
                                                    <option value="producing" {{ old('maturity_stage') == 'producing' ? 'selected' : '' }}>Producing</option>
                                                </select>
                                                @error('maturity_stage')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Media Upload & Verification</h5>
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    Upload clear, recent photos. Maximum file size: 2MB per image.
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="farmer_photo" class="form-label">Farmer's Passport Photo <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('farmer_photo') is-invalid @enderror" 
                                               id="farmer_photo" name="farmer_photo" accept="image/jpeg,image/jpg,image/png" required>
                                        @error('farmer_photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Clear headshot of the farmer</small>
                                        <div id="farmer_photo_preview" class="mt-2"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="farm_photo" class="form-label">Farm Photo <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control @error('farm_photo') is-invalid @enderror" 
                                               id="farm_photo" name="farm_photo" accept="image/jpeg,image/jpg,image/png" required>
                                        @error('farm_photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Photo showing the farm/land</small>
                                        <div id="farm_photo_preview" class="mt-2"></div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" id="back-to-step2">
                                        <i class="ri-arrow-left-line"></i> Previous
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submit-btn">
                                        <i class="ri-check-line"></i> Submit Enrollment
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const step1Tab = document.getElementById('step1-tab');
    const step2Tab = document.getElementById('step2-tab');
    const step3Tab = document.getElementById('step3-tab');
    const progressBar = document.getElementById('progress-bar');

    const nextToStep2 = document.getElementById('next-to-step2');
    const nextToStep3 = document.getElementById('next-to-step3');
    const backToStep1 = document.getElementById('back-to-step1');
    const backToStep2 = document.getElementById('back-to-step2');

    const farmTypeSelect = document.getElementById('farm_type');
    const primaryOccupationSelect = document.getElementById('primary_occupation');
    const otherOccupationWrapper = document.getElementById('other_occupation_wrapper');
    const enrollmentForm = document.getElementById('enrollment-form');
    const submitBtn = document.getElementById('submit-btn');

    function updateProgress(step) {
        const width = (step / 3) * 100;
        progressBar.style.width = width + '%';
    }

    nextToStep2.addEventListener('click', function() {
        if (validateStep1()) {
            step2Tab.disabled = false;
            const tab = new bootstrap.Tab(step2Tab);
            tab.show();
            updateProgress(2);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    function updatePracticeDetails() {
        const farmType = farmTypeSelect.value;

        // Hide default message
        const defaultMsg = document.getElementById('practice-default');
        if (defaultMsg) {
            defaultMsg.style.display = farmType ? 'none' : 'block';
        }

        // Hide all practice sections and disable their required fields
        document.querySelectorAll('.practice-section').forEach(function(section) {
            section.style.display = 'none';
            section.querySelectorAll('input, select, textarea').forEach(function(input) {
                input.removeAttribute('required');
                input.disabled = true; // Disable hidden fields
            });
        });

        // If no farm type selected, stop here
        if (!farmType) return;

        // Show the relevant practice section
        const practiceSection = document.getElementById('practice-' + farmType);
        if (practiceSection) {
            practiceSection.style.display = 'block';
            
            // Enable and set required for visible fields
            practiceSection.querySelectorAll('input, select, textarea').forEach(function(input) {
                input.disabled = false; // Enable field
                const label = document.querySelector('label[for="' + input.id + '"]');
                if (label && label.innerHTML.includes('text-danger')) {
                    input.setAttribute('required', 'required');
                }
            });
        }
    }

    nextToStep3.addEventListener('click', function() {
        if (validateStep2()) {
            step3Tab.disabled = false;
            const tab = new bootstrap.Tab(step3Tab);
            tab.show();
            updateProgress(3);
            updatePracticeDetails();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    backToStep1.addEventListener('click', function() {
        const tab = new bootstrap.Tab(step1Tab);
        tab.show();
        updateProgress(1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    backToStep2.addEventListener('click', function() {
        const tab = new bootstrap.Tab(step2Tab);
        tab.show();
        updateProgress(2);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    function validateStep1() {
        const requiredFields = [
            'nin', 'full_name', 'email', 'phone_primary', 'date_of_birth',
            'gender', 'marital_status', 'lga_id', 'ward', 'residential_address',
            'educational_level', 'household_size', 'primary_occupation'
        ];

        let isValid = true;
        let firstInvalidField = null;

        requiredFields.forEach(function(fieldName) {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            } else if (field) {
                field.classList.remove('is-invalid');
            }
        });

        if (primaryOccupationSelect.value === 'other') {
            const otherOccupationField = document.getElementById('other_occupation');
            if (!otherOccupationField.value.trim()) {
                otherOccupationField.classList.add('is-invalid');
                isValid = false;
                if (!firstInvalidField) firstInvalidField = otherOccupationField;
            }
        }

        if (!isValid && firstInvalidField) {
            firstInvalidField.focus();
            alert('Please fill in all required fields in Step 1');
        }

        return isValid;
    }

    function validateStep2() {
        const requiredFields = [
            'name', 'farm_type', 'total_size_hectares', 
            'ownership_status', 'geolocation_geojson'
        ];

        let isValid = true;
        let firstInvalidField = null;

        requiredFields.forEach(function(fieldName) {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
            } else if (field) {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid && firstInvalidField) {
            firstInvalidField.focus();
            alert('Please fill in all required fields in Step 2');
        }

        return isValid;
    }

    function validateStep3() {
        const farmType = farmTypeSelect.value;
        let isValid = true;
        let firstInvalidField = null;

        // Validate file uploads
        const farmerPhoto = document.getElementById('farmer_photo');
        const farmPhoto = document.getElementById('farm_photo');

        if (!farmerPhoto.files || farmerPhoto.files.length === 0) {
            farmerPhoto.classList.add('is-invalid');
            isValid = false;
            if (!firstInvalidField) firstInvalidField = farmerPhoto;
        } else {
            farmerPhoto.classList.remove('is-invalid');
        }

        if (!farmPhoto.files || farmPhoto.files.length === 0) {
            farmPhoto.classList.add('is-invalid');
            isValid = false;
            if (!firstInvalidField) firstInvalidField = farmPhoto;
        } else {
            farmPhoto.classList.remove('is-invalid');
        }

        // Validate practice details based on farm type
        if (farmType) {
            const practiceSection = document.getElementById('practice-' + farmType);
            if (practiceSection) {
                practiceSection.querySelectorAll('input[required], select[required]').forEach(function(field) {
                    if (!field.disabled && !field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = field;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
            }
        }

        if (!isValid && firstInvalidField) {
            firstInvalidField.focus();
            alert('Please fill in all required fields and upload photos in Step 3');
        }

        return isValid;
    }

    primaryOccupationSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            otherOccupationWrapper.style.display = 'block';
            document.getElementById('other_occupation').required = true;
        } else {
            otherOccupationWrapper.style.display = 'none';
            document.getElementById('other_occupation').required = false;
        }
    });

    farmTypeSelect.addEventListener('change', updatePracticeDetails);

    // Initialize on page load if farm_type has old value
    if (farmTypeSelect.value) {
        updatePracticeDetails();
    }

    // Form submission validation
    if (enrollmentForm) {
        enrollmentForm.addEventListener('submit', function(e) {
            // Validate all steps before submission
            if (!validateStep1() || !validateStep2() || !validateStep3()) {
                e.preventDefault();
                alert('Please complete all required fields in all steps before submitting.');
                return false;
            }
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Submitting...';
        });
    }

    // Image preview handlers
    document.getElementById('farmer_photo').addEventListener('change', function(e) {
        previewImage(e.target, 'farmer_photo_preview');
    });

    document.getElementById('farm_photo').addEventListener('change', function(e) {
        previewImage(e.target, 'farm_photo_preview');
    });

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail mt-2" style="max-width: 200px;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});
</script>
@endpush
