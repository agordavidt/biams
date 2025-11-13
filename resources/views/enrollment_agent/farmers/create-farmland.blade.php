@extends('layouts.enrollment_agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Add New Farmland</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('enrollment.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enrollment.farmers.index') }}">Farmers</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enrollment.farmers.show', $farmer) }}">{{ $farmer->full_name }}</a></li>
                        <li class="breadcrumb-item active">Add Farmland</li>
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

    {{-- Farmer Info Card --}}
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-1">Adding farmland for:</h6>
                            <h5 class="mb-0">{{ $farmer->full_name }}</h5>
                            <p class="text-muted mb-0">
                                <small>NIN: {{ $farmer->nin }} | Phone: {{ $farmer->phone_primary }}</small>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="badge bg-success-subtle text-success px-3 py-2">{{ ucwords(str_replace('_', ' ', $farmer->status)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">New Farmland Details</h5>
                    <p class="text-muted">Add another farm plot to this farmer's profile. Fields marked with <span class="text-danger">*</span> are required.</p>

                    <form id="farmland-form" action="{{ route('enrollment.farmers.farmlands.store', $farmer) }}" method="POST">
                        @csrf

                        {{-- Farm Land Details --}}
                        <h6 class="mb-3 mt-4">Farm Plot Information</h6>
                        
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
                                </select>
                                @error('farm_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">This determines the practice details below</small>
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

                        {{-- Geospatial Data --}}
                        <h6 class="mt-4 mb-3">Geospatial Data</h6>
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

                        {{-- Practice Details Section --}}
                        <h6 class="mt-4 mb-3">Farming Practice Details</h6>

                        <div id="practice-container">
                            {{-- Default Message --}}
                            <div id="practice-default" class="alert alert-warning" style="display: {{ old('farm_type') ? 'none' : 'block' }};">
                                <i class="ri-alert-line me-2"></i>
                                Please select a <strong>Farm Type</strong> above to display the relevant practice details form.
                            </div>

                            {{-- Crops Practice Details --}}
                            <div id="practice-crops" class="practice-section" style="display: {{ old('farm_type') == 'crops' ? 'block' : 'none' }};">
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

                        {{-- Form Actions --}}
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('enrollment.farmers.show', $farmer) }}" class="btn btn-secondary">
                                 Cancel
                            </a>
                            <button type="submit" class="btn btn-success" id="submit-btn">
                                Add Farmland
                            </button>
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
    const farmTypeSelect = document.getElementById('farm_type');
    const farmlandForm = document.getElementById('farmland-form');
    const submitBtn = document.getElementById('submit-btn');

    // Update practice details based on farm type selection
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
                input.disabled = true;
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
                input.disabled = false;
                const label = document.querySelector('label[for="' + input.id + '"]');
                if (label && label.innerHTML.includes('text-danger')) {
                    input.setAttribute('required', 'required');
                }
            });
        }
    }

    // Listen for farm type changes
    farmTypeSelect.addEventListener('change', updatePracticeDetails);

    // Initialize on page load if farm_type has old value
    if (farmTypeSelect.value) {
        updatePracticeDetails();
    }

    // Form validation before submission
    function validateForm() {
        let isValid = true;
        let firstInvalidField = null;

        // Validate basic farm details
        const requiredFields = ['name', 'farm_type', 'total_size_hectares', 'ownership_status', 'geolocation_geojson'];
        
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

        // Validate practice details based on farm type
        const farmType = farmTypeSelect.value;
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
            alert('Please fill in all required fields before submitting.');
        }

        return isValid;
    }

    // Handle form submission
    if (farmlandForm) {
        farmlandForm.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Adding Farmland...';
        });
    }

    // Real-time validation on input
    document.querySelectorAll('input[required], select[required], textarea[required]').forEach(function(field) {
        field.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
            }
        });
    });
});
</script>
@endpush