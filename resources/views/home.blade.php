@extends('layouts.user')

@section('content')

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p>Welcome, {{ $user->name }}</p>
                        <!-- <div class="w-80">
                            @if (auth()->user()->status === 'pending')
                                <div class="d-flex align-items-center gap-3 p-3 bg-warning text-dark rounded">
                                    <i class="fas fa-hourglass-half fa-3x"></i> 
                                    <div>
                                        <h4 class="mb-0"><strong>Pending</strong></h4>                                        
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center gap-3 p-3 bg-success text-white rounded">
                                    <i class="fas fa-check-circle fa-3x"></i> 
                                    <div>
                                        <h4 class="mb-0"><strong>Onboarded</strong></h4>
                                    </div>
                                </div>
                            @endif
                        </div> -->
               


                 

                        <!-- <div class="col-sm-6 col-md-4 col-xl-3">
                            <div class="my-4 text-center">
                                <p class="text-muted">Crop Farming</p>
                               
                                <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">Crop Farming</button>
                            </div>

                            <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalScrollableTitle">Crop Farming</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                                <form method="POST" action="{{ route('farmers.crop.store') }}">
                                                @csrf

                                               
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif                                          

                                             
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                       
                                                        <div class="mb-4">
                                                            <label class="form-label" for="farm_size">Farm Size (hectares)</label>
                                                            <input type="number" step="0.1" class="form-control input-mask" name="farm_size" value="{{ old('farm_size') }}" required>
                                                        </div>

                                                       
                                                        <div class="mb-4">
                                                            <label class="form-label" for="farming_methods">Farming Methods</label>
                                                            <select class="form-control input-mask" name="farming_methods" required>
                                                                <option value="organic" {{ old('farming_methods') === 'organic' ? 'selected' : '' }}>Organic</option>
                                                                <option value="conventional" {{ old('farming_methods') === 'conventional' ? 'selected' : '' }}>Conventional</option>
                                                                <option value="mixed" {{ old('farming_methods') === 'mixed' ? 'selected' : '' }}>Mixed</option>
                                                            </select>
                                                        </div>

                                                       
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

                                                       
                                                        <div class="mb-4">
                                                            <label class="form-label" for="farm_location">Farm Location</label>
                                                            <input type="text" class="form-control input-mask" name="farm_location" value="{{ old('farm_location') }}" required>
                                                        </div>

                                                      
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

                                                      
                                                        <div class="mb-4" id="otherCropField" style="display: none;">
                                                            <label for="otherCrop">Specify the crop:</label>
                                                            <input type="text" name="other_crop" id="otherCrop" value="{{ old('other_crop') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                             
                                                <div class="text-center mt-4">
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit Form</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->








                        
             <!-- Practice Selection -->
              @if (auth()->user()->status === 'onboarded')
               
                         <h4 class="card-title mb-4">Select Your Agricultural Practice</h4>
                            <div class="row g-4">
                                <!-- Crop Farming -->
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('farmers.crop') }}">
                                         <div class="card practice-card" data-practice="crop_farming">
                                            <div class="card-body text-center">
                                                <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                                                <h4 class="mb-2">Crop Farming</h4>
                                                <p class="text-truncate font-size-14 mb-2">Register as a crop farmer</p>
                                            </div>
                                    </div>
                                    </a>
                                   
                                </div>

                                <!-- Animal Farming -->
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('farmers.animal') }}">
                                         <div class="card practice-card" data-practice="animal_farming">
                                        <div class="card-body text-center">
                                            <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                                            <h4 class="mb-2">Animal Farming</h4>
                                            <p class="text-truncate font-size-14 mb-2">Register as an animal farmer</p>
                                        </div>
                                    </div>
                                    </a>
                                </div>

                                <!-- Processing -->
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('farmers.processor') }}">
                                         <div class="card practice-card" data-practice="processing">
                                        <div class="card-body text-center">
                                            <i class="fas fa-industry fa-3x text-success mb-3"></i>
                                            <h4 class="mb-2">Processing & Value Addition</h4>
                                            <p class="text-truncate font-size-14 mb-2">Register as an agricultural processor</p>  
                                        </div>
                                    </div>
                                    </a>
                                   
                                </div>

                                <!-- Abattoir -->
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('farmers.abattoir') }}">
                                         <div class="card practice-card" data-practice="abattoir">
                                        <div class="card-body text-center">
                                            <i class="fas fa-warehouse fa-3x text-success mb-3"></i>
                                             <h4 class="mb-2">Abattoir</h4>
                                            <p class="text-truncate font-size-14 mb-2">Register as an abattoir operator</p>                                            
                                        </div>
                                    </div>
                                    </a>
                                   
                                </div>
                            </div>

                            <!--- more agricultural practices -->
                            <div class="row g-4 mt-3">
                                <!-- Agricultural services-->
                                <div class="col-md-6 col-lg-3">
                                    <a href="#">
                                         <div class="card practice-card" data-practice="crop_farming">
                                            <div class="card-body text-center">
                                                <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                                                <h4 class="mb-2">Agricultural Services</h4>
                                                <p class="text-truncate font-size-14 mb-2">Register as a crop farmer</p>
                                            </div>
                                    </div>
                                    </a>
                                   
                                </div>

                                <!-- Aqualculture and Fisheries -->
                                <div class="col-md-6 col-lg-3">
                                    <a href="#">
                                         <div class="card practice-card" data-practice="animal_farming">
                                        <div class="card-body text-center">
                                            <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                                            <h4 class="mb-2">Aqualculture and Fisheries</h4>
                                            <p class="text-truncate font-size-14 mb-2">Register as an animal farmer</p>
                                        </div>
                                    </div>
                                    </a>
                                </div>

                                <!-- Processing -->
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('farmers.processor') }}">
                                         <div class="card practice-card" data-practice="processing">
                                        <div class="card-body text-center">
                                            <i class="fas fa-industry fa-3x text-success mb-3"></i>
                                            <h4 class="mb-2">Agroforestry and Forestry</h4>
                                            <p class="text-truncate font-size-14 mb-2">Register as an agricultural processor</p>  
                                        </div>
                                    </div>
                                    </a>
                                   
                                </div>

                                <!-- Abattoir -->
                                <div class="col-md-6 col-lg-3">
                                    <a href="{{ route('farmers.abattoir') }}">
                                         <div class="card practice-card" data-practice="abattoir">
                                        <div class="card-body text-center">
                                            <i class="fas fa-warehouse fa-3x text-success mb-3"></i>
                                             <h4 class="mb-2">Abattoir</h4>
                                            <p class="text-truncate font-size-14 mb-2">Register as an abattoir operator</p>                                            
                                        </div>
                                    </div>
                                    </a>
                                   
                                </div>
                            </div>
                        </div>
            @else
                <div class="alert alert-warning">
                    You must be onboarded to access the agricultural practice application forms.
                </div>
            @endif

            <div class="row">
                   <!-- Practice Registration Modal -->
                   <div class="modal fade" id="practiceModal" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Agricultural Practice Registration</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">

                                    <!-- ==================== Form ============================= -->
                                        <form id="practiceForm" class="needs-validation" novalidate  method="POST" action="#" >
                                            @csrf
                                            <!-- Common Demographics Section -->
                                            

                                            <!-- Dynamic Practice-Specific Fields -->
                                            <div id="practiceFields" class="mt-4">
                                                <!-- Will be populated via JavaScript -->
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-success" id="submitPractice">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>

                     
                        
                    </div> 
                </div>
        <!-- End Page-content -->
        
        

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


                function handleOtherLivestock() {
                    const livestockSelect = document.getElementById('livestock');
                    const otherLivestockField = document.getElementById('otherLivestockField');
                    if (livestockSelect.value === 'Other') {
                        otherLivestockField.style.display = 'block';
                    } else {
                        otherLivestockField.style.display = 'none';
                    }
                }
            </script>

            
            @endsection




