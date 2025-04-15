@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add Abattoir</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.abattoirs.store') }}">
                            @csrf
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
                            <!-- <div class="mb-3">
                                <label class="form-label">LGA</label>
                                <input type="text" name="lga" class="form-control @error('lga') is-invalid @enderror" value="{{ old('lga') }}">
                                @error('lga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div> -->
                            <div class="mb-3">
                                <label class="form-label">LGA</label>
                                <select name="lga" class="form-control @error('lga') is-invalid @enderror">
                                    <option value="" disabled>Select Local Government Area</option>
                                    <option value="Agatu" {{ old('lga') == 'Agatu' ? 'selected' : '' }}>Agatu</option>
                                    <option value="Apa" {{ old('lga') == 'Apa' ? 'selected' : '' }}>Apa</option>
                                    <option value="Ado" {{ old('lga') == 'Ado' ? 'selected' : '' }}>Ado</option>
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
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection