@extends('layouts.vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Team Member</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.team.index') }}">Team</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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

                <form action="{{ route('vendor.team.update', $teamMember) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $teamMember->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $teamMember->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" value="{{ old('phone_number', $teamMember->phone_number) }}" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Vendor Manager" {{ old('role', $teamMember->roles->first()->name) === 'Vendor Manager' ? 'selected' : '' }}>
                                Vendor Manager (Full Access)
                            </option>
                            <option value="Distribution Agent" {{ old('role', $teamMember->roles->first()->name) === 'Distribution Agent' ? 'selected' : '' }}>
                                Distribution Agent (Fulfillment Only)
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        To change the password for this user, use the "Reset Password" button on the team list page.
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('vendor.team.index') }}" class="btn btn-secondary">
                             Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                             Update Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection