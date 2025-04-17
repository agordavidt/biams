@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Register Livestock</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.livestock.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Species</label>
                                    <select name="species" class="form-control @error('species') is-invalid @enderror">
                                        <option value="cattle">Cattle</option>
                                        <option value="goat">Goat</option>
                                        <option value="sheep">Sheep</option>
                                        <option value="pig">Pig</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('species') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Breed</label>
                                    <input type="text" name="breed" class="form-control @error('breed') is-invalid @enderror" value="{{ old('breed') }}">
                                    @error('breed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Origin State</label>
                                    <select id="origin_state" name="origin_state" class="form-control @error('origin_state') is-invalid @enderror">
                                        <option value="">Select State</option>
                                        <option value="Abia">Abia</option>
                                        <option value="Adamawa">Adamawa</option>
                                        <option value="Akwa Ibom">Akwa Ibom</option>
                                        <option value="Anambra">Anambra</option>
                                        <option value="Bauchi">Bauchi</option>
                                        <option value="Bayelsa">Bayelsa</option>
                                        <option value="Benue">Benue</option>
                                        <option value="Borno">Borno</option>
                                        <option value="Cross River">Cross River</option>
                                        <option value="Delta">Delta</option>
                                        <option value="Ebonyi">Ebonyi</option>
                                        <option value="Edo">Edo</option>
                                        <option value="Ekiti">Ekiti</option>
                                        <option value="Enugu">Enugu</option>
                                        <option value="Gombe">Gombe</option>
                                        <option value="Imo">Imo</option>
                                        <option value="Jigawa">Jigawa</option>
                                        <option value="Kaduna">Kaduna</option>
                                        <option value="Kano">Kano</option>
                                        <option value="Katsina">Katsina</option>
                                        <option value="Kebbi">Kebbi</option>
                                        <option value="Kogi">Kogi</option>
                                        <option value="Kwara">Kwara</option>
                                        <option value="Lagos">Lagos</option>
                                        <option value="Nasarawa">Nasarawa</option>
                                        <option value="Niger">Niger</option>
                                        <option value="Ogun">Ogun</option>
                                        <option value="Ondo">Ondo</option>
                                        <option value="Osun">Osun</option>
                                        <option value="Oyo">Oyo</option>
                                        <option value="Plateau">Plateau</option>
                                        <option value="Rivers">Rivers</option>
                                        <option value="Sokoto">Sokoto</option>
                                        <option value="Taraba">Taraba</option>
                                        <option value="Yobe">Yobe</option>
                                        <option value="Zamfara">Zamfara</option>
                                        <option value="Federal Capital Territory">Federal Capital Territory</option>
                                    </select>
                                    @error('origin_state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Origin LGA</label>
                                    <input type="text" name="origin_lga" class="form-control @error('origin_lga') is-invalid @enderror" value="{{ old('origin_lga') }}">
                                    @error('origin_lga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Origin Location</label>
                                    <input type="text" name="origin_location" class="form-control @error('origin_location') is-invalid @enderror" value="{{ old('origin_location') }}">
                                    @error('origin_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Owner Name</label>
                                    <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name') }}">
                                    @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Owner Phone</label>
                                    <input type="text" name="owner_phone" class="form-control @error('owner_phone') is-invalid @enderror" value="{{ old('owner_phone') }}">
                                    @error('owner_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Owner Address</label>
                                    <input type="text" name="owner_address" class="form-control @error('owner_address') is-invalid @enderror" value="{{ old('owner_address') }}">
                                    @error('owner_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Registration Date</label>
                                    <input type="date" name="registration_date" class="form-control @error('registration_date') is-invalid @enderror" value="{{ old('registration_date') }}">
                                    @error('registration_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Estimated Weight (kg)</label>
                                    <input type="number" step="0.1" name="estimated_weight_kg" class="form-control @error('estimated_weight_kg') is-invalid @enderror" value="{{ old('estimated_weight_kg') }}">
                                    @error('estimated_weight_kg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Estimated Age (months)</label>
                                    <input type="number" name="estimated_age_months" class="form-control @error('estimated_age_months') is-invalid @enderror" value="{{ old('estimated_age_months') }}">
                                    @error('estimated_age_months') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // No more API calls for states and LGAs.
        // The states are directly listed in the HTML.
        // Users will manually input the Local Government Area.
    </script>
@endsection