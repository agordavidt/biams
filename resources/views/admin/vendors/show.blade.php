@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Vendor Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">{{ $vendor->legal_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Header -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-lg">
                                <span class="avatar-title bg-primary rounded-circle font-size-24">
                                    <i class="ri-building-line"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $vendor->legal_name }}</h4>
                            <p class="text-muted mb-0">
                                {{ ucwords(str_replace('_', ' ', $vendor->organization_type)) }}
                                @if($vendor->registration_number)
                                    | Reg: {{ $vendor->registration_number }}
                                @endif
                            </p>
                            <p class="text-muted mb-0">
                                <small>Registered: {{ $vendor->created_at->format('M d, Y') }} by {{ $vendor->registeredBy->name ?? 'System' }}</small>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="badge badge-soft-{{ $vendor->is_active ? 'success' : 'danger' }} mb-2">
                            {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <div class="btn-group">
                            <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            <form action="{{ route('admin.vendors.toggle-status', $vendor) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-{{ $vendor->is_active ? 'warning' : 'success' }}">                                    
                                    {{ $vendor->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Users</p>
                        <h4 class="mb-2">{{ $stats['total_users'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-team-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Vendor Managers</p>
                        <h4 class="mb-2">{{ $stats['vendor_managers'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-user-star-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Distribution Agents</p>
                        <h4 class="mb-2">{{ $stats['distribution_agents'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-info rounded-3">
                            <i class="ri-truck-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Active Resources</p>
                        <h4 class="mb-2">{{ $stats['active_resources'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-box-3-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Company Information -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Company Information</h4>
                
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 30%;">Legal Name:</th>
                                <td>{{ $vendor->legal_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Registration Number:</th>
                                <td>{{ $vendor->registration_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Organization Type:</th>
                                <td>{{ ucwords(str_replace('_', ' ', $vendor->organization_type)) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Establishment Date:</th>
                                <td>{{ $vendor->establishment_date?->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Address:</th>
                                <td>{{ $vendor->address }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Website:</th>
                                <td>
                                    @if($vendor->website)
                                        <a href="{{ $vendor->website }}" target="_blank">{{ $vendor->website }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Tax ID:</th>
                                <td>{{ $vendor->tax_identification_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Description:</th>
                                <td>{{ $vendor->description }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Focus Areas:</th>
                                <td>
                                    @foreach($vendor->focus_areas as $area)
                                        <span class="badge badge-soft-primary me-1">
                                            {{ $vendor->getFocusAreaOptions()[$area] ?? $area }}
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contact Person -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Contact Person Information</h4>
                
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 30%;">Name:</th>
                                <td>{{ $vendor->contact_person_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Title:</th>
                                <td>{{ $vendor->contact_person_title ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Phone:</th>
                                <td>{{ $vendor->contact_person_phone }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Email:</th>
                                <td>{{ $vendor->contact_person_email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Team Members</h4>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendor->users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-soft-primary">
                                        {{ $user->roles->first()->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-soft-{{ $user->status == 'onboarded' ? 'success' : 'warning' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No team members yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Bank Name:</th>
                                <td>{{ $vendor->bank_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Account Name:</th>
                                <td>{{ $vendor->bank_account_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Account Number:</th>
                                <td>{{ $vendor->bank_account_number ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Registration Certificate -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Registration Certificate</h4>
                
                @if($vendor->registration_certificate)
                    <a href="{{ Storage::url($vendor->registration_certificate) }}" 
                       target="_blank" 
                       class="btn btn-primary btn-block w-100">
                         View Certificate
                    </a>
                @else
                    <p class="text-muted mb-0">No certificate uploaded</p>
                @endif
            </div>
        </div>

        <!-- Resources Summary -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Resources Summary</h4>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Resources</span>
                        <strong>{{ $stats['total_resources'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Proposed</span>
                        <span class="badge badge-soft-warning">{{ $stats['proposed_resources'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Active</span>
                        <span class="badge badge-soft-success">{{ $stats['active_resources'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection