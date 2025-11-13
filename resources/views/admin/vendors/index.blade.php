@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Vendor Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vendors</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">All Registered Vendors</h4>
                    <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                       Register New Vendor
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="vendorsTable" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Legal Name</th>
                                <th>Organization Type</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Team Members</th>
                                <th>Resources</th>
                                <th>Status</th>
                                <th>Registered By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $vendor)
                            <tr>
                                <td>
                                    <strong>{{ $vendor->legal_name }}</strong>
                                    @if($vendor->registration_number)
                                    <br><small class="text-muted">Reg: {{ $vendor->registration_number }}</small>
                                    @endif
                                </td>
                                <td>{{ ucwords(str_replace('_', ' ', $vendor->organization_type)) }}</td>
                                <td>{{ $vendor->contact_person_name }}</td>
                                <td>{{ $vendor->contact_person_phone }}</td>
                                <td>{{ $vendor->contact_person_email }}</td>
                                <td>
                                    <span class="badge badge-soft-primary">{{ $vendor->users_count }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-soft-info">{{ $vendor->resources_count }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-soft-{{ $vendor->is_active ? 'success' : 'danger' }}">
                                        {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $vendor->registeredBy->name ?? 'System' }}</small>
                                    <br>
                                    <small class="text-muted">{{ $vendor->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.vendors.show', $vendor) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Details">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('admin.vendors.edit', $vendor) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.vendors.toggle-status', $vendor) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-{{ $vendor->is_active ? 'warning' : 'success' }}" 
                                                    title="{{ $vendor->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="ri-toggle-{{ $vendor->is_active ? 'line' : 'fill' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">No vendors registered yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#vendorsTable').DataTable({
            responsive: true,
            order: [[8, 'desc']]
        });
    });
</script>
@endpush