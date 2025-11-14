@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Vendor</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.vendors.update', $vendor) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
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
                                   id="legal_name" name="legal_name" value="{{ old('legal_name', $vendor->legal_name) }}" required>
                            @error('legal_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="registration_number" class="form-label">Registration Number</label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                   id="registration_number" name="registration_number" value="{{ old('registration_number', $vendor->registration_number) }}">
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
                                    <option value="{{ $key }}" {{ old('organization_type', $vendor->organization_type) == $key ? 'selected' : '' }}>
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
                                   id="establishment_date" name="establishment_date" 
                                   value="{{ old('establishment_date', $vendor->establishment_date?->format('Y-m-d')) }}">
                            @error('establishment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2" required>{{ old('address', $vendor->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" name="website" value="{{ old('website', $vendor->website) }}">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tax_identification_number" class="form-label">Tax ID Number</label>
                            <input type="text" class="form-control @error('tax_identification_number') is-invalid @enderror" 
                                   id="tax_identification_number" name="tax_identification_number" 
                                   value="{{ old('tax_identification_number', $vendor->tax_identification_number) }}">
                            @error('tax_identification_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" maxlength="500" required>{{ old('description', $vendor->description) }}</textarea>
                        <small class="text-muted">Maximum 500 characters</small>
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
                                    <input class="form-check-input" type="checkbox" name="focus_areas[]" 
                                           value="{{ $key }}" id="focus_{{ $key }}"
                                           {{ in_array($key, old('focus_areas', $vendor->focus_areas ?? [])) ? 'checked' : '' }}>
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
                                   id="contact_person_name" name="contact_person_name" 
                                   value="{{ old('contact_person_name', $vendor->contact_person_name) }}" required>
                            @error('contact_person_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="contact_person_title" class="form-label">Title/Position</label>
                            <input type="text" class="form-control @error('contact_person_title') is-invalid @enderror" 
                                   id="contact_person_title" name="contact_person_title" 
                                   value="{{ old('contact_person_title', $vendor->contact_person_title) }}">
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
                                   value="{{ old('contact_person_phone', $vendor->contact_person_phone) }}" required>
                            @error('contact_person_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="contact_person_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('contact_person_email') is-invalid @enderror" 
                                   id="contact_person_email" name="contact_person_email" 
                                   value="{{ old('contact_person_email', $vendor->contact_person_email) }}" required>
                            @error('contact_person_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                               id="bank_name" name="bank_name" value="{{ old('bank_name', $vendor->bank_name) }}">
                        @error('bank_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bank_account_name" class="form-label">Account Name</label>
                        <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" 
                               id="bank_account_name" name="bank_account_name" 
                               value="{{ old('bank_account_name', $vendor->bank_account_name) }}">
                        @error('bank_account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="bank_account_number" class="form-label">Account Number</label>
                        <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                               id="bank_account_number" name="bank_account_number" 
                               value="{{ old('bank_account_number', $vendor->bank_account_number) }}">
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
                    
                    @if($vendor->registration_certificate)
                    <div class="mb-3">
                        <p class="text-muted mb-2">Current Certificate:</p>
                        <a href="{{ Storage::url($vendor->registration_certificate) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary">
                            View Current
                        </a>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="registration_certificate" class="form-label">
                            {{ $vendor->registration_certificate ? 'Replace Certificate' : 'Upload Certificate' }}
                        </label>
                        <input type="file" class="form-control @error('registration_certificate') is-invalid @enderror" 
                               id="registration_certificate" name="registration_certificate" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max: 5MB)</small>
                        @error('registration_certificate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <!-- <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Status</h4>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" 
                               name="is_active" {{ old('is_active', $vendor->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active Vendor
                        </label>
                    </div>
                </div>
            </div> -->

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                             Update Vendor
                        </button>
                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="btn btn-secondary">
                             Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection