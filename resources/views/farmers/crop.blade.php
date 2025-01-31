@extends('layouts.forms')

@section('content')




        <div class="main-content"  Style="margin-left: 10%; margin-right: 5%">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Crop Farming Registration</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                             <!-- <a class="btn btn-info mb-5 waves-effect waves-light" href="index.html">Back to Dashboard</a> -->
                                            <li class="breadcrumb-item active">Registration</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- <h4 class="card-title mb-4">Demographic Inforamtion</h4> -->
                                       <form method="POST" action="{{ route('farmers.crop.store') }}">
                                            @csrf

                                            <!-- Display validation errors -->
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif                                          

                                            <!-- Farm Details -->
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <!-- Farm Size -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="farm_size">Farm Size (hectares)</label>
                                                        <input type="number" step="0.1" class="form-control input-mask" name="farm_size" value="{{ old('farm_size') }}" required>
                                                    </div>

                                                    <!-- Farming Methods -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="farming_methods">Farming Methods</label>
                                                        <select class="form-control input-mask" name="farming_methods" required>
                                                            <option value="organic" {{ old('farming_methods') === 'organic' ? 'selected' : '' }}>Organic</option>
                                                            <option value="conventional" {{ old('farming_methods') === 'conventional' ? 'selected' : '' }}>Conventional</option>
                                                            <option value="mixed" {{ old('farming_methods') === 'mixed' ? 'selected' : '' }}>Mixed</option>
                                                        </select>
                                                    </div>

                                                    <!-- Seasonal Pattern -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="seasonal_pattern">Seasonal Pattern</label>
                                                        <select class="form-control input-mask" name="seasonal_pattern" required>
                                                            <option value="rainy" {{ old('seasonal_pattern') === 'rainy' ? 'selected' : '' }}>Rainy Season</option>
                                                            <option value="dry" {{ old('seasonal_pattern') === 'dry' ? 'selected' : '' }}>Dry Season</option>
                                                            <option value="both" {{ old('seasonal_pattern') === 'both' ? 'selected' : '' }}>Both Seasons</option>
                                                        </select>
                                                    </div>

                                                     <!-- Land tenancy -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="land_tenure">Land Tenure</label>
                                                        <select class="form-control input-mask" name="land_tenure" >
                                                            <option value="owned" {{ old('farming_methods') === 'organic' ? 'selected' : '' }}>Owned</option>
                                                            <option value="leased" {{ old('farming_methods') === 'conventional' ? 'selected' : '' }}>Leased</option>
                                                            <option value="inherited" {{ old('farming_methods') === 'mixed' ? 'selected' : '' }}>Inherited</option>
                                                        </select>
                                                    </div>

                                                </div>

                                                <div class="col-lg-6">
                                                    <!-- Geolocation -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="latitude">Farm Coordinates</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="latitude" placeholder="Latitude" value="{{ old('latitude') }}" required>
                                                            <input type="text" class="form-control" name="longitude" placeholder="Longitude" value="{{ old('longitude') }}" required>
                                                            <button type="button" class="btn btn-outline-secondary" onclick="getLocation()">
                                                                <i class="fas fa-map-marker-alt"></i> Get Location
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Farm Location -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="farm_location">Farm Location</label>
                                                        <input type="text" class="form-control input-mask" name="farm_location" value="{{ old('farm_location') }}" required>
                                                    </div>

                                                    <!-- Crop Cultivated -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="crop">Crop Cultivated</label>
                                                        <select class="form-control input-mask" name="crop" id="crops" onchange="handleOtherOption()" required>
                                                            <option value="">Select Crop</option>
                                                            <option value="Yam" {{ old('crop') === 'Yam' ? 'selected' : '' }}>Yams</option>
                                                            <option value="Rice" {{ old('crop') === 'Rice' ? 'selected' : '' }}>Rice</option>
                                                            <option value="Cassava" {{ old('crop') === 'Cassava' ? 'selected' : '' }}>Cassava</option>
                                                            <option value="Other" {{ old('crop') === 'Other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>

                                                    <!-- Hidden input field for custom crop -->
                                                    <div class="mb-4" id="otherCropField" style="display: none;">
                                                        <label for="otherCrop">Specify the crop:</label>
                                                        <input type="text" name="other_crop" id="otherCrop" value="{{ old('other_crop') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit Form</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © Benue State Integrated Agricultural Assets Management System.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Powered <i class="mdi mdi-heart text-danger"></i> BDIC
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                
            </div>





            

@endsection



<!-- JavaScript to handle "Other" crop option -->
<script>
    function handleOtherOption() {
        const cropSelect = document.getElementById('crops');
        const otherCropField = document.getElementById('otherCropField');
        if (cropSelect.value === 'Other') {
            otherCropField.style.display = 'block';
        } else {
            otherCropField.style.display = 'none';
        }
    }
</script>