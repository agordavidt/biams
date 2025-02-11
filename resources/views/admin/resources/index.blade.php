@extends('layouts.admin')

@section('content')

   <!-- start page title -->
   <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Manage Resources</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resources</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title"></h4>
                    <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                        <i class="ri-add-line align-middle me-1"></i> Create New Resource
                    </a>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table id="resources-table" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Target Practice</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resources as $resource)
                            <tr>
                                <td>{{ $resource->name }}</td>
                                <td>{{ Str::limit($resource->description, 50) }}</td>
                                <td>
                                    @if($resource->requires_payment)
                                        <span class="badge bg-success">₦{{ number_format($resource->price, 2) }}</span>
                                    @else
                                        <span class="badge bg-info">Free</span>
                                    @endif
                                </td>
                                <td>{{ Str::title(str_replace('-', ' ', $resource->target_practice)) }}</td>
                                <td>
                                    <a href="{{ route('admin.resources.edit', $resource) }}" 
                                       class="btn btn-sm btn-info me-2">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <form action="{{ route('admin.resources.destroy', $resource) }}" 
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
        $('#resources-table').DataTable();

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
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