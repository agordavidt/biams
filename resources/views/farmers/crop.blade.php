@extends('layouts.new')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Crop Farming Registration</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Crop Registration</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('farmers.crop.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="farm_size">Farm Size (hectares)</label>
                                    <input type="number" step="0.1" class="form-control" id="farm_size"
                                        name="farm_size" value="{{ old('farm_size') }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="farming_methods">Farming Methods</label>
                                    <select class="form-select" name="farming_methods" required>
                                        <option value="organic"
                                            {{ old('farming_methods') === 'organic' ? 'selected' : '' }}>Organic</option>
                                        <option value="conventional"
                                            {{ old('farming_methods') === 'conventional' ? 'selected' : '' }}>Conventional
                                        </option>
                                        <option value="mixed" {{ old('farming_methods') === 'mixed' ? 'selected' : '' }}>
                                            Mixed</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="seasonal_pattern">Seasonal Pattern</label>
                                    <select class="form-select" name="seasonal_pattern" required>
                                        <option value="rainy" {{ old('seasonal_pattern') === 'rainy' ? 'selected' : '' }}>
                                            Rainy Season</option>
                                        <option value="dry" {{ old('seasonal_pattern') === 'dry' ? 'selected' : '' }}>Dry
                                            Season</option>
                                        <option value="both" {{ old('seasonal_pattern') === 'both' ? 'selected' : '' }}>
                                            Both Seasons</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="land_tenure">Land Tenure</label>
                                    <select class="form-select" name="land_tenure" required>
                                        <option value="owned" {{ old('land_tenure') === 'owned' ? 'selected' : '' }}>Owned
                                        </option>
                                        <option value="leased" {{ old('land_tenure') === 'leased' ? 'selected' : '' }}>
                                            Leased</option>
                                        <option value="inherited"
                                            {{ old('land_tenure') === 'inherited' ? 'selected' : '' }}>Inherited</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Farm Coordinates <i class="fa fa-question-circle"
                                            aria-hidden="true" data-bs-toggle="tooltip" data-bs-html="true"
                                            title="Good day Mr. Daivd. What do you suggest we place here to inform the ordinary farmer about farm coordinates? Maybe you just write it out to me as a reply."></i>

                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="latitude" id="latitude"
                                            placeholder="Latitude" value="{{ old('latitude') }}" required>
                                        <input type="text" class="form-control" name="longitude" id="longitude"
                                            placeholder="Longitude" value="{{ old('longitude') }}" required>
                                        <button type="button" class="btn btn-secondary" onclick="getLocation()">
                                            <i class="ri-map-pin-line me-1"></i> Get Location
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="farm_location">Farm Location</label>
                                    <input type="text" class="form-control" name="farm_location"
                                        value="{{ old('farm_location') }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="crop">Crop Cultivated</label>
                                    <select class="form-select" name="crop" id="crops" onchange="handleOtherOption()"
                                        required>
                                        <option value="">Select Crop</option>
                                        <option value="Yam" {{ old('crop') === 'Yam' ? 'selected' : '' }}>Yams</option>
                                        <option value="Rice" {{ old('crop') === 'Rice' ? 'selected' : '' }}>Rice
                                        </option>
                                        <option value="Cassava" {{ old('crop') === 'Cassava' ? 'selected' : '' }}>Cassava
                                        </option>
                                        <option value="Other" {{ old('crop') === 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-4" id="otherCropField" style="display: none;">
                                    <label class="form-label" for="otherCrop">Specify the crop</label>
                                    <input type="text" class="form-control" name="other_crop" id="otherCrop"
                                        value="{{ old('other_crop') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <a href="{{ route('home') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit Registration</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Function to handle the "Other" crop option
        function handleOtherOption() {
            const cropSelect = document.getElementById('crops');
            const otherCropField = document.getElementById('otherCropField');
            otherCropField.style.display = cropSelect.value === 'Other' ? 'block' : 'none';
        }

        // Initialize the other crop field on page load
        document.addEventListener('DOMContentLoaded', handleOtherOption);

        // Function to get the current location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Function to display the position
        function showPosition(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        }

        // Function to handle errors
        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }
    </script>
@endsection
