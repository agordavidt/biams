@extends('layouts.super_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">{{ $vendor->legal_name }}</h4>
            <div>
                <a href="{{ route('super_admin.vendors.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Information -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Vendor Information</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Legal Name:</strong> {{ $vendor->legal_name }}</p>
                        <p class="mb-2"><strong>Registration Number:</strong> {{ $vendor->registration_number ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Organization Type:</strong> {{ ucfirst(str_replace('_', ' ', $vendor->organization_type)) }}</p>
                        <p class="mb-2"><strong>Establishment Date:</strong> {{ $vendor->establishment_date ? $vendor->establishment_date->format('Y-m-d') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Status:</strong> 
                            @if($vendor->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                        <p class="mb-2"><strong>Tax ID:</strong> {{ $vendor->tax_identification_number ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Website:</strong> 
                            @if($vendor->website)
                                <a href="{{ $vendor->website }}" target="_blank">{{ $vendor->website }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                <h6 class="mt-4 mb-2">Contact Information</h6>
                <p class="mb-2"><strong>Contact Person:</strong> {{ $vendor->contact_person_name }}</p>
                <p class="mb-2"><strong>Title:</strong> {{ $vendor->contact_person_title ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Phone:</strong> {{ $vendor->contact_person_phone }}</p>
                <p class="mb-2"><strong>Email:</strong> {{ $vendor->contact_person_email }}</p>
                <p class="mb-2"><strong>Address:</strong> {{ $vendor->address }}</p>

                <h6 class="mt-4 mb-2">Description</h6>
                <p>{{ $vendor->description }}</p>

                @if($vendor->focus_areas)
                <h6 class="mt-4 mb-2">Focus Areas</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($vendor->focus_areas as $area)
                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $area)) }}</span>
                    @endforeach
                </div>
                @endif

                @if($vendor->bank_name)
                <h6 class="mt-4 mb-2">Banking Information</h6>
                <p class="mb-2"><strong>Bank Name:</strong> {{ $vendor->bank_name }}</p>
                <p class="mb-2"><strong>Account Name:</strong> {{ $vendor->bank_account_name }}</p>
                <p class="mb-2"><strong>Account Number:</strong> {{ $vendor->bank_account_number }}</p>
                @endif

                <div class="mt-4">
                    <p class="mb-2"><strong>Registered By:</strong> {{ $vendor->registeredBy ? $vendor->registeredBy->name : 'N/A' }}</p>
                    <p class="mb-2"><strong>Registered At:</strong> {{ $vendor->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Statistics</h5>
                
                <div class="mb-3">
                    <p class="text-muted mb-1">Total Users</p>
                    <h4>{{ $stats['total_users'] }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Vendor Managers</p>
                    <h4>{{ $stats['vendor_managers'] }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Distribution Agents</p>
                    <h4>{{ $stats['distribution_agents'] }}</h4>
                </div>

                <hr>

                <div class="mb-3">
                    <p class="text-muted mb-1">Total Resources</p>
                    <h4>{{ $stats['total_resources'] }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Active Resources</p>
                    <h4>{{ $stats['active_resources'] }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Under Review</p>
                    <h4>{{ $stats['under_review_resources'] }}</h4>
                </div>

                <div class="mb-3">
                    <p class="text-muted mb-1">Proposed</p>
                    <h4>{{ $stats['proposed_resources'] }}</h4>
                </div>

                <hr>

                <div class="mb-3">
                    <p class="text-muted mb-1">Total Applications</p>
                    <h4>{{ $stats['total_applications'] }}</h4>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Actions</h5>
                
                <form action="{{ route('super_admin.vendors.toggle-status', $vendor) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning w-100">
                        {{ $vendor->is_active ? 'Deactivate Vendor' : 'Activate Vendor' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Users -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Vendor Users</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendor->users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->status === 'onboarded' ? 'success' : 'warning' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No users found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Resources -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Vendor Resources</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Price</th>
                                <th>Applications</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resources as $resource)
                            <tr>
                                <td>
                                    <a href="{{ route('super_admin.resources.show', $resource) }}">
                                        {{ $resource->name }}
                                    </a>
                                </td>
                                <td>{{ ucfirst($resource->type) }}</td>
                                <td>
                                    <span class="badge bg-{{ $resource->status === 'active' ? 'success' : ($resource->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($resource->status) }}
                                    </span>
                                </td>
                                <td>{{ $resource->requires_payment ? 'Required' : 'Free' }}</td>
                                <td>{{ $resource->price ? '₦' . number_format($resource->price, 2) : 'Free' }}</td>
                                <td>{{ $resource->applications_count }}</td>
                                <td>{{ $resource->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No resources found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $resources->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection