@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Livestock: {{ $livestock->tracking_id }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.livestock.update', $livestock) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Species</label>
                                    <select name="species" class="form-control @error('species') is-invalid @enderror">
                                        <option value="cattle" {{ $livestock->species === 'cattle' ? 'selected' : '' }}>Cattle</option>
                                        <option value="goat" {{ $livestock->species === 'goat' ? 'selected' : '' }}>Goat</option>
                                        <option value="sheep" {{ $livestock->species === 'sheep' ? 'selected' : '' }}>Sheep</option>
                                        <option value="pig" {{ $livestock->species === 'pig' ? 'selected' : '' }}>Pig</option>
                                        <option value="other" {{ $livestock->species === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('species') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Breed</label>
                                    <input type="text" name="breed" class="form-control @error('breed') is-invalid @enderror" value="{{ old('breed', $livestock->breed) }}">
                                    @error('breed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                        <option value="male" {{ $livestock->gender === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $livestock->gender === 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Origin Location</label>
                                    <input type="text" name="origin_location" class="form-control @error('origin_location') is-invalid @enderror" value="{{ old('origin_location', $livestock->origin_location) }}">
                                    @error('origin_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Origin LGA</label>
                                    <input type="text" name="origin_lga" class="form-control @error('origin_lga') is-invalid @enderror" value="{{ old('origin_lga', $livestock->origin_lga) }}">
                                    @error('origin_lga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Origin State</label>
                                    <input type="text" name="origin_state" class="form-control @error('origin_state') is-invalid @enderror" value="{{ old('origin_state', $livestock->origin_state) }}">
                                    @error('origin_state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Owner Name</label>
                                    <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name', $livestock->owner_name) }}">
                                    @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Owner Phone</label>
                                    <input type="text" name="owner_phone" class="form-control @error('owner_phone') is-invalid @enderror" value="{{ old('owner_phone', $livestock->owner_phone) }}">
                                    @error('owner_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Owner Address</label>
                                    <input type="text" name="owner_address" class="form-control @error('owner_address') is-invalid @enderror" value="{{ old('owner_address', $livestock->owner_address) }}">
                                    @error('owner_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Registration Date</label>
                                    <input type="date" name="registration_date" class="form-control @error('registration_date') is-invalid @enderror" value="{{ old('registration_date', $livestock->registration_date->format('Y-m-d')) }}">
                                    @error('registration_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Estimated Weight (kg)</label>
                                    <input type="number" step="0.1" name="estimated_weight_kg" class="form-control @error('estimated_weight_kg') is-invalid @enderror" value="{{ old('estimated_weight_kg', $livestock->estimated_weight_kg) }}">
                                    @error('estimated_weight_kg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Estimated Age (months)</label>
                                    <input type="number" name="estimated_age_months" class="form-control @error('estimated_age_months') is-invalid @enderror" value="{{ old('estimated_age_months', $livestock->estimated_age_months) }}">
                                    @error('estimated_age_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection