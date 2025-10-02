@extends('layouts.enrollment_agent')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Enroll New Farmer</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Farmer Enrollment Survey</h4>
                    <p class="card-title-desc">Complete all sections accurately to enroll a new farmer into the system. Required fields are marked with an asterisk (*).</p>

                    <form action="{{ route('enrollment.farmers.store') }}" method="POST">
                        @csrf
                        
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#step1" role="tab">
                                    <span class="d-none d-sm-block">1. Personal & Identity</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#step2" role="tab">
                                    <span class="d-none d-sm-block">2. Farm & Land Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#step3" role="tab">
                                    <span class="d-none d-sm-block">3. Practice Details & Media</span>
                                </a>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-3 text-muted">
                            
                            <div class="tab-pane active" id="step1" role="tabpanel">
                                <h5>Farmer Demographics and Contact</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="nin" class="form-label">NIN (National ID Number) *</label>
                                        <input type="text" class="form-control" id="nin" name="nin" value="{{ old('nin') }}" required>
                                        @error('nin')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="full_name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                        @error('full_name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="phone_primary" class="form-label">Primary Phone Number *</label>
                                        <input type="text" class="form-control" id="phone_primary" name="phone_primary" value="{{ old('phone_primary') }}" required>
                                        @error('phone_primary')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="lga_id" class="form-label">Local Government Area (LGA) *</label>
                                        <select class="form-select" id="lga_id" name="lga_id" required>
                                            <option value="">Select LGA</option>
                                            @foreach($lgas as $lga)
                                                <option value="{{ $lga->id }}" {{ old('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('lga_id')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="cooperative_id" class="form-label">Cooperative (Optional)</label>
                                        <select class="form-select" id="cooperative_id" name="cooperative_id">
                                            <option value="">None</option>
                                            @foreach($cooperatives as $coop)
                                                <option value="{{ $coop->id }}" {{ old('cooperative_id') == $coop->id ? 'selected' : '' }}>{{ $coop->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    {{-- ... Add other personal fields (DOB, Gender, etc.) here ... --}}
                                </div>

                            </div>

                            <div class="tab-pane" id="step2" role="tabpanel">
                                <h5>Farm Plot Registration</h5>
                                <p>Register the primary farm plot and capture its location data.</p>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="farm_name" class="form-label">Farm Name/Reference</label>
                                        <input type="text" class="form-control" id="farm_name" name="farm_name" value="{{ old('farm_name') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="farm_type" class="form-label">Farm Type *</label>
                                        <select class="form-select" id="farm_type" name="farm_type" required>
                                            <option value="">Select Type</option>
                                            <option value="crops" {{ old('farm_type') == 'crops' ? 'selected' : '' }}>Crops</option>
                                            <option value="livestock" {{ old('farm_type') == 'livestock' ? 'selected' : '' }}>Livestock</option>
                                            <option value="fisheries" {{ old('farm_type') == 'fisheries' ? 'selected' : '' }}>Fisheries</option>
                                            <option value="orchards" {{ old('farm_type') == 'orchards' ? 'selected' : '' }}>Orchards</option>
                                        </select>
                                        @error('farm_type')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="total_size_hectares" class="form-label">Total Size (Hectares) *</label>
                                        <input type="number" step="0.01" class="form-control" id="total_size_hectares" name="total_size_hectares" value="{{ old('total_size_hectares') }}" required>
                                        @error('total_size_hectares')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="geolocation_geojson" class="form-label">Geospatial Data (GeoJSON Polygon) *</label>
                                        {{-- In a real app, this would be a map widget (Leaflet/Google Maps) --}}
                                        <textarea class="form-control" id="geolocation_geojson" name="geolocation_geojson" rows="4" placeholder="Paste GeoJSON here or use map tool...">{{ old('geolocation_geojson') }}</textarea>
                                        @error('geolocation_geojson')<div class="text-danger">{{ $message }}</div>@enderror
                                        <small class="text-muted">Capture the boundaries of the farm plot.</small>
                                    </div>
                                </div>

                                {{-- ... Add other land fields (e.g., tenure, soil type) here ... --}}
                            </div>
                            
                            <div class="tab-pane" id="step3" role="tabpanel">
                                <h5>Specific Farming Practices</h5>
                                <p>Provide details based on the selected **Farm Type**.</p>
                                
                                <div id="practice-details-container">
                                    <div class="alert alert-warning">Please select a **Farm Type** in Step 2 to display the relevant practice details form.</div>
                                    
                                    {{-- The specific practice fields (CropPracticeDetails, etc.) will be dynamically rendered here. --}}
                                </div>

                                <h5 class="mt-4">Media Upload (Optional)</h5>
                                <div class="mb-3">
                                    <label for="photo_id" class="form-label">Farmer Photo / Farm Proof of Existence</label>
                                    <input type="file" class="form-control" id="photo_id" name="photo_id">
                                    <small class="text-muted">Photo of the farmer and/or a geo-tagged photo of the farm.</small>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-success waves-effect waves-light">Submit Enrollment for Review</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection