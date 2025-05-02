@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Manage Partners</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Partners</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Partner Organizations</h4>
                    <div>
                        <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary me-2">
                            <i class="ri-list-check align-middle me-1"></i> Manage Resources
                        </a>
                        <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i> Register New Partner
                        </a>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table id="partners-table" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Legal Name</th>
                                <th>Type</th>
                                <th>Contact Person</th>
                                <th>Resources</th>
                                <th>Focus Areas</th>
                                <!-- <th>Status</th> -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partners as $partner)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.partners.show', $partner) }}">
                                        {{ $partner->legal_name }}
                                    </a>
                                </td>
                                <td>{{ Str::title(str_replace('_', ' ', $partner->organization_type)) }}</td>
                                <td>
                                    <div>{{ $partner->contact_person_name }}</div>
                                    <small class="text-muted">{{ $partner->contact_person_email }}</small>
                                </td>
                                <td>
                                {{ $partner->resources_count }}
                                    <!-- <span class="badge bg-info">{{ $partner->resources_count }}</span> -->
                                </td>
                                <td>
                                    @foreach(array_slice($partner->focus_areas, 0, 2) as $area)
                                        <span class="badge bg-light text-dark">{{ Str::title(str_replace('_', ' ', $area)) }}</span>
                                    @endforeach
                                    @if(count($partner->focus_areas) > 2)
                                        <span class="badge bg-secondary">+{{ count($partner->focus_areas) - 2 }}</span>
                                    @endif
                                </td>
                                <!-- <td>
                                    @if($partner->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td> -->
                                <td>
                                    <a href="{{ route('admin.partners.show', $partner) }}" 
                                       class="btn btn-sm btn-primary me-1">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.partners.edit', $partner) }}" 
                                       class="btn btn-sm btn-info me-1">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <form action="{{ route('admin.partners.destroy', $partner) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
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
        $('#partners-table').DataTable();

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the partner and can't be undone. Any associated resources need to be reassigned first.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush