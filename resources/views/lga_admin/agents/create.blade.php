@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create New Enrollment Agent</h4>
            <div class="page-title-right">
                <a href="{{ route('lga_admin.agents.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Agents
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('lga_admin.agents.store') }}" id="agentForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Personal Information</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       placeholder="Enter full name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback" id="name_error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       placeholder="agent@example.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback" id="email_error"></div>
                                <small class="text-muted">This will be used for login</small>
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="tel" 
                                       name="phone_number" 
                                       id="phone_number" 
                                       class="form-control @error('phone_number') is-invalid @enderror" 
                                       value="{{ old('phone_number') }}"
                                       pattern="0[0-9]{10}"
                                       maxlength="11"
                                       title="Phone number must be 11 digits starting with 0"
                                       placeholder="e.g., 08012345678">
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback" id="phone_number_error"></div>
                                <small class="text-muted">11 digits starting with 0 (optional)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Security Information</h5>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Minimum 8 characters"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="ri-eye-line" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback" id="password_error"></div>
                                <small class="text-muted">Must be at least 8 characters long</small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Re-enter password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="ri-eye-line" id="toggleIconConfirm"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="password_confirmation_error"></div>
                            </div>

                            <div class="alert alert-info mt-4">
                                <i class="ri-information-line me-2"></i>
                                <strong>Administrative Assignment:</strong><br>
                                This agent will be assigned to <strong>{{ auth()->user()->administrativeUnit->name ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Role & Permissions</h6>
                                    <p class="card-text mb-2">
                                        <i class="ri-shield-check-line text-success me-1"></i>
                                        <strong>Role:</strong> Enrollment Agent
                                    </p>
                                    <p class="card-text mb-0">
                                        <i class="ri-list-check text-primary me-1"></i>
                                        <strong>Permissions:</strong> Enroll Farmers, Verify Farmer Data, Update Farmer Profiles, View Farmer Data
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Create Enrollment Agent
                        </button>
                        <a href="{{ route('lga_admin.agents.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
$(document).ready(function() {
    // Toggle password visibility for password field
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
        const toggleIcon = $('#toggleIcon');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            toggleIcon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
        } else {
            passwordInput.attr('type', 'password');
            toggleIcon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
        }
    });

    // Toggle password visibility for confirmation field
    $('#togglePasswordConfirm').on('click', function() {
        const passwordInput = $('#password_confirmation');
        const toggleIcon = $('#toggleIconConfirm');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            toggleIcon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
        } else {
            passwordInput.attr('type', 'password');
            toggleIcon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
        }
    });

    // Name validation
    $('#name').on('blur', function() {
        const name = $(this).val().trim();
        
        if (!name) {
            showFieldError($(this), 'Full name is required');
        } else if (name.length < 3) {
            showFieldError($(this), 'Name must be at least 3 characters long');
        } else {
            clearFieldError($(this));
        }
    });

    // Email validation and duplicate check
    $('#email').on('blur', function() {
        const email = $(this).val().trim();
        const field = $(this);
        
        if (!email) {
            showFieldError(field, 'Email address is required');
            return;
        }
        
        // Basic email format validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showFieldError(field, 'Please enter a valid email address');
            return;
        }
        
        // Check for duplicate email
        $.ajax({
            url: '/api/check-email',
            method: 'POST',
            data: {
                email: email,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.exists) {
                    showFieldError(field, 'This email is already registered');
                } else {
                    clearFieldError(field);
                }
            },
            error: function() {
                clearFieldError(field);
            }
        });
    });

    // Phone number validation
    $('#phone_number').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        
        const phone = $(this).val();
        
        if (phone.length > 0) {
            if (phone[0] !== '0') {
                showFieldError($(this), 'Phone number must start with 0');
            } else if (phone.length > 0 && phone.length < 11) {
                showFieldError($(this), 'Phone number must be 11 digits');
            } else {
                clearFieldError($(this));
            }
        } else {
            clearFieldError($(this));
        }
    });

    // Prevent paste of non-digit content in phone
    $('#phone_number').on('paste', function(e) {
        e.preventDefault();
        const pastedText = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        const cleaned = pastedText.replace(/[^0-9]/g, '').slice(0, 11);
        $(this).val(cleaned).trigger('input');
    });

    // Password validation
    $('#password').on('input', function() {
        const password = $(this).val();
        
        if (password.length > 0 && password.length < 8) {
            showFieldError($(this), 'Password must be at least 8 characters');
        } else {
            clearFieldError($(this));
        }
        
        // Also validate confirmation if it has a value
        if ($('#password_confirmation').val()) {
            $('#password_confirmation').trigger('input');
        }
    });

    // Password confirmation validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();
        
        if (confirmation && password !== confirmation) {
            showFieldError($(this), 'Passwords do not match');
        } else {
            clearFieldError($(this));
        }
    });

    // Form submission validation
    $('#agentForm').on('submit', function(e) {
        let hasErrors = false;

        // Validate name
        const name = $('#name').val().trim();
        if (!name) {
            showFieldError($('#name'), 'Full name is required');
            hasErrors = true;
        } else if (name.length < 3) {
            showFieldError($('#name'), 'Name must be at least 3 characters long');
            hasErrors = true;
        }

        // Validate email
        const email = $('#email').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            showFieldError($('#email'), 'Email address is required');
            hasErrors = true;
        } else if (!emailRegex.test(email)) {
            showFieldError($('#email'), 'Please enter a valid email address');
            hasErrors = true;
        }

        // Validate phone (only if provided)
        const phone = $('#phone_number').val();
        if (phone.length > 0) {
            if (phone.length !== 11 || phone[0] !== '0') {
                showFieldError($('#phone_number'), 'Phone number must be 11 digits starting with 0');
                hasErrors = true;
            }
        }

        // Validate password
        const password = $('#password').val();
        if (!password) {
            showFieldError($('#password'), 'Password is required');
            hasErrors = true;
        } else if (password.length < 8) {
            showFieldError($('#password'), 'Password must be at least 8 characters');
            hasErrors = true;
        }

        // Validate password confirmation
        const confirmation = $('#password_confirmation').val();
        if (!confirmation) {
            showFieldError($('#password_confirmation'), 'Please confirm your password');
            hasErrors = true;
        } else if (password !== confirmation) {
            showFieldError($('#password_confirmation'), 'Passwords do not match');
            hasErrors = true;
        }

        // Check for any visible error messages
        if ($('.invalid-feedback:visible').length > 0) {
            hasErrors = true;
        }

        if (hasErrors) {
            e.preventDefault();
            toastr.error('Please correct the errors in the form before submitting');
            
            // Scroll to first error
            const firstError = $('.is-invalid:first');
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        }
    });

    // Helper functions
    function showFieldError(field, message) {
        field.addClass('is-invalid');
        const errorId = field.attr('id') + '_error';
        $('#' + errorId).text(message).show();
    }

    function clearFieldError(field) {
        field.removeClass('is-invalid');
        const errorId = field.attr('id') + '_error';
        $('#' + errorId).hide();
    }
});
</script>
@endpush