@extends('layouts.super_admin')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Department Management</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                            Add Department
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
                            <table id="departmentsTable" class="table table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Abbreviation</th>
                                        <th>Users Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $department)
                                        <tr>
                                            <td>{{ $department->name }}</td>
                                            <td>{{ $department->abbreviation ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $department->users_count }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                            onclick="editDepartment({{ $department->id }}, '{{ $department->name }}', '{{ $department->abbreviation }}')" 
                                                            title="Edit">
                                                        <i class="ri-edit-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="deleteDepartment({{ $department->id }})" title="Delete">
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

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDepartmentModalLabel">Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDepartmentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="add_abbreviation" class="form-label">Abbreviation</label>
                        <input type="text" class="form-control" id="add_abbreviation" name="abbreviation">
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

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDepartmentForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_department_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_abbreviation" class="form-label">Abbreviation</label>
                        <input type="text" class="form-control" id="edit_abbreviation" name="abbreviation">
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
        $('#departmentsTable').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 25
        });

        // Add Department Form Submit
        $('#addDepartmentForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route('super_admin.management.departments.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#addDepartmentModal').modal('hide');
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

        // Edit Department Form Submit
        $('#editDepartmentForm').on('submit', function(e) {
            e.preventDefault();
            const departmentId = $('#edit_department_id').val();
            
            $.ajax({
                url: `/super-admin/management/departments/${departmentId}`,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    $('#editDepartmentModal').modal('hide');
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

    function editDepartment(id, name, abbreviation) {
        $('#edit_department_id').val(id);
        $('#edit_name').val(name);
        $('#edit_abbreviation').val(abbreviation || '');
        $('#editDepartmentModal').modal('show');
    }

    function deleteDepartment(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! Departments with assigned users cannot be deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/super-admin/management/departments/${id}`,
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