@extends('layouts.super_admin')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Create New User</h4>
                    <div class="page-title-right">                       
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
                        <form method="POST" action="{{ route('super_admin.management.users.store') }}" id="createUserForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Basic Information</h5>
                                    
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                    </div>                                    
                                </div>

                                <div class="col-md-6">
                                    <h5 class="mb-3">Role & Unit Assignment</h5>
                                    
                                    <div class="mb-3">
                                        <label for="role_id" class="form-label">Assign Role <span class="text-danger">*</span></label>
                                        <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                                            <option value="">-- Select Role --</option>
                                            @foreach($roles as $id => $name)
                                                <option value="{{ $id }}" {{ old('role_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                                            <label for="administrative_type" class="form-label">Administrative Unit Type <span class="text-danger" id="type_required">*</span></label>
                                            <select id="administrative_type" name="administrative_type" class="form-control @error('administrative_type') is-invalid @enderror">
                                                <option value="">-- Select Type --</option>
                                                <option value="Department" {{ old('administrative_type') == 'Department' ? 'selected' : '' }}>Department</option>
                                                <option value="Agency" {{ old('administrative_type') == 'Agency' ? 'selected' : '' }}>Agency</option>
                                                <option value="LGA" {{ old('administrative_type') == 'LGA' ? 'selected' : '' }}>LGA</option>
                                            </select>
                                            @error('administrative_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="administrative_id" class="form-label">Administrative Unit <span class="text-danger" id="unit_required">*</span></label>
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
                                    <i class="ri-save-line align-middle me-1"></i> Create User
                                </button>
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
    const departments = @json($departments);
    const agencies = @json($agencies);
    const lgas = @json($lgas);
    const unitRoleIds = Object.keys(@json($unitRoles)).map(id => String(id));

    const roleSelect = document.getElementById('role_id');
    const unitSection = document.getElementById('administrative_unit_section');
    const unitTypeSelect = document.getElementById('administrative_type');
    const unitIdSelect = document.getElementById('administrative_id');
    const form = document.getElementById('createUserForm');

    // Event Listeners
    roleSelect.addEventListener('change', toggleUnitSection);
    unitTypeSelect.addEventListener('change', updateUnits);
    
    // Add change listener to administrative_id to log selection
    unitIdSelect.addEventListener('change', function() {
        console.log('Administrative Unit Selected:', {
            value: unitIdSelect.value,
            text: unitIdSelect.options[unitIdSelect.selectedIndex]?.text,
            disabled: unitIdSelect.disabled
        });
    });
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleUnitSection();
        if (unitTypeSelect.value) {
            updateUnits();
        }
    });

    // Add form submission validation with more logging
    form.addEventListener('submit', function(e) {
        console.log('=== FORM SUBMISSION TRIGGERED ===');
        
        const selectedRoleId = String(roleSelect.value);
        console.log('Selected Role ID:', selectedRoleId);
        console.log('Is Unit Role?', unitRoleIds.includes(selectedRoleId));
        
        if (unitRoleIds.includes(selectedRoleId)) {
            // For unit-based roles, ensure fields are filled
            console.log('Administrative Type Value:', unitTypeSelect.value);
            console.log('Administrative ID Value:', unitIdSelect.value);
            console.log('Administrative Type Disabled?', unitTypeSelect.disabled);
            console.log('Administrative ID Disabled?', unitIdSelect.disabled);
            
            if (!unitTypeSelect.value || !unitIdSelect.value) {
                e.preventDefault();
                console.error('❌ VALIDATION FAILED: Missing administrative fields');
                alert('Please select both Administrative Unit Type and Administrative Unit for this role.');
                return false;
            }
            
            // Log what we're about to submit
            console.log('✅ SUBMITTING FORM WITH:', {
                role_id: roleSelect.value,
                administrative_type: unitTypeSelect.value,
                administrative_id: unitIdSelect.value,
                administrative_id_text: unitIdSelect.options[unitIdSelect.selectedIndex]?.text
            });
        } else {
            // For global roles, ensure administrative fields are cleared/disabled
            console.log('✅ SUBMITTING FORM FOR GLOBAL ROLE (no administrative unit)');
        }
        
        console.log('=== FORM WILL NOW SUBMIT ===');
    });

    function toggleUnitSection() {
        const selectedRoleId = String(roleSelect.value);

        if (unitRoleIds.includes(selectedRoleId)) {
            // Show section and enable fields
            unitSection.style.display = 'block';
            unitTypeSelect.disabled = false;
            unitIdSelect.disabled = false;
            unitTypeSelect.required = true;
            unitIdSelect.required = true;
            
            // If there's an old value, restore it
            const oldType = '{{ old("administrative_type") }}';
            if (oldType && !unitTypeSelect.value) {
                unitTypeSelect.value = oldType;
                updateUnits();
            }
        } else {
            // Hide section and disable fields (disabled fields aren't submitted)
            unitSection.style.display = 'none';
            unitTypeSelect.disabled = true;
            unitIdSelect.disabled = true;
            unitTypeSelect.required = false;
            unitIdSelect.required = false;
            
            // Clear values when disabled
            unitTypeSelect.value = '';
            unitIdSelect.innerHTML = '<option value="">-- Select Unit --</option>';
        }
    }

    function updateUnits() {
        const type = unitTypeSelect.value;
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

        // Restore old value if exists
        const oldUnitId = '{{ old('administrative_id') }}'; 
        if (oldUnitId) {
            unitIdSelect.value = oldUnitId;
        }
        
        console.log(`Loaded ${units.length} units for type: ${type}`);
    }
</script>
@endpush