<!-- 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Complete Your Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.complete') }}">
                        @csrf

                        
                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label for="nin" class="col-md-4 col-form-label text-md-right">{{ __('NIN') }}</label>

                            <div class="col-md-6">
                                <input id="number" type="text" class="form-control @error('nin') is-invalid @enderror" name="nin" value="{{ old('nin') }}" required autocomplete="nin" autofocus>

                                @error('nin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                     
                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('address') }}</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required autocomplete="address" autofocus>

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Date of Birth') }}</label>

                            <div class="col-md-6">
                                <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" required autocomplete="dob">

                                @error('dob')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                     
                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                            <div class="col-md-6">
                                <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                     
                        <div class="form-group row">
                            <label for="education" class="col-md-4 col-form-label text-md-right">{{ __('Education') }}</label>

                            <div class="col-md-6">
                                <input id="education" type="text" class="form-control @error('education') is-invalid @enderror" name="education" value="{{ old('education') }}" required autocomplete="education">

                                @error('education')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                     
                        <div class="form-group row">
                            <label for="household_size" class="col-md-4 col-form-label text-md-right">{{ __('Household Size') }}</label>

                            <div class="col-md-6">
                                <input id="household_size" type="number" class="form-control @error('household_size') is-invalid @enderror" name="household_size" value="{{ old('household_size') }}" required min="1">

                                @error('household_size')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                            <label for="dependents" class="col-md-4 col-form-label text-md-right">{{ __('Dependents') }}</label>

                            <div class="col-md-6">
                                <input id="dependents" type="number" class="form-control @error('dependents') is-invalid @enderror" name="dependents" value="{{ old('dependents') }}" required min="0">

                                @error('dependents')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="income_level" class="col-md-4 col-form-label text-md-right">{{ __('Income Level') }}</label>

                            <div class="col-md-6">
                                <input id="income_level" type="text" class="form-control @error('income_level') is-invalid @enderror" name="income_level" value="{{ old('income_level') }}" required>

                                @error('income_level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                   
                        <div class="form-group row">
                            <label for="lga" class="col-md-4 col-form-label text-md-right">{{ __('Local Government Area (LGA)') }}</label>

                            <div class="col-md-6">
                                <input id="lga" type="text" class="form-control @error('lga') is-invalid @enderror" name="lga" value="{{ old('lga') }}" required>

                                @error('lga')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Complete Profile') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



 -->








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benue State Integrated Agricultural Data and Access Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .progress-bar {
            background-color: #0d6efd;
            transition: width 0.3s ease;
        }

        .step {
            transition: all 0.3s ease;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <!-- Progress Bar -->
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>

                        <!-- Step 1: Welcome -->
                        <div class="step" id="step1">
                            <div class="text-center">
                                <h2 class="mb-4">Welcome to Farmer's Network!</h2>
                                <p class="mb-4">Let's complete your profile to get you started.</p>
                                <button class="btn btn-primary next-step">Get Started</button>
                            </div>
                        </div>

                        <!-- Step 2: Demographics Form -->
                        <div class="step" id="step2" style="display: none;">
                            <h3 class="mb-4">Complete Your Profile</h3>
                            <form id="demographicsForm" method="POST" action="{{ route('profile.complete') }}">
                             @csrf
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-lg-6">
                                         <div class="mb-4">
                                            <label class="form-label" for="nin">NIN</label>
                                            <input type="text" class="form-control" name="nin" required>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label" for="phone">Phone Number</label>
                                            <input type="tel" class="form-control" name="phone" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="dob">Date of Birth</label>
                                            <input type="date" class="form-control" name="dob" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="gender">Gender</label>
                                            <select class="form-control" name="gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="education">Education Level</label>
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

                                        
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="household_size">Household Size</label>
                                            <input type="number" class="form-control" name="household_size" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="dependents">Number of Dependents</label>
                                            <input type="number" class="form-control" name="dependents" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="income_level">Income Level</label>
                                            <select class="form-control" name="income_level" required>
                                                <option value="">Select Income Level</option>
                                                <option value="0-100000">Less than ₦100,000</option>
                                                <option value="100001-250000">₦100,001 - ₦250,000</option>
                                                <option value="250001-500000">₦250,001 - ₦500,000</option>
                                                <option value="500001-1000000">₦500,001 - ₦1,000,000</option>
                                                <option value="1000001+">Above ₦1,000,000</option>
                                            </select>
                                        </div>
                                         <div class="mb-4">
                                            <label class="form-label" for="address">State </label>
                                            <input type="text" class="form-control" value="Benue" name="state" readonly>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label" for="lga">Local Government Area</label>
                                            <select class="form-control" name="lga" required>
                                                <option value="">Select LGA</option>                                    
                                                <option value="Ado">Ado</option>
                                                <option value="Agatu">Agatu</option>
                                                <option value="Apa">Apa</option>
                                                <option value="Buruku">Buruku</option>
                                                <option value="Gboko">Gboko</option>
                                                <option value="Guma">Guma</option>
                                                <option value="Gwer East">Gwer East</option>
                                                <option value="Gwer West">Gwer West</option>
                                                <option value="Katsina-Ala">Katsina-Ala</option>
                                                <option value="Konshisha">Konshisha</option>
                                                <option value="Kwande">Kwande</option>
                                                <option value="Logo">Logo</option>
                                                <option value="Makurdi">Makurdi</option>
                                                <option value="Obi">Obi</option>
                                                <option value="Ogbadibo">Ogbadibo</option>
                                                <option value="Oju">Oju</option>
                                                <option value="Ohimini">Ohimini</option>
                                                <option value="Okpokwu">Okpokwu</option>
                                                <option value="Otpo">Otpo</option>
                                                <option value="Tarka">Tarka</option>
                                                <option value="Ukum">Ukum</option>
                                                <option value="Ushongo">Ushongo</option>
                                                <option value="Vandeikya">Vandeikya</option></div>
                                            </select>
                                        </div>                                      
                                         <div class="mb-4">
                                            <label class="form-label" for="ward">Ward </label>
                                            <input type="text" class="form-control" value="#" name="ward">
                                        </div>
                                        
                                    </div>
                                     <div class="mb-4">
                                            <label class="form-label" for="address">Contact Address: </label>
                                            <input type="text" class="form-control" name="address" required>
                                        </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="button" class="btn btn-secondary prev-step">Back</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>

                        <!-- Step 3: Completion -->
                        <div class="step" id="step3" style="display: none;">
                            <div class="text-center">
                                <h2 class="mb-4">Thank You!</h2>
                                <p class="mb-4">Your profile has been submitted successfully. Our team will review your information and get back to you shortly.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>

        $(document).ready(function () {
        let currentStep = 1;
        const totalSteps = 3;

        // Initialize toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
        };

        // Update progress bar
        function updateProgress() {
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            $(".progress-bar").css("width", `${progress}%`);
        }

        // Show step
        function showStep(step) {
            $(".step").hide();
            $(`#step${step}`).show();
            currentStep = step;
            updateProgress();
        }

        // Next step button handler
        $(".next-step").click(function () {
            showStep(currentStep + 1);
        });

        // Previous step button handler
        $(".prev-step").click(function () {
            showStep(currentStep - 1);
        });

        // // Form submission handler
        // $("#demographicsForm").on("submit", function (e) {
        //     e.preventDefault();

        //     // Collect form data
        //     const formData = $(this).serialize();

        //     // Simulate AJAX submission
        //     $.ajax({
        //     url: "your-backend-endpoint", // Replace with your actual endpoint
        //     method: "POST",
        //     data: formData,
        //     success: function (response) {
        //         // Show success message
        //         toastr.success("Profile updated successfully!");

        //         // Move to completion step
        //         showStep(3);
        //     },
        //     error: function (xhr, status, error) {
        //         // Show error message
        //         toastr.error("An error occurred. Please try again.");
        //     },
        //     });
        // });

        // Initialize first step
        showStep(1);
        });



    </script>
</body>
</html>