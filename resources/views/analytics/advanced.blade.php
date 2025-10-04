@extends('layouts.super_admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Advanced Analytics Filter</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('analytics.dashboard') }}">Analytics</a></li>
                        <li class="breadcrumb-item active">Advanced Filter</li>
                    </ol>
                </div>
            </div>
            <p class="text-muted mb-0">Generate custom reports with dynamic filters and detailed insights</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary bg-opacity-10 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-filter-3-line text-primary me-2"></i>
                        <h5 class="card-title mb-0 text-primary">Filter Options</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('analytics.advanced.generate') }}" method="GET" id="advancedFilterForm">
                        
                        {{-- Demographics Section --}}
                        <div class="filter-section mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-primary bg-opacity-10 rounded text-primary">
                                            <i class="ri-user-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Demographics</h5>
                                    <p class="text-muted mb-0">Filter by farmer personal characteristics</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select name="gender" id="gender" class="form-select">
                                            <option value="">All Genders</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Age Range</label>
                                        <div class="input-group">
                                            <input type="number" name="age_min" id="age_min" class="form-control" placeholder="Min" min="18" max="100">
                                            <span class="input-group-text bg-light border">to</span>
                                            <input type="number" name="age_max" class="form-control" placeholder="Max" min="18" max="100">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="educational_level" class="form-label">Education Level</label>
                                        <select name="educational_level[]" id="educational_level" class="form-select" multiple>
                                            <option value="none">None</option>
                                            <option value="primary">Primary</option>
                                            <option value="secondary">Secondary</option>
                                            <option value="tertiary">Tertiary</option>
                                            <option value="vocational">Vocational</option>
                                        </select>
                                        <small class="form-text text-muted">Hold Ctrl/Cmd for multiple selection</small>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="marital_status" class="form-label">Marital Status</label>
                                        <select name="marital_status" id="marital_status" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="single">Single</option>
                                            <option value="married">Married</option>
                                            <option value="divorced">Divorced</option>
                                            <option value="widowed">Widowed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-4 col-md-6">
                                    <div class="mb-3">
                                        <label for="primary_occupation" class="form-label">Primary Occupation</label>
                                        <select name="primary_occupation" id="primary_occupation" class="form-select">
                                            <option value="">All Occupations</option>
                                            <option value="full_time_farmer">Full-time Farmer</option>
                                            <option value="part_time_farmer">Part-time Farmer</option>
                                            <option value="civil_servant">Civil Servant</option>
                                            <option value="trader">Trader</option>
                                            <option value="artisan">Artisan</option>
                                            <option value="student">Student</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6">
                                    <div class="mb-3">
                                        <label for="cooperative_member" class="form-label">Cooperative Membership</label>
                                        <select name="cooperative_member" id="cooperative_member" class="form-select">
                                            <option value="">All Farmers</option>
                                            <option value="1">Members Only</option>
                                            <option value="0">Non-members Only</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Farmer Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="active">Active</option>
                                            <option value="pending_activation">Pending Activation</option>
                                            <option value="pending_lga_review">Pending Review</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Geographic Section --}}
                        <div class="filter-section mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-success bg-opacity-10 rounded text-success">
                                            <i class="ri-map-pin-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Geographic Location</h5>
                                    <p class="text-muted mb-0">Filter by Local Government Areas</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="lga_id" class="form-label">LGA (Local Government Area)</label>
                                        <select name="lga_id[]" id="lga_id" class="form-select" multiple>
                                            @foreach($filterOptions['lgas'] as $lga)
                                                <option value="{{ $lga->id }}">{{ $lga->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Hold Ctrl/Cmd for multiple selection</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Farm Type Section --}}
                        <div class="filter-section mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-warning bg-opacity-10 rounded text-warning">
                                            <i class="ri-tractor-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Farm Characteristics</h5>
                                    <p class="text-muted mb-0">Filter by farm type and ownership</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-4 col-md-6">
                                    <div class="mb-3">
                                        <label for="farm_type" class="form-label">Farm Type</label>
                                        <select name="farm_type" id="farm_type" class="form-select">
                                            <option value="">All Types</option>
                                            <option value="crops">Crops</option>
                                            <option value="livestock">Livestock</option>
                                            <option value="fisheries">Fisheries</option>
                                            <option value="orchards">Orchards</option>
                                            <option value="forestry">Forestry</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-6">
                                    <div class="mb-3">
                                        <label for="ownership_status" class="form-label">Ownership Status</label>
                                        <select name="ownership_status" id="ownership_status" class="form-select">
                                            <option value="">All Types</option>
                                            <option value="owned">Owned</option>
                                            <option value="leased">Leased</option>
                                            <option value="shared">Shared</option>
                                            <option value="communal">Communal</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Crop-Specific Filters --}}
                        <div class="filter-section mb-4" id="cropFilters" style="display: none;">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-success bg-opacity-10 rounded text-success">
                                            <i class="ri-seedling-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Crop Production</h5>
                                    <p class="text-muted mb-0">Additional filters for crop farming</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="crop_type" class="form-label">Crop Type</label>
                                        <select name="crop_type" id="crop_type" class="form-select">
                                            <option value="">Select Crop</option>
                                            @foreach($filterOptions['crop_types'] as $crop)
                                                <option value="{{ $crop }}">{{ ucwords(str_replace('_', ' ', $crop)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="farming_method" class="form-label">Farming Method</label>
                                        <select name="farming_method" id="farming_method" class="form-select">
                                            <option value="">All Methods</option>
                                            <option value="irrigation">Irrigation</option>
                                            <option value="rain_fed">Rain-fed</option>
                                            <option value="organic">Organic</option>
                                            <option value="mixed">Mixed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Livestock-Specific Filters --}}
                        <div class="filter-section mb-4" id="livestockFilters" style="display: none;">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-info bg-opacity-10 rounded text-info">
                                            <i class="ri-bear-smile-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Livestock Production</h5>
                                    <p class="text-muted mb-0">Additional filters for livestock farming</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="animal_type" class="form-label">Animal Type</label>
                                        <select name="animal_type" id="animal_type" class="form-select">
                                            <option value="">Select Animal</option>
                                            @foreach($filterOptions['animal_types'] as $animal)
                                                <option value="{{ $animal }}">{{ ucwords(str_replace('_', ' ', $animal)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="breeding_practice" class="form-label">Breeding Practice</label>
                                        <select name="breeding_practice" id="breeding_practice" class="form-select">
                                            <option value="">All Practices</option>
                                            <option value="open_grazing">Open Grazing</option>
                                            <option value="ranching">Ranching</option>
                                            <option value="intensive">Intensive</option>
                                            <option value="semi_intensive">Semi-intensive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Fisheries-Specific Filters --}}
                        <div class="filter-section mb-4" id="fisheriesFilters" style="display: none;">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-primary bg-opacity-10 rounded text-primary">
                                            <i class="ri-fish-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Fisheries Production</h5>
                                    <p class="text-muted mb-0">Additional filters for fish farming</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="fishing_type" class="form-label">Fishing Type</label>
                                        <select name="fishing_type" id="fishing_type" class="form-select">
                                            <option value="">All Types</option>
                                            <option value="aquaculture_pond">Aquaculture Pond</option>
                                            <option value="riverine">Riverine</option>
                                            <option value="reservoir">Reservoir</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="species_raised" class="form-label">Species Raised</label>
                                        <select name="species_raised" id="species_raised" class="form-select">
                                            <option value="">All Species</option>
                                            @foreach($filterOptions['species'] as $species)
                                                <option value="{{ $species }}">{{ ucwords($species) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="hstack gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-bar-chart-line align-middle me-1"></i> Generate Report
                                    </button>
                                    <button type="reset" class="btn btn-light">
                                        <i class="ri-refresh-line align-middle me-1"></i> Reset Filters
                                    </button>
                                    @can('export_analytics')
                                    <button type="button" class="btn btn-success" onclick="exportResults()">
                                        <i class="ri-download-line align-middle me-1"></i> Export Results
                                    </button>
                                    @endcan
                                    <a href="{{ route('analytics.advanced.predefined') }}" class="btn btn-outline-primary ms-auto">
                                        <i class="ri-list-check align-middle me-1"></i> Predefined Reports
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Examples Section --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light bg-opacity-10 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-lightbulb-line text-warning me-2"></i>
                        <h5 class="card-title mb-0">Example Queries</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-xl-4 col-md-6">
                            <div class="card border border-primary border-opacity-25 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-primary bg-opacity-10 rounded text-primary">
                                                    <i class="ri-women-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="card-title text-primary mb-2">Women in Fisheries</h6>
                                            <p class="card-text text-muted small mb-3">Gender: Female + Farm Type: Fisheries</p>
                                            <button class="btn btn-sm btn-outline-primary" onclick="applyExample('female', 'fisheries', null)">
                                                Apply This Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="card border border-success border-opacity-25 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-success bg-opacity-10 rounded text-success">
                                                    <i class="ri-user-star-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="card-title text-success mb-2">Youth Cassava Farmers</h6>
                                            <p class="card-text text-muted small mb-3">Age: 18-35 + Crop: Cassava</p>
                                            <button class="btn btn-sm btn-outline-success" onclick="applyExample(null, 'crops', 'cassava', 18, 35)">
                                                Apply This Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="card border border-warning border-opacity-25 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-warning bg-opacity-10 rounded text-warning">
                                                    <i class="ri-graduation-cap-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="card-title text-warning mb-2">Educated Livestock Farmers</h6>
                                            <p class="card-text text-muted small mb-3">Education: Tertiary/Vocational + Farm Type: Livestock</p>
                                            <button class="btn btn-sm btn-outline-warning" onclick="applyExample(null, 'livestock', null)">
                                                Apply This Filter
                                            </button>
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
</div>
@endsection

@push('scripts')
<script>
    // Show/hide specific filters based on farm type selection
    document.getElementById('farm_type').addEventListener('change', function() {
        const cropFilters = document.getElementById('cropFilters');
        const livestockFilters = document.getElementById('livestockFilters');
        const fisheriesFilters = document.getElementById('fisheriesFilters');
        
        // Hide all specific filters
        cropFilters.style.display = 'none';
        livestockFilters.style.display = 'none';
        fisheriesFilters.style.display = 'none';
        
        // Show relevant filter section with animation
        if (this.value === 'crops') {
            cropFilters.style.display = 'block';
            cropFilters.style.animation = 'fadeIn 0.3s ease-in';
        } else if (this.value === 'livestock') {
            livestockFilters.style.display = 'block';
            livestockFilters.style.animation = 'fadeIn 0.3s ease-in';
        } else if (this.value === 'fisheries') {
            fisheriesFilters.style.display = 'block';
            fisheriesFilters.style.animation = 'fadeIn 0.3s ease-in';
        }
    });

    // Apply example filters
    function applyExample(gender, farmType, cropType, ageMin, ageMax) {
        if (gender) {
            document.getElementById('gender').value = gender;
        }
        if (farmType) {
            document.getElementById('farm_type').value = farmType;
            document.getElementById('farm_type').dispatchEvent(new Event('change'));
        }
        if (cropType) {
            document.getElementById('crop_type').value = cropType;
        }
        if (ageMin) {
            document.getElementById('age_min').value = ageMin;
        }
        if (ageMax) {
            document.querySelector('input[name="age_max"]').value = ageMax;
        }
        
        // Show success message
        showToast('Example filters applied! Adjust as needed and click Generate Report.', 'success');
    }

    // Export results function
    function exportResults() {
        const form = document.getElementById('advancedFilterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        window.location.href = '{{ route("analytics.advanced.export") }}?' + params.toString();
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        // You can integrate with a toast library or use browser alert for now
        alert(message);
    }

    // Add some basic animations
    document.addEventListener('DOMContentLoaded', function() {
        const filterSections = document.querySelectorAll('.filter-section');
        filterSections.forEach((section, index) => {
            section.style.animation = `slideInUp 0.5s ease ${index * 0.1}s both`;
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .filter-section {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
        border-left: 4px solid #4e73df;
    }
    
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border: 1px solid #e3e6f0;
    }
    
    .form-select[multiple] {
        height: auto;
        min-height: 120px;
    }
</style>
@endpush