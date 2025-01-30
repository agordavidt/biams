@extends('layouts.user')

@section('content')

<div class="main-content">                
               
          
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="w-80">
                            @if (auth()->user()->status === 'pending')
                                <!-- Pending Status -->
                                <div class="d-flex align-items-center gap-3 p-3 bg-warning text-dark rounded">
                                    <i class="fas fa-hourglass-half fa-3x"></i> <!-- Pending Icon -->
                                    <div>
                                        <h4 class="mb-0"><strong>Pending</strong></h4>
                                        <p class="mb-0">Your application is under review. Please check back later.</p>
                                    </div>
                                </div>
                            @elseif (auth()->user()->status === 'rejected')
                                <!-- Rejected Status -->
                                <div class="d-flex align-items-center gap-3 p-3 bg-danger text-white rounded">
                                    <i class="fas fa-times-circle fa-3x"></i> <!-- Rejected Icon -->
                                    <div>
                                        <h4 class="mb-0"><strong>Rejected</strong></h4>
                                        <p class="mb-0">Your application has been rejected. Please contact support for more information.</p>
                                    </div>
                                </div>
                            @else
                                <!-- Onboarded (Verified) Status -->
                                <div class="d-flex align-items-center gap-3 p-3 bg-success text-white rounded">
                                    <i class="fas fa-check-circle fa-3x"></i> <!-- Verified Icon -->
                                    <div>
                                        <h4 class="mb-0"><strong>Onboarded</strong></h4>
                                        <p class="mb-0">You have been successfully onboarded. You now have full access to the system.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        


                        <div class="container">
                                <h1>Welcome, {{ $user->name }}</h1>

                                <!-- Display user's registrations -->
                                <h2>Your Registrations</h2>
                                @if ($registrations->isEmpty())
                                    <!-- Display this message if there are no registrations -->
                                    <div class="alert alert-info">
                                        No registrations found.
                                    </div>
                                @else
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Application Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($registrations as $registration)
                                                <tr>
                                                    <td>{{ $registration->type }}</td>
                                                    <td>{{ ucfirst($registration->status) }}</td>
                                                    <td>{{ $registration->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-primary">View Details</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                              
                </div>


                 <div class="row">
                            <div class="col-lg-4">
                                <div class="card bg-primary text-white-50">
                                    <div class="card-body">
                                        <h5 class="mb-4 text-white"><i class="mdi mdi-bullseye-arrow me-3"></i> Primary Card</h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-lg-4">
                                <div class="card bg-success text-white-50">
                                    <div class="card-body">
                                        <h5 class="mb-4 text-white"><i class="mdi mdi-check-all me-3"></i> Success Card</h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-lg-4">
                                <div class="card bg-info text-white-50">
                                    <div class="card-body">
                                        <h5 class="mb-4 text-white"><i class="mdi mdi-alert-circle-outline me-3"></i>Info Card</h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card bg-warning text-white-50">
                                    <div class="card-body">
                                        <h5 class="mb-4 text-white"><i class="mdi mdi-alert-outline me-3"></i>Warning Card</h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-lg-4">
                                <div class="card bg-danger text-white-50">
                                    <div class="card-body">
                                        <h5 class="mb-4 text-white"><i class="mdi mdi-block-helper me-3"></i>Danger Card</h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-lg-4">
                                <div class="card bg-dark text-light">
                                    <div class="card-body">
                                        <h5 class="mb-4 text-light"><i class="mdi mdi-alert-circle-outline me-3"></i>Dark Card</h5>
                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->




                          <div class="col-sm-6 col-md-4 col-xl-3">
                                                <div class="my-4 text-center">
                                                    <p class="text-muted">Scrollable modal</p>
                                                    <!-- Small modal -->
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">Scrollable modal</button>
                                                </div>
        
                                                <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalScrollableTitle">Scrollable Modal</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
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
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button>
                                                            </div>
                                                        </div><!-- /.modal-content -->
                                                    </div><!-- /.modal-dialog -->
                                                </div><!-- /.modal -->
                                            </div>








                        
             <!-- Practice Selection -->
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
                                            <h4 class="mb-2">Processing</h4>
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
        <!-- End Page-content -->
        
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>document.write(new Date().getFullYear())</script> © <span class="text-info">Benue State Integrated Agricultural Assest Management system. </span> 
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Powered by BDIC
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        
    </div>



<div>
     <!-- Main Content -->
   

</div>

   

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

 
@endsection




