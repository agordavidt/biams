@extends('layouts.admin')

@section('content')

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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Resources List</h4>
                    <div>
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary me-2">
                            <i class="ri-building-line align-middle me-1"></i> Manage Partners
                        </a>
                        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                            <i class="ri-add-line align-middle me-1"></i> Create New Resource
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
                    <table id="resources-table" class="table table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Partner Organization</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Target Practice</th>
                                <th>Validity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resources as $resource)
                            <tr>
                                <td>{{ $resource->name }}</td>
                                <td>
                                    @if($resource->partner_id)
                                        <a href="{{ route('admin.partners.show', $resource->partner_id) }}">
                                            {{ $resource->partner->legal_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Ministry of Agriculture</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($resource->description, 50) }}</td>
                                <td>
                                    @if($resource->requires_payment)
                                        <span>₦{{ number_format($resource->price, 2) }}</span>
                                    @else
                                        <span >Free</span>
                                    @endif
                                </td>
                                <td>{{ Str::title(str_replace('-', ' ', $resource->target_practice)) }}</td>
                                <td>
                                    @if($resource->start_date && $resource->start_date->isFuture())
                                        <span >Not Started</span>
                                    @elseif($resource->end_date)
                                        <span>{{ $resource->end_date->diffInDays(now()) }} days </span>
                                    @else
                                        <span >No Expiry</span>
                                    @endif
                                </td>
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