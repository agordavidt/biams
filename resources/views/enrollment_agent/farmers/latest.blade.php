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
                    <h4 class="card-title mb-4">Farmer Enrollment Survey Wizard</h4>
                    <p class="card-title-desc">Complete all sections accurately. Required fields are marked with an asterisk (*).</p>

                    {{-- NOTE: Added enctype="multipart/form-data" for file uploads --}}
                    <form id="enrollment-form" action="{{ route('enrollment.farmers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Tab Navigation for Steps --}}
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist" id="enrollment-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#step1" role="tab" data-step="1">
                                    <span class="d-sm-block">1. Personal & Identity</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#step2" role="tab" data-step="2">
                                    <span class="d-sm-block">2. Farm & Land Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#step3" role="tab" data-step="3">
                                    <span class="d-sm-block">3. Practice, Media & Submit</span>
                                </a>
                            </li>
                        </ul>
                        
                        {{-- Tab Content (The actual form steps) --}}
                        <div class="tab-content p-3 text-muted">
                            
                            {{-- ========================================================================================================= --}}
                            {{-- STEP 1: Personal & Identity --}}
                            {{-- ========================================================================================================= --}}
                            <div class="tab-pane active" id="step1" role="tabpanel">
                                <h5>Farmer Demographics and Contact</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="nin" class="form-label">NIN (National ID Number) *</label>
                                        <input type="text" class="form-control" id="nin" name="nin" value="{{ old('nin') }}" required placeholder="e.g., 12345678901">
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
                                        <input type="text" class="form-control" id="phone_primary" name="phone_primary" value="{{ old('phone_primary') }}" required placeholder="e.g., 080XXXXXXXX">
                                        @error('phone_primary')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="dob" class="form-label">Date of Birth *</label>
                                        <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                                        @error('dob')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="gender" class="form-label">Gender *</label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                
                                <h5 class="mt-4">Location and Grouping</h5>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="lga_id" class="form-label">Local Government Area (LGA) *</label>
                                        <select class="form-select" id="lga_id" name="lga_id" required>
                                            <option value="">Select LGA</option>
                                            {{-- Assume $lgas is passed from the controller --}}
                                            @foreach($lgas ?? [] as $lga) 
                                                <option value="{{ $lga->id }}" {{ old('lga_id') == $lga->id ? 'selected' : '' }}>{{ $lga->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('lga_id')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="cooperative_id" class="form-label">Cooperative (Optional)</label>
                                        <select class="form-select" id="cooperative_id" name="cooperative_id">
                                            <option value="">None (Individual Farmer)</option>
                                            {{-- Assume $cooperatives is passed from the controller, filtered by LGA in a real app --}}
                                            @foreach($cooperatives ?? [] as $coop)
                                                <option value="{{ $coop->id }}" {{ old('cooperative_id') == $coop->id ? 'selected' : '' }}>{{ $coop->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('cooperative_id')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="address_residential" class="form-label">Residential Address *</label>
                                        <input type="text" class="form-control" id="address_residential" name="address_residential" value="{{ old('address_residential') }}" required>
                                        @error('address_residential')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-end">
                                    <button type="button" class="btn btn-primary next-step">Next: Land Details <i class="ri-arrow-right-line align-middle"></i></button>
                                </div>
                            </div>
                            
                            {{-- ========================================================================================================= --}}
                            {{-- STEP 2: Farm & Land Details --}}
                            {{-- ========================================================================================================= --}}
                            <div class="tab-pane" id="step2" role="tabpanel">
                                <h5>Farm Plot Registration</h5>
                                <p>Register the primary farm plot location and key physical characteristics.</p>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="farm_name" class="form-label">Farm Name/Reference (Optional)</label>
                                        <input type="text" class="form-control" id="farm_name" name="farm_name" value="{{ old('farm_name') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="farm_type" class="form-label">Primary Agricultural Sector *</label>
                                        <select class="form-select" id="farm_type" name="farm_type" required onchange="updatePracticeDetails()">
                                            <option value="">Select Type</option>
                                            <option value="crops" {{ old('farm_type') == 'crops' ? 'selected' : '' }}>Crops/Arable</option>
                                            <option value="livestock" {{ old('farm_type') == 'livestock' ? 'selected' : '' }}>Livestock</option>
                                            <option value="fisheries" {{ old('farm_type') == 'fisheries' ? 'selected' : '' }}>Fisheries/Aquaculture</option>
                                            <option value="orchards" {{ old('farm_type') == 'orchards' ? 'selected' : '' }}>Orchards/Perennial</option>
                                        </select>
                                        @error('farm_type')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="total_size_hectares" class="form-label">Total Size (Hectares) *</label>
                                        <input type="number" step="0.01" class="form-control" id="total_size_hectares" name="total_size_hectares" value="{{ old('total_size_hectares') }}" required placeholder="e.g., 2.5">
                                        @error('total_size_hectares')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                
                                <h5 class="mt-4">Land and Water Details</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="land_tenure" class="form-label">Land Tenure Type *</label>
                                        <select class="form-select" id="land_tenure" name="land_tenure" required>
                                            <option value="">Select Tenure</option>
                                            <option value="Owned" {{ old('land_tenure') == 'Owned' ? 'selected' : '' }}>Owned</option>
                                            <option value="Leased" {{ old('land_tenure') == 'Leased' ? 'selected' : '' }}>Leased</option>
                                            <option value="Communal" {{ old('land_tenure') == 'Communal' ? 'selected' : '' }}>Communal Land</option>
                                            <option value="Inherited" {{ old('land_tenure') == 'Inherited' ? 'selected' : '' }}>Inherited/Customary</option>
                                        </select>
                                        @error('land_tenure')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="soil_type" class="form-label">Dominant Soil Type (Optional)</label>
                                        <input type="text" class="form-control" id="soil_type" name="soil_type" value="{{ old('soil_type') }}" placeholder="e.g., Loamy, Clay, Sandy">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="water_source" class="form-label">Primary Water Source (Optional)</label>
                                        <input type="text" class="form-control" id="water_source" name="water_source" value="{{ old('water_source') }}" placeholder="e.g., River, Borehole, Rainfed">
                                    </div>
                                </div>

                                <h5 class="mt-4">Geospatial Data</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="geolocation_geojson" class="form-label">Geospatial Data (GeoJSON Polygon) *</label>
                                        {{-- This simulates a map widget capture output --}}
                                        <textarea class="form-control" id="geolocation_geojson" name="geolocation_geojson" rows="4" required placeholder='{"type": "Polygon", "coordinates": [...] }'>{{ old('geolocation_geojson') }}</textarea>
                                        @error('geolocation_geojson')<div class="text-danger">{{ $message }}</div>@enderror
                                        <small class="text-muted">Required: Capture the boundaries of the farm plot using a GPS tool.</small>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary prev-step"><i class="ri-arrow-left-line align-middle"></i> Previous: Identity</button>
                                    <button type="button" class="btn btn-primary next-step">Next: Practice & Media <i class="ri-arrow-right-line align-middle"></i></button>
                                </div>
                            </div>
                            
                            {{-- ========================================================================================================= --}}
                            {{-- STEP 3: Practice Details & Media --}}
                            {{-- ========================================================================================================= --}}
                            <div class="tab-pane" id="step3" role="tabpanel">
                                <h5>Specific Farming Practices</h5>
                                <div id="practice-details-container">
                                    {{-- Dynamic content will be loaded here based on farm_type selected in Step 2 --}}
                                    <div id="crops-details" class="practice-form" style="display:none;">
                                        <h6>Crop Farming Specifics</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="crop_primary_crop" class="form-label">Primary Crop Grown *</label>
                                                <input type="text" class="form-control" id="crop_primary_crop" name="crop_primary_crop" value="{{ old('crop_primary_crop') }}" placeholder="e.g., Maize, Cassava">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="crop_estimated_yield" class="form-label">Last Season Estimated Yield (Bags/Tonnes)</label>
                                                <input type="number" step="0.01" class="form-control" id="crop_estimated_yield" name="crop_estimated_yield" value="{{ old('crop_estimated_yield') }}" placeholder="e.g., 5.0">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="crop_uses_irrigation" id="crop_uses_irrigation" {{ old('crop_uses_irrigation') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="crop_uses_irrigation">
                                                        Does the farmer use irrigation?
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Livestock Details Placeholder --}}
                                    <div id="livestock-details" class="practice-form" style="display:none;">
                                        <h6>Livestock Farming Specifics</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="livestock_type" class="form-label">Type of Livestock *</label>
                                                <input type="text" class="form-control" id="livestock_type" name="livestock_type" value="{{ old('livestock_type') }}" placeholder="e.g., Cattle, Poultry, Goats">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="livestock_head_count" class="form-label">Total Head/Number of Animals *</label>
                                                <input type="number" class="form-control" id="livestock_head_count" name="livestock_head_count" value="{{ old('livestock_head_count') }}" placeholder="e.g., 50">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Default alert --}}
                                    <div id="default-alert" class="alert alert-info">Please select a **Primary Agricultural Sector** in Step 2 to display the relevant practice details form.</div>
                                </div>

                                <h5 class="mt-4">Media and Verification Documents</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="farmer_photo" class="form-label">Farmer's Passport Photo *</label>
                                        <input type="file" class="form-control" id="farmer_photo" name="farmer_photo" accept="image/*" required>
                                        <small class="text-muted">A clear, recent photo of the farmer.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="farm_proof_document" class="form-label">Farm Proof of Existence (Geo-tagged Photo) *</label>
                                        <input type="file" class="form-control" id="farm_proof_document" name="farm_proof_document" accept="image/*" required>
                                        <small class="text-muted">A photo of the farmer standing on their land (preferably geo-tagged).</small>
                                    </div>
                                </div>
                                
                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary prev-step"><i class="ri-arrow-left-line align-middle"></i> Previous: Land Details</button>
                                    <button type="submit" class="btn btn-success waves-effect waves-light">Submit Enrollment for Review</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
{{-- Required JavaScript for Tab Navigation and Dynamic Fields --}}
<script>
    // Handles tab switching using Next/Previous buttons
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            const currentTab = document.querySelector('.nav-tabs .active');
            const nextTab = currentTab.parentElement.nextElementSibling.querySelector('.nav-link');
            if (nextTab) {
                // Activate the next tab
                new bootstrap.Tab(nextTab).show();
                // Scroll to the top of the form
                document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            const currentTab = document.querySelector('.nav-tabs .active');
            const prevTab = currentTab.parentElement.previousElementSibling.querySelector('.nav-link');
            if (prevTab) {
                // Activate the previous tab
                new bootstrap.Tab(prevTab).show();
                // Scroll to the top of the form
                document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Function to dynamically update Step 3 content based on Farm Type
    function updatePracticeDetails() {
        const farmType = document.getElementById('farm_type').value;
        
        // Hide all practice details and the default alert first
        document.querySelectorAll('.practice-form').forEach(form => {
            form.style.display = 'none';
        });
        document.getElementById('default-alert').style.display = 'none';

        // Show the relevant form or the default alert
        if (farmType === 'crops' || farmType === 'orchards') {
            document.getElementById('crops-details').style.display = 'block';
        } else if (farmType === 'livestock') {
            document.getElementById('livestock-details').style.display = 'block';
        } else if (farmType === 'fisheries') {
            // For fisheries, we could create another dedicated block, but for this example, let's show an alert
            document.getElementById('default-alert').innerHTML = 'Please fill out relevant details in the notes section for **Fisheries**.';
            document.getElementById('default-alert').style.display = 'block';
        } else {
             document.getElementById('default-alert').innerHTML = 'Please select a **Primary Agricultural Sector** in Step 2 to display the relevant practice details form.';
            document.getElementById('default-alert').style.display = 'block';
        }
    }

    // Initial call on page load to set the correct display based on old input
    document.addEventListener('DOMContentLoaded', updatePracticeDetails);

</script>
@endsection