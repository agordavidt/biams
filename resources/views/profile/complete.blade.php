<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - Benue State Agricultural Network</title>
    
    <!-- CSS -->
    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        .welcome-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                        url('{{ asset("dashboard/images/farming-bg.jpg") }}') center/cover;
            padding: 2rem;
        }

        .welcome-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 800px;
            width: 100%;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 1.5rem;
        }

        .progress {
            height: 8px;
            margin: 2rem 0;
            border-radius: 4px;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }

        .btn-start {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            border-radius: 50px;
            background: #0d6efd;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .step-title {
            color: #0d6efd;
            margin-bottom: 1.5rem;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .benefits-list li {
            padding: 0.5rem 0;
            padding-left: 2rem;
            position: relative;
        }

        .benefits-list li:before {
            content: "✓";
            color: #0d6efd;
            position: absolute;
            left: 0;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="welcome-section">
    <div class="welcome-card">
        <!-- Initial Welcome Screen -->
        <div id="welcome-content" class="text-center">
            <img src="{{ asset('dashboard/images/benue_logo.jpeg') }}" alt="Logo" class="logo">
            <h3 class="mb-4">Welcome to Benue State Integrated Agricultural Data Assets Management System</h3>
            <p class="lead mb-4">Complete your profile to unlock all features and start your agricultural journey with us.</p>
            
            <ul class="benefits-list text-start">
                <li>Access agricultural resources and funding opportunities</li>
                <li>Connect with other farmers in your community</li>
                <li>Get direct access to market opportunities</li>
                <li>Receive updates on agricultural programs and support</li>
            </ul>

            <button id="start-profile" class="btn btn-start btn-primary">Complete Your Profile</button>
        </div>

        <!-- Profile Form Section -->
        <div id="profile-form-section" style="display: none;">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>

            <form id="profileForm" method="POST" action="{{ route('profile.complete') }}">
                @csrf
                
                <!-- Step 1: Basic Information -->
                <div class="step active" data-step="1">
                    <h4 class="step-title">Step 1: Basic Information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIN</label>
                            <input type="text" class="form-control" name="nin" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="dob" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-control" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-primary next-step">Next</button>
                    </div>
                </div>

                <!-- Step 2: Education & Household -->
                <div class="step" data-step="2">
                    <h4 class="step-title">Step 2: Education & Household Information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Education Level</label>
                            <select class="form-control" name="education" required>
                                <option value="">Select Education Level</option>
                                <option value="no_formal">No Formal School</option>
                                <option value="primary">Primary School</option>
                                <option value="secondary">Secondary School</option>
                                <option value="undergraduate">Undergraduate</option>
                                <option value="graduate">Graduate</option>
                                <option value="postgraduate">Post Graduate</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Household Size</label>
                            <input type="number" class="form-control" name="household_size" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Number of Dependents</label>
                            <input type="number" class="form-control" name="dependents" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Income Level</label>
                            <select class="form-control" name="income_level" required>
                                <option value="">Select Income Level</option>
                                <option value="0-100000">Less than ₦100,000</option>
                                <option value="100001-250000">₦100,001 - ₦250,000</option>
                                <option value="250001-500000">₦250,001 - ₦500,000</option>
                                <option value="500001-1000000">₦500,001 - ₦1,000,000</option>
                                <option value="1000001+">Above ₦1,000,000</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary prev-step">Previous</button>
                        <button type="button" class="btn btn-primary next-step">Next</button>
                    </div>
                </div>

                <!-- Step 3: Location -->
                <div class="step" data-step="3">
                    <h4 class="step-title">Step 3: Location Information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" value="Benue" name="state" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Local Government Area</label>
                            <select class="form-control" name="lga" required>
                                <option value="">Select LGA</option>
                                @foreach(['Ado', 'Agatu', 'Apa', 'Buruku', 'Gboko', 'Guma', 'Gwer East', 'Gwer West', 
                                        'Katsina-Ala', 'Konshisha', 'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo', 
                                        'Oju', 'Ohimini', 'Okpokwu', 'Otukpo', 'Tarka', 'Ukum', 'Ushongo', 'Vandeikya'] as $lga)
                                    <option value="{{ $lga }}">{{ $lga }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ward</label>
                            <input type="text" class="form-control" name="ward" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Contact Address</label>
                            <textarea class="form-control" name="address" required rows="3"></textarea>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary prev-step">Previous</button>
                        <button type="submit" class="btn btn-success">Complete Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 3;

    // Start Profile Button Click
    $('#start-profile').click(function() {
        $('#welcome-content').fadeOut(300, function() {
            $('#profile-form-section').fadeIn(300);
        });
    });

    // Update Progress Bar
    function updateProgress() {
        const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
        $(".progress-bar").css("width", `${progress}%`);
    }

    // Show Step
    function showStep(step) {
        $('.step').removeClass('active').hide();
        $(`.step[data-step="${step}"]`).addClass('active').fadeIn();
        currentStep = step;
        updateProgress();
    }

    // Next Step
    $('.next-step').click(function() {
        // Validate current step
        const currentStepElement = $(`.step[data-step="${currentStep}"]`);
        const inputs = currentStepElement.find('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.each(function() {
            if (!this.value) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                title: 'Please Complete All Fields',
                text: 'All fields are required to proceed.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }
    });

    // Previous Step
    $('.prev-step').click(function() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    // Form Submission
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    title: 'Profile Completed!',
                    text: 'Your profile has been successfully completed. You will now be redirected to the dashboard.',
                    icon: 'success',
                    confirmButtonText: 'Continue to Dashboard'
                }).then((result) => {
                    window.location.href = '{{ route("home") }}';
                });
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error',
                    text: 'There was an error completing your profile. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Input validation on change
    $('input, select, textarea').on('change', function() {
        if (this.value) {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>

</body>
</html>