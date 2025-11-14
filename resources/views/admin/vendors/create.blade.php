@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Register New Vendor</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">Register</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.vendors.store') }}" method="POST" enctype="multipart/form-data" id="vendorForm">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Company Information -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Company Information</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="legal_name" class="form-label">Legal Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('legal_name') is-invalid @enderror" 
                                   id="legal_name" name="legal_name" value="{{ old('legal_name') }}" required>
                            @error('legal_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="legal_name_error"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="registration_number" class="form-label">Registration Number</label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                   id="registration_number" name="registration_number" value="{{ old('registration_number') }}">
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="organization_type" class="form-label">Organization Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('organization_type') is-invalid @enderror" 
                                    id="organization_type" name="organization_type" required>
                                <option value="">Select Type</option>
                                @foreach($vendor->getOrganizationTypeOptions() as $key => $label)
                                    <option value="{{ $key }}" {{ old('organization_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('organization_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="establishment_date" class="form-label">Establishment Date</label>
                            <input type="date" class="form-control @error('establishment_date') is-invalid @enderror" 
                                   id="establishment_date" name="establishment_date" value="{{ old('establishment_date') }}">
                            @error('establishment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                       <div class="col-md-6">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                id="website" name="website" 
                                value="{{ old('website') }}"
                                placeholder="https://example.com">                           
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="website_error"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="tax_identification_number" class="form-label">Tax ID Number</label>
                            <input type="text" class="form-control @error('tax_identification_number') is-invalid @enderror" 
                                   id="tax_identification_number" name="tax_identification_number" value="{{ old('tax_identification_number') }}">
                            @error('tax_identification_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" maxlength="500" required>{{ old('description') }}</textarea>
                        <small class="text-muted">Maximum 500 characters (<span id="char_count">0</span>/500)</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Focus Areas <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach($vendor->getFocusAreaOptions() as $key => $label)
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input focus-area-checkbox" type="checkbox" name="focus_areas[]" 
                                           value="{{ $key }}" id="focus_{{ $key }}"
                                           {{ in_array($key, old('focus_areas', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="focus_{{ $key }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('focus_areas')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="text-danger" id="focus_areas_error"></div>
                    </div>
                </div>
            </div>

            <!-- Contact Person Information -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Contact Person Information</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="contact_person_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contact_person_name') is-invalid @enderror" 
                                   id="contact_person_name" name="contact_person_name" value="{{ old('contact_person_name') }}" required>
                            @error('contact_person_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="contact_person_title" class="form-label">Title/Position</label>
                            <input type="text" class="form-control @error('contact_person_title') is-invalid @enderror" 
                                   id="contact_person_title" name="contact_person_title" value="{{ old('contact_person_title') }}">
                            @error('contact_person_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="contact_person_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('contact_person_phone') is-invalid @enderror" 
                                id="contact_person_phone" name="contact_person_phone" 
                                value="{{ old('contact_person_phone') }}" 
                                pattern="0[0-9]{10}" 
                                maxlength="11"                            
                                title="Phone number must be 11 digits starting with 0"
                                required>                        
                            @error('contact_person_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="contact_person_phone_error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="contact_person_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('contact_person_email') is-invalid @enderror" 
                                   id="contact_person_email" name="contact_person_email" value="{{ old('contact_person_email') }}" required>
                            @error('contact_person_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="contact_person_email_error"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendor Manager Account -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Vendor Manager Account</h4>
                    <p class="text-muted">Create the primary user account with full vendor management rights.</p>
                    
                    <div class="mb-3">
                        <label for="manager_name" class="form-label">Manager Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('manager_name') is-invalid @enderror" 
                               id="manager_name" name="manager_name" value="{{ old('manager_name') }}" required>
                        @error('manager_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="manager_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('manager_email') is-invalid @enderror" 
                                   id="manager_email" name="manager_email" value="{{ old('manager_email') }}" required>
                            @error('manager_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="manager_email_error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="manager_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('manager_phone') is-invalid @enderror" 
                                id="manager_phone" name="manager_phone" 
                                value="{{ old('manager_phone') }}" 
                                pattern="0[0-9]{10}" 
                                maxlength="11"                             
                                title="Phone number must be 11 digits starting with 0"
                                required>                        
                            @error('manager_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="manager_phone_error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="manager_password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('manager_password') is-invalid @enderror" 
                                   id="manager_password" name="manager_password" required>
                            <small class="text-muted">Minimum 8 characters</small>
                            @error('manager_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="invalid-feedback" id="manager_password_error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="manager_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="manager_password_confirmation" name="manager_password_confirmation" required>
                            <div class="invalid-feedback" id="password_confirmation_error"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Banking Information -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Banking Information</h4>
                    
                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Bank Name</label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                               id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bank_account_name" class="form-label">Account Name</label>
                        <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" 
                               id="bank_account_name" name="bank_account_name" value="{{ old('bank_account_name') }}">
                        @error('bank_account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bank_account_number" class="form-label">Account Number</label>
                        <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                               id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}" maxlength="11" >
                        @error('bank_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Document Upload -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Registration Certificate</h4>
                    
                    <div class="mb-3">
                        <label for="registration_certificate" class="form-label">Upload Certificate</label>
                        <input type="file" class="form-control @error('registration_certificate') is-invalid @enderror" 
                               id="registration_certificate" name="registration_certificate" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</small>
                        @error('registration_certificate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="invalid-feedback" id="registration_certificate_error"></div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Register Vendor
                        </button>
                        <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
$(document).ready(function() {
    // Character count for description
    $('#description').on('input', function() {
        $('#char_count').text($(this).val().length);
    });
    
    // Update count on page load if there's old input
    $('#char_count').text($('#description').val().length);

    // Phone number validation - only allow digits
    $('input[type="tel"]').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        
        if (this.value.length > 0 && this.value[0] !== '0') {
            showFieldError($(this), 'Phone number must start with 0');
        } else if (this.value.length > 0 && this.value.length < 11) {
            showFieldError($(this), 'Phone number must be 11 digits');
        } else {
            clearFieldError($(this));
        }
    });

    // Prevent paste of non-digit content
    $('input[type="tel"]').on('paste', function(e) {
        e.preventDefault();
        const pastedText = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        const cleaned = pastedText.replace(/[^0-9]/g, '').slice(0, 11);
        $(this).val(cleaned).trigger('input');
    });

    // Email validation and duplicate check
    $('#manager_email, #contact_person_email').on('blur', function() {
        const email = $(this).val();
        const field = $(this);
        
        if (!email) return;
        
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

    // Website URL validation
    $('#website').on('blur', function() {
        const url = $(this).val();
        if (url && !url.match(/^https?:\/\/.+/)) {
            showFieldError($(this), 'Website must start with http:// or https://');
        } else {
            clearFieldError($(this));
        }
    });

    // Password validation
    $('#manager_password').on('input', function() {
        const password = $(this).val();
        
        if (password.length > 0 && password.length < 8) {
            showFieldError($(this), 'Password must be at least 8 characters');
        } else {
            clearFieldError($(this));
        }
        
        // Also validate confirmation if it has a value
        if ($('#manager_password_confirmation').val()) {
            $('#manager_password_confirmation').trigger('input');
        }
    });

    // Password confirmation validation
    $('#manager_password_confirmation').on('input', function() {
        const password = $('#manager_password').val();
        const confirmation = $(this).val();
        
        if (confirmation && password !== confirmation) {
            showFieldError($(this), 'Passwords do not match');
        } else {
            clearFieldError($(this));
        }
    });

    // Focus areas validation
    $('.focus-area-checkbox').on('change', function() {
        const checkedCount = $('.focus-area-checkbox:checked').length;
        const errorDiv = $('#focus_areas_error');
        
        if (checkedCount === 0) {
            errorDiv.text('Please select at least one focus area').show();
        } else {
            errorDiv.hide();
        }
    });

    // File upload validation
    $('#registration_certificate').on('change', function() {
        const file = this.files[0];
        if (file) {
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                showFieldError($(this), 'Only PDF, JPG, and PNG files are allowed');
                this.value = '';
            } else if (file.size > maxSize) {
                showFieldError($(this), 'File size must not exceed 5MB');
                this.value = '';
            } else {
                clearFieldError($(this));
            }
        }
    });

    // Form submission validation
    $('#vendorForm').on('submit', function(e) {
        let hasErrors = false;

        // Check all required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                showFieldError($(this), 'This field is required');
                hasErrors = true;
            }
        });

        // Check focus areas
        if ($('.focus-area-checkbox:checked').length === 0) {
            $('#focus_areas_error').text('Please select at least one focus area').show();
            hasErrors = true;
        }

        // Check phone numbers
        $('input[type="tel"]').each(function() {
            if ($(this).val().length !== 11 || $(this).val()[0] !== '0') {
                showFieldError($(this), 'Phone number must be 11 digits starting with 0');
                hasErrors = true;
            }
        });

        // Check password match
        if ($('#manager_password').val() !== $('#manager_password_confirmation').val()) {
            showFieldError($('#manager_password_confirmation'), 'Passwords do not match');
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
            $('html, body').animate({
                scrollTop: $('.is-invalid:first').offset().top - 100
            }, 500);
        }
    });

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
@endsection