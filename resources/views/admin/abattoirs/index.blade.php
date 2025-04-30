@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Abattoirs</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAbattoirModal">Add Abattoir</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="mb-3">
                            <input type="text" name="search" class="form-control w-25 d-inline" placeholder="Search by name or LGA" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </form>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Registration #</th>
                                    <th>LGA</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($abattoirs as $abattoir)
                                    <tr>
                                        <td>{{ $abattoir->name }}</td>
                                        <td>{{ $abattoir->registration_number }}</td>
                                        <td>{{ $abattoir->lga }}</td>
                                        <td>{{ $abattoir->capacity }}</td>
                                        <td>{{ ucfirst($abattoir->status) }}</td>
                                        <td>                                           
                                        <a href="{{ route('admin.abattoirs.edit', $abattoir->id) }}" class="btn btn-sm btn-warning edit-abattoir">Edit</a>
                                            <a href="{{ route('admin.abattoirs.staff', $abattoir) }}" class="btn btn-sm btn-info">Staff</a>
                                            <a href="{{ route('admin.abattoirs.operations', $abattoir) }}" class="btn btn-sm btn-success">Operations</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $abattoirs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Abattoir Modal -->
    <div class="modal fade" id="createAbattoirModal" tabindex="-1" aria-labelledby="createAbattoirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAbattoirModalLabel">Add New Abattoir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createAbattoirForm" method="POST" action="{{ route('admin.abattoirs.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Registration Number</label>
                            <input type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number') }}">
                            @error('registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">License Number</label>
                            <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" value="{{ old('license_number') }}">
                            @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">LGA</label>
                            <select name="lga" class="form-control @error('lga') is-invalid @enderror">
                                <option value="" disabled selected>Select Local Government Area</option>
                                <option value="Agatu" {{ old('lga') == 'Agatu' ? 'selected' : '' }}>Agatu</option>
                                <option value="Apa" {{ old('lga') == 'Apa' ? 'selected' : '' }}>Apa</option>
                                <option value="Ado" {{ old('lga') == 'Ado' ? 'selected' : '' }}>Ado</option>
                                <!-- Add all other LGAs here -->
                                <option value="Buruku" {{ old('lga') == 'Buruku' ? 'selected' : '' }}>Buruku</option>
                                <option value="Gboko" {{ old('lga') == 'Gboko' ? 'selected' : '' }}>Gboko</option>
                                <option value="Guma" {{ old('lga') == 'Guma' ? 'selected' : '' }}>Guma</option>
                                <option value="Gwer East" {{ old('lga') == 'Gwer East' ? 'selected' : '' }}>Gwer East</option>
                                <option value="Gwer West" {{ old('lga') == 'Gwer West' ? 'selected' : '' }}>Gwer West</option>
                                <option value="Katsina-Ala" {{ old('lga') == 'Katsina-Ala' ? 'selected' : '' }}>Katsina-Ala</option>
                                <option value="Konshisha" {{ old('lga') == 'Konshisha' ? 'selected' : '' }}>Konshisha</option>
                                <option value="Kwande" {{ old('lga') == 'Kwande' ? 'selected' : '' }}>Kwande</option>
                                <option value="Logo" {{ old('lga') == 'Logo' ? 'selected' : '' }}>Logo</option>
                                <option value="Makurdi" {{ old('lga') == 'Makurdi' ? 'selected' : '' }}>Makurdi</option>
                                <option value="Obi" {{ old('lga') == 'Obi' ? 'selected' : '' }}>Obi</option>
                                <option value="Ogbadibo" {{ old('lga') == 'Ogbadibo' ? 'selected' : '' }}>Ogbadibo</option>
                                <option value="Ohimini" {{ old('lga') == 'Ohimini' ? 'selected' : '' }}>Ohimini</option>
                                <option value="Oju" {{ old('lga') == 'Oju' ? 'selected' : '' }}>Oju</option>
                                <option value="Okpokwu" {{ old('lga') == 'Okpokwu' ? 'selected' : '' }}>Okpokwu</option>
                                <option value="Otukpo" {{ old('lga') == 'Otukpo' ? 'selected' : '' }}>Otukpo</option>
                                <option value="Tarka" {{ old('lga') == 'Tarka' ? 'selected' : '' }}>Tarka</option>
                                <option value="Ukum" {{ old('lga') == 'Ukum' ? 'selected' : '' }}>Ukum</option>
                                <option value="Ushongo" {{ old('lga') == 'Ushongo' ? 'selected' : '' }}>Ushongo</option>
                                <option value="Vandeikya" {{ old('lga') == 'Vandeikya' ? 'selected' : '' }}>Vandeikya</option>
                            </select>
                            @error('lga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">GPS Latitude</label>
                            <input type="number" step="any" name="gps_latitude" class="form-control @error('gps_latitude') is-invalid @enderror" value="{{ old('gps_latitude') }}">
                            @error('gps_latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">GPS Longitude</label>
                            <input type="number" step="any" name="gps_longitude" class="form-control @error('gps_longitude') is-invalid @enderror" value="{{ old('gps_longitude') }}">
                            @error('gps_longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}">
                            @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Abattoir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Abattoir Modal -->
    <div class="modal fade" id="editAbattoirModal" tabindex="-1" aria-labelledby="editAbattoirModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAbattoirModalLabel">Edit Abattoir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editAbattoirForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="edit-name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Registration Number</label>
                            <input type="text" name="registration_number" class="form-control" id="edit-registration_number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">License Number</label>
                            <input type="text" name="license_number" class="form-control" id="edit-license_number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" id="edit-address">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">LGA</label>
                            <select name="lga" class="form-control" id="edit-lga">
                                <option value="Agatu">Agatu</option>
                                <option value="Apa">Apa</option>
                                <option value="Ado">Ado</option>                                
                                <option value="Buruku">Buruku</option>
                                <option value="Gboko">Gboko</option>
                                <option value="Guma">Guma</option>
                                <option value="Gwer East">Gwer East</option>
                                <option value="Gwer West">Gwer West</option>
                                <option value="Katsina-Ala">Katsina-Ala</option>
                                <option value="Konshisha">Konshisha</option>
                                <option value="Kwande">Kwande</option>
                                <option value="Logo">Logo</option>
                                <option value="Makurdi">Makurdi</option>
                                <option value="Obi">Obi</option>
                                <option value="Ogbadibo">Ogbadibo</option>
                                <option value="Ohimini">Ohimini</option>
                                <option value="Oju">Oju</option>
                                <option value="Okpokwu">Okpokwu</option>
                                <option value="Otukpo">Otukpo</option>
                                <option value="Tarka">Tarka</option>
                                <option value="Ukum">Ukum</option>
                                <option value="Ushongo">Ushongo</option>
                                <option value="Vandeikya">Vandeikya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">GPS Latitude</label>
                            <input type="number" step="any" name="gps_latitude" class="form-control" id="edit-gps_latitude">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">GPS Longitude</label>
                            <input type="number" step="any" name="gps_longitude" class="form-control" id="edit-gps_longitude">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="number" name="capacity" class="form-control" id="edit-capacity">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" id="edit-status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="edit-description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Abattoir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                dom: 'Bfrtip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
            });

            // Handle edit button clicks
            $('.edit-abattoir').click(function() {
                var abattoirId = $(this).data('id');
                var url = "{{ route('admin.abattoirs.update', ':id') }}".replace(':id', abattoirId);
                
                // Populate the form with data
                $('#edit-name').val($(this).data('name'));
                $('#edit-registration_number').val($(this).data('registration_number'));
                $('#edit-license_number').val($(this).data('license_number'));
                $('#edit-address').val($(this).data('address'));
                $('#edit-lga').val($(this).data('lga'));
                $('#edit-gps_latitude').val($(this).data('gps_latitude'));
                $('#edit-gps_longitude').val($(this).data('gps_longitude'));
                $('#edit-capacity').val($(this).data('capacity'));
                $('#edit-status').val($(this).data('status'));
                $('#edit-description').val($(this).data('description'));
                
                // Update form action
                $('#editAbattoirForm').attr('action', url);
                
                // Show the modal
                $('#editAbattoirModal').modal('show');
            });

            // Handle form submissions with AJAX
            $('#createAbattoirForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        if(response.success) {
                            $('#createAbattoirModal').modal('hide');
                            Swal.fire('Success', 'Abattoir created successfully!', 'success')
                                .then(() => {
                                    location.reload();
                                });
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        if(xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                var input = form.find('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').text(value[0]);
                            });
                        }
                    }
                });
            });

            $('#editAbattoirForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        if(response.success) {
                            $('#editAbattoirModal').modal('hide');
                            Swal.fire('Success', 'Abattoir updated successfully!', 'success')
                                .then(() => {
                                    location.reload();
                                });
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        if(xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                var input = form.find('[name="' + key + '"]');
                                input.addClass('is-invalid');
                                input.next('.invalid-feedback').text(value[0]);
                            });
                        }
                    }
                });
            });

            // Reset forms when modals are closed
            $('#createAbattoirModal').on('hidden.bs.modal', function () {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').text('');
            });

            $('#editAbattoirModal').on('hidden.bs.modal', function () {
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.invalid-feedback').text('');
            });
        });
    </script>
@endpush