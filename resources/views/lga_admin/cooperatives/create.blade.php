@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Register New Cooperative</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.cooperatives.index') }}">Cooperatives</a></li>
                    <li class="breadcrumb-item active">Register New</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-building-line me-2"></i>Cooperative Registration Form
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('lga_admin.cooperatives.store') }}" method="POST" id="cooperativeForm">
                    @csrf

                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ri-information-line me-1"></i>Basic Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="registration_number" class="form-label">
                                    Registration Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('registration_number') is-invalid @enderror" 
                                       id="registration_number" 
                                       name="registration_number" 
                                       value="{{ old('registration_number') }}" 
                                       placeholder="e.g., COOP/BEN/2025/001"
                                       required>
                                <small class="text-muted">Official government or association registration number</small>
                                @error('registration_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Cooperative Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="e.g., Makurdi Farmers Cooperative Society"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ri-contacts-line me-1"></i>Contact Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_person" class="form-label">
                                    Contact Person <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('contact_person') is-invalid @enderror" 
                                       id="contact_person" 
                                       name="contact_person" 
                                       value="{{ old('contact_person') }}" 
                                       placeholder="Full name of primary official"
                                       required>
                                <small class="text-muted">Name of Chairman, Secretary, or primary representative</small>
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       placeholder="e.g., 08012345678"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address (Optional)</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="cooperative@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Operational Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ri-bar-chart-line me-1"></i>Operational Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_member_count" class="form-label">
                                    Total Member Count
                                </label>
                                <input type="number" 
                                       class="form-control @error('total_member_count') is-invalid @enderror" 
                                       id="total_member_count" 
                                       name="total_member_count" 
                                       value="{{ old('total_member_count', 0) }}" 
                                       min="0"
                                       placeholder="Number of registered members">
                                <small class="text-muted">As reported by the cooperative</small>
                                @error('total_member_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_land_size" class="form-label">
                                    Total Land Managed (Hectares)
                                </label>
                                <input type="number" 
                                       class="form-control @error('total_land_size') is-invalid @enderror" 
                                       id="total_land_size" 
                                       name="total_land_size" 
                                       value="{{ old('total_land_size') }}" 
                                       step="0.01"
                                       min="0"
                                       placeholder="e.g., 250.50">
                                <small class="text-muted">Aggregate land managed by cooperative members</small>
                                @error('total_land_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Primary Activities <span class="text-danger">*</span>
                                </label>
                                <div class="row">
                                    @foreach($activities as $activity)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="primary_activities[]" 
                                                   value="{{ $activity }}" 
                                                   id="activity_{{ $loop->index }}"
                                                   {{ in_array($activity, old('primary_activities', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="activity_{{ $loop->index }}">
                                                {{ $activity }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('primary_activities')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('lga_admin.cooperatives.index') }}" class="btn btn-light">
                                    <i class="ri-close-line me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Register Cooperative
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation
    document.getElementById('cooperativeForm').addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('input[name="primary_activities[]"]:checked');
        if (checkboxes.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Primary Activities Required',
                text: 'Please select at least one primary activity for the cooperative.',
            });
            return false;
        }
    });
</script>
@endpush