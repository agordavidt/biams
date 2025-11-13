@extends('layouts.super_admin')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Agency Management</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAgencyModal">
                            Add Agency
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="agenciesTable" class="table table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Users Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agencies as $agency)
                                        <tr>
                                            <td>{{ $agency->name }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $agency->department->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $agency->users_count }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                            onclick="editAgency({{ $agency->id }}, '{{ $agency->name }}', {{ $agency->department_id }})" 
                                                            title="Edit">
                                                        <i class="ri-edit-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="deleteAgency({{ $agency->id }})" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
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
    </div>
</div>

<!-- Add Agency Modal -->
<div class="modal fade" id="addAgencyModal" tabindex="-1" aria-labelledby="addAgencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAgencyModalLabel">Add New Agency</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAgencyForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Agency Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="add_department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="add_department_id" name="department_id" required>
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                         Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Agency Modal -->
<div class="modal fade" id="editAgencyModal" tabindex="-1" aria-labelledby="editAgencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAgencyModalLabel">Edit Agency</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAgencyForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_agency_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Agency Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_department_id" name="department_id" required>
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#agenciesTable').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 25
        });

        // Add Agency Form Submit
        $('#addAgencyForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route('super_admin.management.agencies.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#addAgencyModal').modal('hide');
                    Swal.fire('Success!', response.message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            $(`#add_${key}`).addClass('is-invalid');
                            $(`#add_${key}`).next('.invalid-feedback').text(errors[key][0]);
                        });
                    } else {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                }
            });
        });

        // Edit Agency Form Submit
        $('#editAgencyForm').on('submit', function(e) {
            e.preventDefault();
            const agencyId = $('#edit_agency_id').val();
            
            $.ajax({
                url: `/super-admin/management/agencies/${agencyId}`,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editAgencyModal').modal('hide');
                    Swal.fire('Success!', response.message, 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            $(`#edit_${key}`).addClass('is-invalid');
                            $(`#edit_${key}`).next('.invalid-feedback').text(errors[key][0]);
                        });
                    } else {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                }
            });
        });

        // Reset form validation on modal close
        $('.modal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').text('');
        });
    });

    function editAgency(id, name, departmentId) {
        $('#edit_agency_id').val(id);
        $('#edit_name').val(name);
        $('#edit_department_id').val(departmentId);
        $('#editAgencyModal').modal('show');
    }

    function deleteAgency(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! Agencies with assigned users cannot be deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/super-admin/management/agencies/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush