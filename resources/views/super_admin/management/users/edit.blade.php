@extends('layouts.super_admin')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit User: {{ $user->name }}</h4>
                    <div class="page-title-right">
                        {{-- FIX 1: Changed route from 'super_admin.management.users' to 'super_admin.management.users.index' --}}
                        <a href="{{ route('super_admin.management.users.index') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line align-middle me-1"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('super_admin.management.users.update', $user) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Basic Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                        <small class="text-muted">Leave blank to keep current password</small>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Account Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                                            <option value="onboarded" {{ old('status', $user->status) == 'onboarded' ? 'selected' : '' }}>Onboarded (Active)</option>
                                            <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="mb-3">Role & Unit Assignment</h5>
                                    
                                    <div class="mb-3">
                                        <label for="role_id" class="form-label">Assign Role <span class="text-danger">*</span></label>
                                        <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                                            <option value="">-- Select Role --</option>
                                            @php
                                                $currentRoleId = old('role_id', $user->roles->first()->id ?? '');
                                            @endphp
                                            @foreach($roles as $id => $name)
                                                <option value="{{ $id }}" {{ $currentRoleId == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div id="administrative_unit_section" style="display: none;">
                                        <div class="alert alert-info">
                                            <i class="ri-information-line"></i> This role requires administrative unit assignment.
                                        </div>

                                        <div class="mb-3">
                                            <label for="administrative_type" class="form-label">Administrative Unit Type</label>
                                            <select id="administrative_type" name="administrative_type" class="form-control @error('administrative_type') is-invalid @enderror">
                                                @php
                                                    $currentUnitType = old('administrative_type', $user->administrative_type ? class_basename($user->administrative_type) : '');
                                                @endphp
                                                <option value="">-- Select Type --</option>
                                                <option value="Department" {{ $currentUnitType == 'Department' ? 'selected' : '' }}>Department</option>
                                                <option value="Agency" {{ $currentUnitType == 'Agency' ? 'selected' : '' }}>Agency</option>
                                                <option value="LGA" {{ $currentUnitType == 'LGA' ? 'selected' : '' }}>LGA</option>
                                            </select>
                                            @error('administrative_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="administrative_id" class="form-label">Administrative Unit</label>
                                            <select id="administrative_id" name="administrative_id" class="form-control @error('administrative_id') is-invalid @enderror">
                                                <option value="">-- Select Unit --</option>
                                            </select>
                                            @error('administrative_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line align-middle me-1"></i> Update User
                                </button>
                                {{-- FIX 1: Changed route from 'super_admin.management.users' to 'super_admin.management.users.index' --}}
                                <a href="{{ route('super_admin.management.users.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initial data passed from the controller
    const departments = @json($departments);
    const agencies = @json($agencies);
    const lgas = @json($lgas);
    const unitRoleIds = Object.keys(@json($unitRoles)).map(id => String(id));
    
    // User data for initial state
    const initialUnitType = '{{ old('administrative_type', $user->administrative_type ? class_basename($user->administrative_type) : '') }}';
    const initialUnitId = '{{ old('administrative_id', $user->administrative_id ?? '') }}';
    
    // DOM elements
    const roleSelect = document.getElementById('role_id');
    const unitSection = document.getElementById('administrative_unit_section');
    const unitTypeSelect = document.getElementById('administrative_type');
    const unitIdSelect = document.getElementById('administrative_id');

    // Event listeners
    roleSelect.addEventListener('change', toggleUnitSection);
    unitTypeSelect.addEventListener('change', updateUnits);
    
    // Initial setup on load
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle the section based on the currently selected role
        toggleUnitSection(false); 
    });

    function toggleUnitSection(resetUnitType = true) {
        const selectedRoleId = String(roleSelect.value);

        if (unitRoleIds.includes(selectedRoleId)) {
            unitSection.style.display = 'block';
        } else {
            unitSection.style.display = 'none';
            if (resetUnitType) {
                // Only clear the type if the user changed the role away from a unit-required role
                unitTypeSelect.value = '';
            }
        }
        
        // Always run updateUnits to either populate the correct list or reset it
        updateUnits();
    }

    function updateUnits() {
        const type = unitTypeSelect.value;
        const currentSelectedId = unitIdSelect.value;
        unitIdSelect.innerHTML = '<option value="">-- Select Unit --</option>';

        let units = [];
        if (type === 'Department') {
            units = departments;
        } else if (type === 'Agency') {
            units = agencies;
        } else if (type === 'LGA') {
            units = lgas;
        }
        
        units.forEach(unit => {
            const option = document.createElement('option');
            option.value = unit.id;
            option.textContent = unit.name;
            unitIdSelect.appendChild(option);
        });

        // Try to re-select the existing value (from old input or $user)
        let idToSelect = initialUnitId;

        // If the type changed, we check the current selected value first
        if (type === initialUnitType) {
            idToSelect = currentSelectedId || initialUnitId;
        } else {
            // If the unit type was manually changed, clear the ID selection
            idToSelect = '{{ old('administrative_id') }}';
        }

        if (idToSelect) {
            unitIdSelect.value = idToSelect;
        }
    }

    // Call updateUnits once the DOM is ready to populate the correct unit list for the loaded user
    if (initialUnitType) {
        // Must call updateUnits *after* the DOM is ready and the selects are rendered
        // The DOMContentLoaded handler already triggers toggleUnitSection(false), which calls updateUnits
        // This ensures the unit list is populated correctly on load.
    }
</script>
@endpush