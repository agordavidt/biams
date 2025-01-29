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
                                                        <a href="{{ route('application.details', $registration->id) }}" class="btn btn-sm btn-primary">View Details</a>
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
                                            <h5 class="mb-3">Demographic Information</h5>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" name="phone" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" name="dob" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Gender</label>
                                                    <select class="form-select" name="gender" required>
                                                        <option value="">Select Gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Education Level</label>
                                                    <select class="form-select" name="education" required>
                                                        <option value="">Select Education Level</option>
                                                        <option value="no_formal">No Formal School</option>
                                                        <option value="primary">Primary School</option>
                                                        <option value="secondary">Secondary School</option>
                                                        <option value="undergraduate">Undergraduate</option>
                                                        <option value="graduate">Graduate</option>
                                                        <option value="postgraduate">Post Graduate</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Household Size</label>
                                                    <input type="number" class="form-control" name="household_size" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Number of Dependents</label>
                                                    <input type="number" class="form-control" name="dependents" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Income Level</label>
                                                    <select class="form-select" name="income_level" required>
                                                        <option value="">Select Income Level</option>
                                                        <option value="0-100000">Less than ₦100,000</option>
                                                        <option value="100001-250000">₦100,001 - ₦250,000</option>
                                                        <option value="250001-500000">₦250,001 - ₦500,000</option>
                                                        <option value="500001-1000000">₦500,001 - ₦1,000,000</option>
                                                        <option value="1000001+">Above ₦1,000,000</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Local Government Area</label>
                                                    <select class="form-select" name="lga" required>
                                                        <option value="">Select LGA</option>                                    
                                                        <option value="Ado">Ado</option>
                                                        <option value="Agatu">Agatu</option>
                                                        <option value="Apa">Apa</option>
                                                        <option value="Buruku">Buruku</option>
                                                        <option value="Gboko">Gboko</option>
                                                        <option value="Guma">Guma</option>
                                                        <option value="Gwer East">Gwer East</option>
                                                        <option value="Gwer West">Gwer West</option>
                                                        <option value="Katsina-Ala">Katsina-Ala</option>
                                                        <option value="Konshisha">Konshisha</option>
                                                        <option value="Kwande">Kwande</option>
                                                        <option value="Logo">Logo</option>
                                                        <option value="Makurdi">Makurdi</option>
                                                        <option value="Obi">Obi</option>
                                                        <option value="Ogbadibo">Ogbadibo</option>
                                                        <option value="Oju">Oju</option>
                                                        <option value="Ohimini">Ohimini</option>
                                                        <option value="Okpokwu">Okpokwu</option>
                                                        <option value="Otpo">Otpo</option>
                                                        <option value="Tarka">Tarka</option>
                                                        <option value="Ukum">Ukum</option>
                                                        <option value="Ushongo">Ushongo</option>
                                                        <option value="Vandeikya">Vandeikya</option>
                                                            
                                                    </select>
                                                </div>
                                            </div>

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




