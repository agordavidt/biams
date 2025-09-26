@php use App\Models\Setting; @endphp
{{-- resources\views\layouts\new.blade.php --}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Complete Your Profile - {{ Setting::get('site_title', 'Benue State Integrated Agricultural Data Assets Management System') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Benue Stat Integrated Agricultural Assets Data Management System" name="description" />
    <meta content="BDIC Team" name="author" />

    <link rel="shortcut icon" href="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/favicon.ico') }}">
    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        /* Custom styles for the standalone view */
        body {
            background-color: #f7f7f7; /* Light background for a clean focus */
        }
        .profile-complete-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .profile-card {
            max-width: 850px; /* Slightly wider card for better form layout */
            width: 100%;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }
        .logo-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-header img {
            max-height: 45px;
        }
        .step-indicator-item.active .step-icon {
            background-color: #556ee6; /* Primary color */
            color: #fff;
        }
        .step-indicator-item.active .step-title {
            color: #556ee6;
            font-weight: 600;
        }
        .step-icon {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f3f3f3;
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
    </style>
</head>

<body>

<div class="profile-complete-wrapper">
    <div class="profile-card">

        <div class="logo-header">
            <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="System Logo" height="40">
            <h4 class="mt-3 mb-1">Account Setup Required</h4>
            <p class="text-muted">Please complete your farmer profile to access the dashboard features.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="profileForm" method="POST" action="{{ route('profile.complete') }}" class="mt-4">
            @csrf

            <div class="row text-center mb-5">
                <div class="col-4 step-indicator-item active" data-step="1">
                    <div class="step-icon">1</div>
                    <div class="step-title font-size-14 text-truncate">Basic Information</div>
                </div>
                <div class="col-4 step-indicator-item" data-step="2">
                    <div class="step-icon">2</div>
                    <div class="step-title font-size-14 text-truncate">Demographics & Income</div>
                </div>
                <div class="col-4 step-indicator-item" data-step="3">
                    <div class="step-icon">3</div>
                    <div class="step-title font-size-14 text-truncate">Location & Address</div>
                </div>
            </div>

            <div class="step active" data-step="1">
                <h5 class="mb-3 text-primary"><i class="ri-user-line me-2"></i> Your Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="nin">National Identity Number (NIN)</label>
                        <input type="text" class="form-control" id="nin" name="nin" placeholder="11-digit NIN" pattern="\d{11}" maxlength="11" value="{{ old('nin') }}" required>
                        <div class="form-text text-muted">Used for verification and government programs.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g. 08012345678" pattern="0\d{10}" maxlength="11" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="gender">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-primary next-step">Next <i class="ri-arrow-right-line align-middle ms-1"></i></button>
                </div>
            </div>

            <div class="step" data-step="2">
                <h5 class="mb-3 text-primary"><i class="ri-team-line me-2"></i> Household & Education</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="education">Highest Education Level</label>
                        <select class="form-select" id="education" name="education" required>
                            <option value="">Select Education Level</option>
                            <option value="no_formal" {{ old('education') === 'no_formal' ? 'selected' : '' }}>No Formal School</option>
                            <option value="primary" {{ old('education') === 'primary' ? 'selected' : '' }}>Primary School</option>
                            <option value="secondary" {{ old('education') === 'secondary' ? 'selected' : '' }}>Secondary School</option>
                            <option value="undergraduate" {{ old('education') === 'undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                            <option value="graduate" {{ old('education') === 'graduate' ? 'selected' : '' }}>Graduate</option>
                            <option value="postgraduate" {{ old('education') === 'postgraduate' ? 'selected' : '' }}>Post Graduate</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="household_size">Total Household Size</label>
                        <input type="number" class="form-control" id="household_size" name="household_size" value="{{ old('household_size') }}" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="dependents">Number of Dependents (under 18 or non-working)</label>
                        <input type="number" class="form-control" id="dependents" name="dependents" value="{{ old('dependents') }}" min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="income_level">Estimated Annual Income (₦)</label>
                        <select class="form-select" id="income_level" name="income_level" required>
                            <option value="">Select Income Range</option>
                            <option value="0-100000" {{ old('income_level') === '0-100000' ? 'selected' : '' }}>Less than ₦100,000</option>
                            <option value="100001-250000" {{ old('income_level') === '100001-250000' ? 'selected' : '' }}>₦100,001 - ₦250,000</option>
                            <option value="250001-500000" {{ old('income_level') === '250001-500000' ? 'selected' : '' }}>₦250,001 - ₦500,000</option>
                            <option value="500001-1000000" {{ old('income_level') === '500001-1000000' ? 'selected' : '' }}>₦500,001 - ₦1,000,000</option>
                            <option value="1000001+" {{ old('income_level') === '1000001+' ? 'selected' : '' }}>Above ₦1,000,000</option>
                        </select>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary prev-step"><i class="ri-arrow-left-line align-middle me-1"></i> Previous</button>
                    <button type="button" class="btn btn-primary next-step">Next <i class="ri-arrow-right-line align-middle ms-1"></i></button>
                </div>
            </div>

            <div class="step" data-step="3">
                <h5 class="mb-3 text-primary"><i class="ri-map-pin-line me-2"></i> Residential Location</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="state">State</label>
                        <input type="text" class="form-control" id="state" value="Benue" name="state" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="lga">Local Government Area (LGA)</label>
                        <select class="form-select" id="lga" name="lga" required>
                            <option value="">Select LGA</option>
                            @php
                                $lgas = ['Ado', 'Agatu', 'Apa', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West', 'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo', 'Oju', 'Ohimini', 'Okpokwu', 'Otukpo', 'Tarka', 'Ukum', 'Ushongo', 'Vandeikya'];
                            @endphp
                            @foreach($lgas as $lga)
                                <option value="{{ $lga }}" {{ old('lga') === $lga ? 'selected' : '' }}>{{ $lga }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="ward">Ward/Community</label>
                        <input type="text" class="form-control" id="ward" name="ward" placeholder="Your Ward or Community Name" value="{{ old('ward') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="address">Contact Address (Street/Village)</label>
                        <textarea class="form-control" id="address" name="address" placeholder="e.g. House 5, Zone A, Agasha Village" required rows="1">{{ old('address') }}</textarea>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary prev-step"><i class="ri-arrow-left-line align-middle me-1"></i> Previous</button>
                    <button type="submit" class="btn btn-success"><i class="ri-check-line align-middle me-1"></i> Complete Profile</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 3;
    const $steps = $('.step');

    // Function to update the step indicator styles
    function updateStepIndicators() {
        $('.step-indicator-item').removeClass('active');
        $(`.step-indicator-item[data-step='${currentStep}']`).addClass('active');
    }

    // Function to show the current step and hide others
    function showStep(step) {
        $steps.removeClass('active').hide();
        $(`.step[data-step='${step}']`).addClass('active').fadeIn(400);
        updateStepIndicators();
    }

    // Next Step handler
    $('.next-step').click(function() {
        const $currentStepDiv = $steps.filter('.active');
        const currentInputs = $currentStepDiv.find('input[required], select[required], textarea[required]');
        let allValid = true;

        // Simple client-side validation for required fields
        currentInputs.each(function() {
            if (!this.checkValidity()) {
                allValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (allValid && currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        } else if (!allValid) {
             // Optional: Show a general error for invalid step
             Swal.fire({
                icon: 'error',
                title: 'Incomplete Information',
                text: 'Please fill out all required fields correctly before proceeding.',
                confirmButtonColor: '#556ee6'
            });
        }
    });

    // Previous Step handler
    $('.prev-step').click(function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Initial load
    showStep(currentStep);

    // Remove is-invalid class on input
    $('input, select, textarea').on('input change', function() {
        if (this.checkValidity()) {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>

</body>
</html>