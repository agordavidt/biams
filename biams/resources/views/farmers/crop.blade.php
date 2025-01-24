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

                                            <!-- Demographic Fields -->
                                            <div class="row">
                                                <!-- Left Column -->
                                                <div class="col-lg-6">
                                                    <!-- Phone -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="phone">Phone Number</label>
                                                        <input type="tel" class="form-control input-mask" name="phone" value="{{ old('phone', $user->phone) }}" readonly>
                                                    </div>

                                                    <!-- Date of Birth -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="dob">Date of Birth</label>
                                                        <input type="date" class="form-control input-mask" name="dob" value="{{ old('dob', $user->dob) }}" required>
                                                    </div>

                                                    <!-- Gender -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="gender">Gender</label>
                                                        <select class="form-control" name="gender" readonly>
                                                            <option value="">Select Gender</option>
                                                            <option value="Male" {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                                            <option value="Female" {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                                        </select>
                                                    </div>

                                                    <!-- Education Level -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="education">Education Level</label>
                                                        <select class="form-control input-mask text-left" name="education" required>
                                                            <option value="">Select Education Level</option>
                                                            <option value="no_formal" {{ old('education', $user->education) === 'no_formal' ? 'selected' : '' }}>No Formal School</option>
                                                            <option value="primary" {{ old('education', $user->education) === 'primary' ? 'selected' : '' }}>Primary School</option>
                                                            <option value="secondary" {{ old('education', $user->education) === 'secondary' ? 'selected' : '' }}>Secondary School</option>
                                                            <option value="undergraduate" {{ old('education', $user->education) === 'undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                                            <option value="graduate" {{ old('education', $user->education) === 'graduate' ? 'selected' : '' }}>Graduate</option>
                                                            <option value="postgraduate" {{ old('education', $user->education) === 'postgraduate' ? 'selected' : '' }}>Post Graduate</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Right Column -->
                                                <div class="col-lg-6">
                                                    <!-- Household Size -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="household_size">Household Size</label>
                                                        <input type="number" class="form-control input-mask" name="household_size" value="{{ old('household_size', $user->household_size) }}" readonly>
                                                    </div>

                                                    <!-- Number of Dependents -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="dependents">Number of Dependents</label>
                                                        <input type="number" class="form-control input-mask" name="dependents" value="{{ old('dependents', $user->dependents) }}" readonly>
                                                    </div>

                                                    <!-- Income Level -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="income_level">Income Level</label>
                                                        <select class="form-control input-mask" name="income_level" required>
                                                            <option value="">Select Income Level</option>
                                                            <option value="0-100000" {{ old('income_level', $user->income_level) === '0-100000' ? 'selected' : '' }}>Less than ₦100,000</option>
                                                            <option value="100001-250000" {{ old('income_level', $user->income_level) === '100001-250000' ? 'selected' : '' }}>₦100,001 - ₦250,000</option>
                                                            <option value="250001-500000" {{ old('income_level', $user->income_level) === '250001-500000' ? 'selected' : '' }}>₦250,001 - ₦500,000</option>
                                                            <option value="500001-1000000" {{ old('income_level', $user->income_level) === '500001-1000000' ? 'selected' : '' }}>₦500,001 - ₦1,000,000</option>
                                                            <option value="1000001+" {{ old('income_level', $user->income_level) === '1000001+' ? 'selected' : '' }}>Above ₦1,000,000</option>
                                                        </select>
                                                    </div>

                                                    <!-- Local Government Area (LGA) -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="lga">Local Government Area</label>
                                                        <select class="form-control input-mask" name="lga" required>
                                                            <option value="">Select LGA</option>
                                                            <option value="Ado" {{ old('lga', $user->lga) === 'Ado' ? 'selected' : '' }}>Ado</option>
                                                            <option value="Agatu" {{ old('lga', $user->lga) === 'Agatu' ? 'selected' : '' }}>Agatu</option>
                                                            <option value="Apa" {{ old('lga', $user->lga) === 'Apa' ? 'selected' : '' }}>Apa</option>
                                                            <!-- Add other LGAs here -->
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

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
                                                </div>

                                                <div class="col-lg-6">
                                                    <!-- Geolocation -->
                                                    <div class="mb-4">
                                                        <label class="form-label" for="latitude">Geolocation</label>
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