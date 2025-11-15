@extends('layouts.super_admin')
@section('content')
<div class="container-fluid">
    {{-- Header – Back + Title + Cancel --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <a href="{{ route('super_admin.management.users.index') }}"
                       class="btn btn-outline-secondary me-3">
                        <i class="ri-arrow-left-line"></i>
                    </a>
                    <div>
                        <h4 class="mb-1">Create New User</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('super_admin.management.users.index') }}">Users</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <a href="{{ route('super_admin.management.users.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('super_admin.management.users.store') }}" id="createUserForm">
                        @csrf
                        <div class="row g-4">
                            {{-- ==== LEFT COLUMN ==== --}}
                            <div class="col-md-6">
                                <h5 class="mb-3">Basic Information</h5>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation"
                                           id="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            {{-- ==== RIGHT COLUMN ==== --}}
                            <div class="col-md-6">
                                <h5 class="mb-3">Role & Unit Assignment</h5>

                                <div class="mb-3">
                                    <label for="role_id" class="form-label">Assign Role <span class="text-danger">*</span></label>
                                    <select name="role_id" id="role_id"
                                            class="form-select @error('role_id') is-invalid @enderror" required>
                                        <option value="">-- Select Role --</option>
                                        @foreach($roles as $id => $name)
                                            <option value="{{ $id }}" {{ old('role_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div id="administrative_unit_section" style="display:none;">
                                    <div class="alert alert-info small mb-3">
                                        <i class="ri-information-line me-1"></i>
                                        This role requires an administrative unit.
                                    </div>

                                    <div class="mb-3">
                                        <label for="administrative_type" class="form-label">
                                            Unit Type <span class="text-danger" id="type_required">*</span>
                                        </label>
                                        <select id="administrative_type" name="administrative_type"
                                                class="form-select @error('administrative_type') is-invalid @enderror">
                                            <option value="">-- Select Type --</option>
                                            <option value="Department" {{ old('administrative_type')=='Department'?'selected':'' }}>Department</option>
                                            <option value="Agency" {{ old('administrative_type')=='Agency'?'selected':'' }}>Agency</option>
                                            <option value="LGA" {{ old('administrative_type')=='LGA'?'selected':'' }}>LGA</option>
                                        </select>
                                        @error('administrative_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="administrative_id" class="form-label">
                                            Unit <span class="text-danger" id="unit_required">*</span>
                                        </label>
                                        <select id="administrative_id" name="administrative_id"
                                                class="form-select @error('administrative_id') is-invalid @enderror">
                                            <option value="">-- Select Unit --</option>
                                        </select>
                                        @error('administrative_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                 Create User
                            </button>
                            <a href="{{ route('super_admin.management.users.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ==== Data (unchanged) ====
    const departments = @json($departments);
    const agencies    = @json($agencies);
    const lgas        = @json($lgas);
    const unitRoleIds = Object.keys(@json($unitRoles)).map(id => String(id));

    // ==== Elements ====
    const roleSelect      = document.getElementById('role_id');
    const unitSection     = document.getElementById('administrative_unit_section');
    const typeSelect      = document.getElementById('administrative_type');
    const unitSelect      = document.getElementById('administrative_id');
    const form            = document.getElementById('createUserForm');

    // ==== Helpers ====
    const toggleUnitSection = () => {
        const needUnit = unitRoleIds.includes(String(roleSelect.value));
        unitSection.style.display = needUnit ? 'block' : 'none';
        typeSelect.disabled = !needUnit;
        unitSelect.disabled = !needUnit;
        typeSelect.required = needUnit;
        unitSelect.required = needUnit;

        if (!needUnit) {
            typeSelect.value = '';
            unitSelect.innerHTML = '<option value="">-- Select Unit --</option>';
        } else if (typeSelect.value) {
            updateUnits();
        }
    };

    const updateUnits = () => {
        const type = typeSelect.value;
        unitSelect.innerHTML = '<option value="">-- Select Unit --</option>';

        const list = type === 'Department' ? departments
                   : type === 'Agency'     ? agencies
                   : type === 'LGA'        ? lgas
                   : [];

        list.forEach(u => {
            const opt = new Option(u.name, u.id);
            unitSelect.appendChild(opt);
        });

        const old = '{{ old("administrative_id") }}';
        if (old) unitSelect.value = old;
    };

    // ==== Listeners ====
    roleSelect.addEventListener('change', toggleUnitSection);
    typeSelect.addEventListener('change', updateUnits);

    form.addEventListener('submit', e => {
        if (unitRoleIds.includes(String(roleSelect.value)) && (!typeSelect.value || !unitSelect.value)) {
            e.preventDefault();
            alert('Please select both Administrative Unit Type and Unit.');
        }
    });

    // ==== Init ====
    document.addEventListener('DOMContentLoaded', () => {
        toggleUnitSection();
        if (typeSelect.value) updateUnits();
    });
</script>
@endpush