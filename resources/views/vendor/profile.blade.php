@extends('layouts.vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Account Settings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        {{-- Profile Information Card --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Profile Information</h5>
                <p class="text-muted mb-4">Update your personal information</p>

                <!-- @if(session('success') && !request()->has('password'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ri-check-line me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif -->

                <form method="POST" action="{{ route('vendor.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            {{-- Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Phone Number --}}
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">
                                    Phone Number
                                </label>
                                <input 
                                    type="text" 
                                    name="phone_number" 
                                    id="phone_number"
                                    class="form-control @error('phone_number') is-invalid @enderror"
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                >
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            {{-- Email (Read-only) --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Email Address
                                </label>
                                <input 
                                    type="email" 
                                    value="{{ $user->email }}"
                                    class="form-control"
                                    disabled
                                >
                                <small class="text-muted">Email address cannot be changed</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Role --}}
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <div>
                                    <span class="status-badge status-onboarded">
                                        Vendor Manager
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                        <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Company Information Card (Read-only) --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Company Information</h5>
                <p class="text-muted mb-4">Your vendor company details</p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Legal Name</label>
                        <p class="mb-0">{{ $vendor->legal_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Organization Type</label>
                        <p class="mb-0">{{ ucfirst($vendor->organization_type) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Contact Email</label>
                        <p class="mb-0">{{ $vendor->contact_person_email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Contact Phone</label>
                        <p class="mb-0">{{ $vendor->contact_person_phone }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Address</label>
                        <p class="mb-0">{{ $vendor->address }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Status</label>
                        <p class="mb-0">
                            <span class="status-badge {{ $vendor->is_active ? 'status-onboarded' : 'status-rejected' }}">
                                {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="ri-information-line me-2"></i>
                    To update company information, please contact the State Admin
                </div>
            </div>
        </div>

        {{-- Security Section (Password Update) --}}
        @include('components.password-update-form')
    </div>
</div>
@endsection