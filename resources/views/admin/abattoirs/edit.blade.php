@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Abattoir</h4>
                    <a href="{{ route('admin.abattoirs.index') }}" class="btn btn-secondary">Back to Abattoirs</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.abattoirs.update', $abattoir) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $abattoir->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col mb-3">
                                <label class="form-label">Registration Number</label>
                                <input type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" value="{{ old('registration_number', $abattoir->registration_number) }}">
                                @error('registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col mb-3">
                                <label class="form-label">License Number</label>
                                <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" value="{{ old('license_number', $abattoir->license_number) }}">
                                @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            </div>
                            <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">LGA</label>
                                <input type="text" name="lga" class="form-control @error('lga') is-invalid @enderror" value="{{ old('lga', $abattoir->lga) }}">
                                @error('lga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $abattoir->address) }}">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            </div>
                            <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">GPS Latitude</label>
                                <input type="number" step="any" name="gps_latitude" class="form-control @error('gps_latitude') is-invalid @enderror" value="{{ old('gps_latitude', $abattoir->gps_latitude) }}">
                                @error('gps_latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col mb-3">
                                <label class="form-label">GPS Longitude</label>
                                <input type="number" step="any" name="gps_longitude" class="form-control @error('gps_longitude') is-invalid @enderror" value="{{ old('gps_longitude', $abattoir->gps_longitude) }}">
                                @error('gps_longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            </div> 
                            <div class="row">
                            <div class="col mb-3">
                                <label class="form-label">Capacity</label>
                                <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity', $abattoir->capacity) }}">
                                @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $abattoir->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $abattoir->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status', $abattoir->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            </div>  
                           
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $abattoir->description) }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection