@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Partner</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.partners.index') }}">Partners</a></li>
                    <li class="breadcrumb-item active">Edit Partner</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Partner Organization</h4>

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="font-size-14 mb-3">Organization Details</h5>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label" for="legal_name">Legal Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('legal_name') is-invalid @enderror" 
                                            id="legal_name" name="legal_name" value="{{ old('legal_name', $partner->legal_name) }}" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="organization_type">Organization Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('organization_type') is-invalid @enderror" 
                                                id="organization_type" name="organization_type" required>
                                                <option value="">Select Type</option>
                                                @foreach($partner->getOrganizationTypeOptions() as $value => $label)
                                                <option value="{{ $value }}" {{ old('organization_type', $partner->organization_type) == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="registration_number">Registration Number</label>
                                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                                id="registration_number" name="registration_number" value="{{ old('registration_number', $partner->registration_number) }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="establishment_date">Establishment Date</label>
                                        <input type="date" class="form-control @error('establishment_date') is-invalid @enderror" 
                                            id="establishment_date" name="establishment_date" 
                                            value="{{ old('establishment_date', $partner->establishment_date ? $partner->establishment_date->format('Y-m-d') : '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description">Organization Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                            id="description" name="description" rows="3" required>{{ old('description', $partner->description) }}</textarea>
                                        <small class="form-text text-muted">Brief description of the organization and its activities (max 500 characters)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="focus_areas">Focus Areas <span class="text-danger">*</span></label>
                                        <div class="row">
                                            @foreach($partner->getFocusAreaOptions() as $value => $label)
                                            <div class="col-md-6">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" 
                                                        id="focus_area_{{ $value }}" name="focus_areas[]" 
                                                        value="{{ $value }}" {{ in_array($value, old('focus_areas', $partner->focus_areas ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="focus_area_{{ $value }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                            id="address" name="address" rows="2" required>{{ old('address', $partner->address) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="website">Website URL</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                            id="website" name="website" value="{{ old('website', $partner->website) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h5 class="font-size-14 mb-3">Contact & Financial Details</h5>
                            <div class="card border mb-4">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label" for="contact_person_name">Contact Person Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('contact_person_name') is-invalid @enderror" 
                                            id="contact_person_name" name="contact_person_name" value="{{ old('contact_person_name', $partner->contact_person_name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="contact_person_title">Job Title</label>
                                        <input type="text" class="form-control @error('contact_person_title') is-invalid @enderror" 
                                            id="contact_person_title" name="contact_person_title" value="{{ old('contact_person_title', $partner->contact_person_title) }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="contact_person_phone">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('contact_person_phone') is-invalid @enderror" 
                                                id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone', $partner->contact_person_phone) }}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="contact_person_email">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('contact_person_email') is-invalid @enderror" 
                                                id="contact_person_email" name="contact_person_email" value="{{ old('contact_person_email', $partner->contact_person_email) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label" for="tax_identification_number">Tax Identification Number</label>
                                        <input type="text" class="form-control @error('tax_identification_number') is-invalid @enderror" 
                                            id="tax_identification_number" name="tax_identification_number" value="{{ old('tax_identification_number', $partner->tax_identification_number) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="registration_certificate">Registration Certificate</label>
                                        <input type="file" class="form-control @error('registration_certificate') is-invalid @enderror" 
                                            id="registration_certificate" name="registration_certificate">
                                        <small class="form-text text-muted">Accepted formats: PDF, JPG, JPEG, PNG (max 5MB)</small>
                                        
                                        @if($partner->registration_certificate)
                                        <div class="mt-2">
                                            <p class="mb-0">Current file: 
                                                <a href="{{ Storage::url($partner->registration_certificate) }}" target="_blank">
                                                    View Document
                                                </a>
                                            </p>
                                            <small class="text-muted">Upload a new file to replace the current one</small>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="bank_name">Bank Name</label>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                            id="bank_name" name="bank_name" value="{{ old('bank_name', $partner->bank_name) }}">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="bank_account_name">Account Name</label>
                                            <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" 
                                                id="bank_account_name" name="bank_account_name" value="{{ old('bank_account_name', $partner->bank_account_name) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="bank_account_number">Account Number</label>
                                            <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                                                id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number', $partner->bank_account_number) }}">
                                        </div>
                                    </div>                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Partner</button>
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation
        $('form').on('submit', function() {
            // Ensure at least one focus area is selected
            if (!$('input[name="focus_areas[]"]:checked').length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select at least one focus area'
                });
                return false;
            }
            return true;
        });
    });
</script>
@endpush