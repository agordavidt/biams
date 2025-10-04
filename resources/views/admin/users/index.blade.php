@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">System Staff Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">System Staff</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Staff</p>
                        <h4 class="mb-0">{{ number_format($stats['totalStaff']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success"><i class="ri-arrow-up-line align-middle"></i> {{ $stats['recentStaff'] }}</span>
                            <span class="ms-1 text-muted font-size-12">added this month</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-team-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">LGA Admins</p>
                        <h4 class="mb-0">{{ number_format($stats['lgaAdmins']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">Across {{ $stats['lgasCovered'] }} LGAs</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-user-settings-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Enrollment Agents</p>
                        <h4 class="mb-0">{{ number_format($stats['enrollmentAgents']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">{{ $stats['activeStaff'] }} Active</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-user-add-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Registered Farmers</p>
                        <h4 class="mb-0">{{ number_format($stats['totalFarmers']) }}</h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">Platform users</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-user-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="row g-3">
                    <div class="col-md-3">
                        <h5 class="card-title mb-0">Filter Staff</h5>
                    </div>
                    <div class="col-md-9">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search name, email, phone..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="role" class="form-select">
                                    <option value="">All Roles</option>
                                    <option value="LGA Admin" {{ request('role') == 'LGA Admin' ? 'selected' : '' }}>LGA Admin</option>
                                    <option value="Enrollment Agent" {{ request('role') == 'Enrollment Agent' ? 'selected' : '' }}>Enrollment Agent</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="administrative_type" class="form-select" id="adminTypeSelect">
                                    <option value="">Unit Type</option>
                                    <option value="App\Models\LGA" {{ request('administrative_type') == 'App\Models\LGA' ? 'selected' : '' }}>LGA</option>
                                    <option value="App\Models\Department" {{ request('administrative_type') == 'App\Models\Department' ? 'selected' : '' }}>Department</option>
                                    <option value="App\Models\Agency" {{ request('administrative_type') == 'App\Models\Agency' ? 'selected' : '' }}>Agency</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="administrative_id" class="form-select" id="adminIdSelect">
                                    <option value="">Select Unit</option>
                                    @if(request('administrative_type') == 'App\Models\LGA')
                                        @foreach($lgas as $lga)
                                            <option value="{{ $lga->id }}" {{ request('administrative_id') == $lga->id ? 'selected' : '' }}>
                                                {{ $lga->name }}
                                            </option>
                                        @endforeach
                                    @elseif(request('administrative_type') == 'App\Models\Department')
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ request('administrative_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    @elseif(request('administrative_type') == 'App\Models\Agency')
                                        @foreach($agencies as $agency)
                                            <option value="{{ $agency->id }}" {{ request('administrative_id') == $agency->id ? 'selected' : '' }}>
                                                {{ $agency->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="onboarded" {{ request('status') == 'onboarded' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                            @if(request()->hasAny(['search', 'role', 'administrative_type', 'status']))
                            <div class="col-md-12">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-soft-secondary btn-sm">
                                    <i class="ri-close-line"></i> Clear Filters
                                </a>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Staff Overview ({{ $users->total() }} {{ Str::plural('member', $users->total()) }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 50px;">#</th>
                                <th scope="col">Staff Member</th>
                                <th scope="col">Contact Information</th>
                                <th scope="col">Role</th>
                                <th scope="col">Administrative Unit</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date Added</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-6">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <i class="ri-mail-line text-muted me-1"></i>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                    @if($user->phone_number)
                                    <div class="mt-1">
                                        <i class="ri-phone-line text-muted me-1"></i>
                                        <span class="text-muted">{{ $user->phone_number }}</span>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($user->hasRole('LGA Admin'))
                                        <span class="badge badge-soft-primary">
                                            <i class="ri-user-settings-line align-middle"></i> LGA Admin
                                        </span>
                                    @elseif($user->hasRole('Enrollment Agent'))
                                        <span class="badge badge-soft-success">
                                            <i class="ri-user-add-line align-middle"></i> Enrollment Agent
                                        </span>
                                    @else
                                        <span class="badge badge-soft-secondary">Staff</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->administrativeUnit)
                                        <div>
                                            <strong>{{ $user->administrativeUnit->name }}</strong>
                                        </div>
                                        <small class="text-muted">
                                            <i class="ri-building-line"></i>
                                            {{ class_basename($user->administrative_type) }}
                                        </small>
                                    @else
                                        <span class="text-muted">
                                            <i class="ri-error-warning-line"></i> Not assigned
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status == 'onboarded')
                                        <span class="badge badge-soft-success">
                                            <i class="ri-checkbox-circle-line align-middle"></i> Active
                                        </span>
                                    @elseif($user->status == 'pending')
                                        <span class="badge badge-soft-warning">
                                            <i class="ri-time-line align-middle"></i> Pending
                                        </span>
                                    @else
                                        <span class="badge badge-soft-secondary">{{ ucfirst($user->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $user->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="btn btn-sm btn-soft-info"
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-user-search-line fs-1 d-block mb-2"></i>
                                        <p class="mb-0">No staff members found</p>
                                        @if(request()->hasAny(['search', 'role', 'administrative_type', 'status']))
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-link">Clear filters</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- LGA Distribution -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-map-pin-line text-primary me-1"></i>
                    Staff Distribution by LGA
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>LGA Name</th>
                                <th class="text-center" style="width: 150px;">LGA Admins</th>
                                <th class="text-center" style="width: 150px;">Enrollment Agents</th>
                                <th class="text-center" style="width: 120px;">Total Staff</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['lgaDistribution'] as $lga)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $lga->name }}</strong>
                                    @if($lga->code)
                                        <br><small class="text-muted">Code: {{ $lga->code }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($lga->lga_admins_count > 0)
                                        <span class="badge badge-soft-primary fs-6">{{ $lga->lga_admins_count }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($lga->agents_count > 0)
                                        <span class="badge badge-soft-success fs-6">{{ $lga->agents_count }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <strong class="text-primary">{{ $lga->total_staff_count }}</strong>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">
                                    No LGA staff assignments yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($stats['lgaDistribution']->isNotEmpty())
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th class="text-center">{{ $stats['lgaDistribution']->sum('lga_admins_count') }}</th>
                                <th class="text-center">{{ $stats['lgaDistribution']->sum('agents_count') }}</th>
                                <th class="text-center">{{ $stats['lgaDistribution']->sum('total_staff_count') }}</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Department Distribution -->
@if($stats['deptDistribution']->isNotEmpty())
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-building-line text-success me-1"></i>
                    Staff Distribution by Department
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Department Name</th>
                                <th class="text-center" style="width: 150px;">Staff Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['deptDistribution'] as $dept)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $dept->name }}</strong>
                                    @if($dept->abbreviation)
                                        <br><small class="text-muted">{{ $dept->abbreviation }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <strong class="text-success">{{ $dept->staff_count }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th class="text-center">{{ $stats['deptDistribution']->sum('staff_count') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
// Dynamic filter for administrative units
document.getElementById('adminTypeSelect').addEventListener('change', function() {
    const adminIdSelect = document.getElementById('adminIdSelect');
    const selectedType = this.value;
    
    // Clear existing options
    adminIdSelect.innerHTML = '<option value="">Select Unit</option>';
    
    if (selectedType) {
        const lgas = @json($lgas);
        const departments = @json($departments);
        const agencies = @json($agencies);
        
        let units = [];
        if (selectedType === 'App\\Models\\LGA') {
            units = lgas;
        } else if (selectedType === 'App\\Models\\Department') {
            units = departments;
        } else if (selectedType === 'App\\Models\\Agency') {
            units = agencies;
        }
        
        units.forEach(unit => {
            const option = document.createElement('option');
            option.value = unit.id;
            option.textContent = unit.name;
            adminIdSelect.appendChild(option);
        });
    }
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection