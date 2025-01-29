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
                                    <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Practice Name</th>
                                                <th>Status</th>
                                                <th>Submission Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($registrations as $registration)
                                                <tr>
                                                    <!-- Use $loop->iteration to generate S/N starting from 1 -->
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $registration->practice->practice_name }}</td>
                                                    <td>
                                                        <span class="badge 
                                                            @if($registration->status === 'pending') bg-warning text-dark
                                                            @elseif($registration->status === 'approved') bg-success text-white
                                                            @elseif($registration->status === 'rejected') bg-danger text-white
                                                            @else bg-secondary text-white
                                                            @endif">
                                                            {{ ucfirst($registration->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $registration->submission_date->format('Y-m-d H:i:s') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @endif
                              
                                <!-- Show links to practice forms -->
                                <h2>Register for Agricultural Practices</h2>
                              <ul>
    @foreach ($practices as $practice)
        <li>
            <a href="#" data-bs-toggle="modal" data-bs-target="#practiceModal-{{ $practice->practice_id }}">
                {{ $practice->practice_name }}
            </a>
        </li>
    @endforeach
</ul>

<!-- Generate Modals Dynamically -->
@foreach ($practices as $practice)
    <div class="modal fade" id="practiceModal-{{ $practice->practice_id }}" tabindex="-1" aria-labelledby="practiceModalLabel-{{ $practice->practice_id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="#">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="practiceModalLabel-{{ $practice->practice_id }}">
                            Register for {{ $practice->practice_name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($practice->practice_name === 'Crop Farming')
                            <!-- Include Crop Farming Form -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="farm_size">Farm Size (hectares)</label>
                                        <input type="number" step="0.1" class="form-control" name="farm_size" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="farming_methods">Farming Methods</label>
                                        <select class="form-control" name="farming_methods" required>
                                            <option value="organic">Organic</option>
                                            <option value="conventional">Conventional</option>
                                            <option value="mixed">Mixed</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="seasonal_pattern">Seasonal Pattern</label>
                                        <select class="form-control" name="seasonal_pattern" required>
                                            <option value="rainy">Rainy Season</option>
                                            <option value="dry">Dry Season</option>
                                            <option value="both">Both Seasons</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="latitude">Geolocation</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="latitude" placeholder="Latitude" required>
                                            <input type="text" class="form-control" name="longitude" placeholder="Longitude" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="farm_location">Farm Location</label>
                                        <input type="text" class="form-control" name="farm_location" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="crop">Crop Cultivated</label>
                                        <select class="form-control" name="crop" id="crops" onchange="handleOtherOption()" required>
                                            <option value="">Select Crop</option>
                                            <option value="Yam">Yam</option>
                                            <option value="Rice">Rice</option>
                                            <option value="Cassava">Cassava</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4" id="otherCropField" style="display: none;">
                                        <label for="otherCrop">Specify the crop:</label>
                                        <input type="text" name="other_crop" id="otherCrop" class="form-control">
                                    </div>
                                </div>
                            </div>
                        @elseif ($practice->practice_name === 'Animal Farming')
                            <!-- Include Animal Farming Form -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="herd_size">Herd Size</label>
                                        <input type="number" class="form-control" name="herd_size" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="facility_type">Facility Type</label>
                                        <select class="form-control" name="facility_type" required>
                                            <option value="Open Grazing">Open Grazing</option>
                                            <option value="Fenced Pasture">Fenced Pasture</option>
                                            <option value="Zero Grazing">Zero Grazing</option>
                                            <option value="Indoor Housing">Indoor Housing</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="breeding_program">Breeding Program</label>
                                        <select class="form-control" name="breeding_program" required>
                                            <option value="Artificial Insemination">Artificial Insemination</option>
                                            <option value="Natural Mating">Natural Mating</option>
                                            <option value="Crossbreeding">Crossbreeding</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="farm_location">Location</label>
                                        <input type="text" class="form-control" name="farm_location" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="livestock">Livestock Type</label>
                                        <select class="form-control" name="livestock" required>
                                            <option value="Cattle">Cattle</option>
                                            <option value="Goats">Goats</option>
                                            <option value="Sheep">Sheep</option>
                                            <option value="Poultry">Poultry</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @elseif ($practice->practice_name === 'Processing')
                            <!-- Processing Form -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="processing_type">Processing Type</label>
                                        <select class="form-control" name="processing_type" required>
                                            <option value="Milling">Milling</option>
                                            <option value="Packaging">Packaging</option>
                                            <option value="Canning">Canning</option>
                                            <option value="Freezing">Freezing</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="capacity">Processing Capacity (tons/day)</label>
                                        <input type="number" class="form-control" name="capacity" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="location">Processing Location</label>
                                        <input type="text" class="form-control" name="location" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="materials">Raw Materials Used</label>
                                        <input type="text" class="form-control" name="materials" required>
                                    </div>
                                </div>
                            </div>
                        @elseif ($practice->practice_name === 'Value Addition')
                            <!-- Value Addition Form -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="product_type">Product Type</label>
                                        <select class="form-control" name="product_type" required>
                                            <option value="Dried Fruits">Dried Fruits</option>
                                            <option value="Juices">Juices</option>
                                            <option value="Snacks">Snacks</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="value_added">Value Added</label>
                                        <input type="text" class="form-control" name="value_added" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="market">Target Market</label>
                                        <input type="text" class="form-control" name="market" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="location">Location</label>
                                        <input type="text" class="form-control" name="location" required>
                                    </div>
                                </div>
                            </div>
                        @elseif ($practice->practice_name === 'Agricultural Services')
                            <!-- Agricultural Services Form -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="service_type">Service Type</label>
                                        <select class="form-control" name="service_type" required>
                                            <option value="Consulting">Consulting</option>
                                            <option value="Equipment Rental">Equipment Rental</option>
                                            <option value="Training">Training</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="service_area">Service Area</label>
                                        <input type="text" class="form-control" name="service_area" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="location">Location</label>
                                        <input type="text" class="form-control" name="location" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="clients">Number of Clients</label>
                                        <input type="number" class="form-control" name="clients" required>
                                    </div>
                                </div>
                            </div>
                        @elseif ($practice->practice_name === 'Aquaculture and Fisheries')
                            <!-- Aquaculture and Fisheries Form -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="fish_type">Fish Type</label>
                                        <select class="form-control" name="fish_type" required>
                                            <option value="Tilapia">Tilapia</option>
                                            <option value="Catfish">Catfish</option>
                                            <option value="Salmon">Salmon</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="pond_size">Pond Size (sq. meters)</label>
                                        <input type="number" class="form-control" name="pond_size" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="location">Location</label>
                                        <input type="text" class="form-control" name="location" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="production_capacity">Production Capacity (tons/year)</label>
                                        <input type="number" class="form-control" name="production_capacity" required>
                                    </div>
                                </div>
                            </div>
                        @elseif ($practice->practice_name === 'Agroforestry and Forestry')
                            <!-- Agroforestry and Forestry Form -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="tree_type">Tree Type</label>
                                        <select class="form-control" name="tree_type" required>
                                            <option value="Fruit Trees">Fruit Trees</option>
                                            <option value="Timber">Timber</option>
                                            <option value="Shade Trees">Shade Trees</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="forest_size">Forest Size (hectares)</label>
                                        <input type="number" class="form-control" name="forest_size" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="location">Location</label>
                                        <input type="text" class="form-control" name="location" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="management_practice">Management Practice</label>
                                        <select class="form-control" name="management_practice" required>
                                            <option value="Sustainable Harvesting">Sustainable Harvesting</option>
                                            <option value="Reforestation">Reforestation</option>
                                            <option value="Mixed Cropping">Mixed Cropping</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @elseif ($practice->practice_name === 'Abattoir')
                            <!-- Abattoir Form -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="capacity">Processing Capacity (animals/day)</label>
                                        <input type="number" class="form-control" name="capacity" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="meat_type">Meat Type</label>
                                        <select class="form-control" name="meat_type" required>
                                            <option value="Beef">Beef</option>
                                            <option value="Pork">Pork</option>
                                            <option value="Poultry">Poultry</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="location">Location</label>
                                        <input type="text" class="form-control" name="location" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="certification">Certification</label>
                                        <select class="form-control" name="certification" required>
                                            <option value="ISO Certified">ISO Certified</option>
                                            <option value="HACCP Certified">HACCP Certified</option>
                                            <option value="None">None</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Placeholder for other practices -->
                            <p>Form for {{ $practice->practice_name }} is under construction.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
                                
                           
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





