@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Enrollment Agents</h4>
            <div class="page-title-right">
                <a href="{{ route('lga_admin.agents.create') }}" class="btn btn-primary">
                    <i class="ri-add-line align-middle me-1"></i> Create New Agent
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">All Enrollment Agents - {{ auth()->user()->administrativeUnit->name ?? 'N/A' }}</h4>
                
                @if($agents->isEmpty())
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        No enrollment agents found. Click "Create New Agent" to add one.
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="agents-table" class="table table-bordered dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agents as $index => $agent)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                {{ $agent->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $agent->email }}</td>
                                    <td>{{ $agent->phone_number ?? 'N/A' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $agent->status }}">
                                            {{ ucfirst($agent->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $agent->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('lga_admin.agents.edit', $agent) }}" 
                                               class="btn btn-sm btn-soft-primary edit-btn" 
                                               title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            
                                            <form action="{{ route('lga_admin.agents.destroy', $agent) }}" 
                                                  method="POST" 
                                                  class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-sm btn-soft-danger delete-btn delete-button" 
                                                        title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Agents</p>
                        <h4 class="mb-2">{{ $agents->count() }}</h4>
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
                        <p class="text-truncate font-size-14 mb-2">Active Agents</p>
                        <h4 class="mb-2">{{ $agents->where('status', 'onboarded')->count() }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-user-smile-line font-size-24"></i>
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
                        <p class="text-truncate font-size-14 mb-2">Pending</p>
                        <h4 class="mb-2">{{ $agents->where('status', 'pending')->count() }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-time-line font-size-24"></i>
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
                        <p class="text-truncate font-size-14 mb-2">Rejected</p>
                        <h4 class="mb-2">{{ $agents->where('status', 'rejected')->count() }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-danger rounded-3">
                            <i class="ri-close-circle-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#agents-table').DataTable({
            "order": [[5, "desc"]], // Sort by created date
            "pageLength": 25,
            "responsive": true,
            "dom": 'Bfrtip',
            "buttons": ['copy', 'excel', 'pdf', 'print']
        });
        
        // Delete confirmation
        $('.delete-button').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This enrollment agent account will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush