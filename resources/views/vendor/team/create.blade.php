@extends('layouts.vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Add Team Member</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.team.index') }}">Team</a></li>
                    <li class="breadcrumb-item active">Add Member</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Team Member Information</h4>

                <form action="{{ route('vendor.team.store') }}" method="POST" id="teamMemberForm">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        <div class="invalid-feedback" id="name-error"></div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            <div class="invalid-feedback" id="email-error"></div>
                            <small class="text-success d-none" id="email-success">
                                <i class="mdi mdi-check-circle"></i> Email is available
                            </small>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" value="{{ old('phone_number') }}" maxlength="11" required>
                            <div class="invalid-feedback" id="phone-error"></div>
                            @error('phone_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Vendor Manager" {{ old('role') === 'Vendor Manager' ? 'selected' : '' }}>
                                Vendor Manager (Full Access)
                            </option>
                            <option value="Distribution Agent" {{ old('role') === 'Distribution Agent' ? 'selected' : '' }}>
                                Distribution Agent (Fulfillment Only)
                            </option>
                        </select>
                        <div class="invalid-feedback" id="role-error"></div>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            <strong>Vendor Manager:</strong> Can manage team, propose resources, and view analytics.<br>
                            <strong>Distribution Agent:</strong> Can only search farmers and mark fulfillments.
                        </small>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Account Credentials</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            <small class="text-muted">Minimum 8 characters</small>
                            <div class="invalid-feedback" id="password-error"></div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                            <div class="invalid-feedback" id="password-confirmation-error"></div>
                            <small class="text-success d-none" id="password-match">
                                <i class="mdi mdi-check-circle"></i> Passwords match
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('vendor.team.index') }}" class="btn btn-secondary">
                           Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                             Add Team Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('teamMemberForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone_number');
    const roleSelect = document.getElementById('role');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const submitBtn = document.getElementById('submitBtn');

    let emailCheckTimeout;
    let formValid = false;

    // Utility function to show error
    function showError(input, errorId, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        document.getElementById(errorId).textContent = message;
        document.getElementById(errorId).style.display = 'block';
        checkFormValidity();
    }

    // Utility function to show success
    function showSuccess(input, errorId) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        document.getElementById(errorId).textContent = '';
        document.getElementById(errorId).style.display = 'none';
        checkFormValidity();
    }

    // Check overall form validity
    function checkFormValidity() {
        const allValid = !document.querySelector('.is-invalid') && 
                        nameInput.value.trim() !== '' &&
                        emailInput.value.trim() !== '' &&
                        phoneInput.value.trim() !== '' &&
                        roleSelect.value !== '' &&
                        passwordInput.value !== '' &&
                        passwordConfirmInput.value !== '';
        
        formValid = allValid;
        submitBtn.disabled = !allValid;
    }

    // Name validation
    nameInput.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value === '') {
            showError(this, 'name-error', 'Full name is required');
        } else if (value.length < 3) {
            showError(this, 'name-error', 'Name must be at least 3 characters');
        } else if (value.length > 255) {
            showError(this, 'name-error', 'Name must not exceed 255 characters');
        } else {
            showSuccess(this, 'name-error');
        }
    });

    // Email validation with real-time availability check
    emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        // Hide success message while typing
        document.getElementById('email-success').classList.add('d-none');

        // Clear previous timeout
        clearTimeout(emailCheckTimeout);

        if (email === '') {
            showError(this, 'email-error', 'Email is required');
            return;
        }

        if (!emailRegex.test(email)) {
            showError(this, 'email-error', 'Please enter a valid email address');
            return;
        }

        // Check email availability after user stops typing (500ms delay)
        emailCheckTimeout = setTimeout(() => {
            checkEmailAvailability(email);
        }, 500);
    });

    function checkEmailAvailability(email) {
        // Show loading state
        emailInput.classList.remove('is-invalid', 'is-valid');
        document.getElementById('email-error').textContent = 'Checking availability...';
        document.getElementById('email-error').classList.remove('invalid-feedback');
        document.getElementById('email-error').classList.add('text-muted');
        document.getElementById('email-error').style.display = 'block';

        // AJAX call to check email
        fetch('{{ route("vendor.team.check-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('email-error').classList.remove('text-muted');
            document.getElementById('email-error').classList.add('invalid-feedback');

            if (data.available) {
                showSuccess(emailInput, 'email-error');
                document.getElementById('email-success').classList.remove('d-none');
            } else {
                showError(emailInput, 'email-error', 'This email is already registered');
                document.getElementById('email-success').classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Error checking email:', error);
            // On error, just mark as valid to not block the form
            showSuccess(emailInput, 'email-error');
        });
    }

    // Phone validation
    phoneInput.addEventListener('input', function() {
        // Allow only numbers
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    phoneInput.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value === '') {
            showError(this, 'phone-error', 'Phone number is required');
        } else if (value.length < 10) {
            showError(this, 'phone-error', 'Phone number must be at least 10 digits');
        } else if (value.length > 11) {
            showError(this, 'phone-error', 'Phone number must not exceed 11 digits');
        } else {
            showSuccess(this, 'phone-error');
        }
    });

    // Role validation
    roleSelect.addEventListener('change', function() {
        if (this.value === '') {
            showError(this, 'role-error', 'Please select a role');
        } else {
            showSuccess(this, 'role-error');
        }
    });

    // Password validation
    passwordInput.addEventListener('input', function() {
        const value = this.value;
        if (value.length > 0 && value.length < 8) {
            showError(this, 'password-error', 'Password must be at least 8 characters');
        } else if (value.length >= 8) {
            showSuccess(this, 'password-error');
        }

        // Also validate confirmation if it has value
        if (passwordConfirmInput.value !== '') {
            validatePasswordConfirmation();
        }
    });

    passwordInput.addEventListener('blur', function() {
        const value = this.value;
        if (value === '') {
            showError(this, 'password-error', 'Password is required');
        } else if (value.length < 8) {
            showError(this, 'password-error', 'Password must be at least 8 characters');
        }
    });

    // Password confirmation validation
    function validatePasswordConfirmation() {
        const password = passwordInput.value;
        const confirmation = passwordConfirmInput.value;

        if (confirmation === '') {
            passwordConfirmInput.classList.remove('is-invalid', 'is-valid');
            document.getElementById('password-match').classList.add('d-none');
            checkFormValidity();
            return;
        }

        if (password !== confirmation) {
            showError(passwordConfirmInput, 'password-confirmation-error', 'Passwords do not match');
            document.getElementById('password-match').classList.add('d-none');
        } else {
            showSuccess(passwordConfirmInput, 'password-confirmation-error');
            document.getElementById('password-match').classList.remove('d-none');
        }
    }

    passwordConfirmInput.addEventListener('input', validatePasswordConfirmation);
    passwordConfirmInput.addEventListener('blur', validatePasswordConfirmation);

    // Form submission
    form.addEventListener('submit', function(e) {
        // Final validation check
        let hasErrors = false;

        if (nameInput.value.trim() === '') {
            showError(nameInput, 'name-error', 'Full name is required');
            hasErrors = true;
        }

        if (emailInput.value.trim() === '') {
            showError(emailInput, 'email-error', 'Email is required');
            hasErrors = true;
        }

        if (phoneInput.value.trim() === '') {
            showError(phoneInput, 'phone-error', 'Phone number is required');
            hasErrors = true;
        }

        if (roleSelect.value === '') {
            showError(roleSelect, 'role-error', 'Please select a role');
            hasErrors = true;
        }

        if (passwordInput.value === '') {
            showError(passwordInput, 'password-error', 'Password is required');
            hasErrors = true;
        }

        if (passwordConfirmInput.value === '') {
            showError(passwordConfirmInput, 'password-confirmation-error', 'Password confirmation is required');
            hasErrors = true;
        }

        if (hasErrors || document.querySelector('.is-invalid')) {
            e.preventDefault();
            
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        } else {
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Adding...';
        }
    });

    // Initial form validity check
    checkFormValidity();
});
</script>
@endpush
@endsection