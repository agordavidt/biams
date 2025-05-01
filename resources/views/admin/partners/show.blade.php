@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Partner Details</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.partners.index') }}">Partners</a></li>
                    <li class="breadcrumb-item active">{{ $partner->legal_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-3">Organization Information</h5>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="dropdown">
                            <a class="text-body dropdown-toggle font-size-16" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                <i class="ri-more-fill"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('admin.partners.edit', $partner) }}">
                                    <i class="ri-edit-box-line me-1 align-middle"></i> Edit
                                </a>
                                <a class="dropdown-item delete-partner" href="#" data-partner-id="{{ $partner->id }}">
                                    <i class="ri-delete-bin-line me-1 align-middle"></i> Delete
                                </a>
                                <form id="delete-form-{{ $partner->id }}" action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg me-3">
                            <span class="avatar-title bg-soft-primary text-primary rounded-circle font-size-24">
                                {{ strtoupper(substr($partner->legal_name, 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="font-size-16 mb-1">{{ $partner->legal_name }}</h5>
                        <p class="text-muted mb-1">{{ Str::title(str_replace('_', ' ', $partner->organization_type)) }}</p>
                        <div>
                            @if($partner->is_active)
                                <span class="badge badge-soft-success">Active</span>
                            @else
                                <span class="badge badge-soft-danger">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="py-3">
                    <h5 class="font-size-15">About:</h5>
                    <p class="text-muted mb-0">{{ $partner->description }}</p>
                </div>

                <div class="pt-1">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5 class="font-size-15">Registration:</h5>
                            <p class="text-muted mb-0">{{ $partner->registration_number ?: 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="font-size-15">Established:</h5>
                            <p class="text-muted mb-0">{{ $partner->establishment_date ? $partner->establishment_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h5 class="font-size-15">Address:</h5>
                        <p class="text-muted mb-0">{{ $partner->address }}</p>
                    </div>

                    <div class="mt-3">
                        <h5 class="font-size-15">Website:</h5>
                        <p class="text-muted mb-0">
                            @if($partner->website)
                                <a href="{{ $partner->website }}" target="_blank">{{ $partner->website }}</a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    @if($partner->registration_certificate)
                    <div class="mt-3">
                        <h5 class="font-size-15">Registration Certificate:</h5>
                        <p class="text-muted mb-0">
                            <a href="{{ Storage::url($partner->registration_certificate) }}" target="_blank" class="btn btn-sm btn-soft-primary">
                                <i class="ri-file-text-line me-1"></i> View Document
                            </a>
                        </p>
                    </div>
                    @endif
                </div>

                <div class="mt-4">
                    <h5 class="font-size-15">Focus Areas:</h5>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($partner->focus_areas as $area)
                            <span class="badge badge-soft-primary">{{ Str::title(str_replace('_', ' ', $area)) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Contact Information</h5>
                <div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-user-3-line font-size-18 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="font-size-15">{{ $partner->contact_person_name }}</h5>
                            <p class="text-muted mb-0">{{ $partner->contact_person_title ?: 'Not specified' }}</p>
                        </div>
                    </div>

                    <div class="d-flex mt-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-phone-line font-size-18 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="font-size-15">Phone</h5>
                            <p class="text-muted mb-0">{{ $partner->contact_person_phone }}</p>
                        </div>
                    </div>

                    <div class="d-flex mt-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-mail-line font-size-18 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="font-size-15">Email</h5>
                            <p class="text-muted mb-0">{{ $partner->contact_person_email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Financial Information</h5>
                <div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-bank-card-line font-size-18 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="font-size-15">Bank Details</h5>
                            <p class="text-muted mb-1">{{ $partner->bank_name ?: 'Bank Name: Not provided' }}</p>
                            <p class="text-muted mb-1">{{ $partner->bank_account_name ?: 'Account Name: Not provided' }}</p>
                            <p class="text-muted mb-0">{{ $partner->bank_account_number ?: 'Account Number: Not provided' }}</p>
                        </div>
                    </div>

                    <div class="d-flex mt-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="ri-file-text-line font-size-18 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="font-size-15">Tax ID</h5>
                            <p class="text-muted mb-0">{{ $partner->tax_identification_number ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <h5 class="card-title flex-grow-1">{{ $partner->legal_name }}'s Resources</h5>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line align-middle me-1"></i> Add New Resource
                        </a>
                    </div>
                </div>

                @if($partner->resources->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Target Practice</th>
                                <th scope="col">Price</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partner->resources as $resource)
                            <tr>
                                <td>{{ $resource->name }}</td>
                                <td>{{ Str::title(str_replace('-', ' ', $resource->target_practice)) }}</td>
                                <td>
                                    @if($resource->requires_payment)
                                        <span class="badge bg-success">₦{{ number_format($resource->price, 2) }}</span>
                                    @else
                                        <span class="badge bg-info">Free</span>
                                    @endif
                                </td>
                                <td>
                                    @if($resource->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.resources.edit', $resource) }}" class="btn btn-sm btn-info">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <form action="{{ route('admin.resources.destroy', $resource) }}" 
                                          method="POST" 
                                          class="d-inline delete-resource">
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
                @else
                <div class="text-center py-4">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-light rounded-circle text-primary h1">
                            <i class="ri-file-list-3-line"></i>
                        </div>
                    </div>
                    <h5>No resources found</h5>
                    <p class="text-muted">This partner organization doesn't have any resources yet.</p>
                    <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                        <i class="ri-add-line align-middle me-1"></i> Create First Resource
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Delete partner confirmation
        $('.delete-partner').on('click', function(e) {
            e.preventDefault();
            const partnerId = $(this).data('partner-id');
            
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
                    document.getElementById('delete-form-' + partnerId).submit();
                }
            });
        });
        
        // Delete resource confirmation
        $('.delete-resource').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Delete Resource?',
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