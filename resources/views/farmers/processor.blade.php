@extends('layouts.forms')

@section('content')


        <div class="main-content"  Style="margin-left: 10%; margin-right: 5%">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Registration Form</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
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
                                        <form method="POST" action="{{ route('farmers.processor.store') }}">
                                            @csrf 
                                            
                                            <div class="row">
                                                  @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <div class="col-lg-6">
                                                    <div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="phone">Phone Number</label>
                                                            <input type="tel" class="form-control input-mask" name="phone" required>                                                            
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="dob">Date of Birth</label>
                                                            <input type="date" class="form-control input-mask" name="dob" required>
                                                        </div>
                                                       <div class="mb-4">
                                                            <label class="form-label" for="gender">Gender</label>
                                                            <select  class="form-control"  name="gender" required>
                                                                <option value="" >Select Gender</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="education">Education Level</label>
                                                            <select  class="form-control input-mask text-left" name="education" required">
                                                                <option value="">Select Education Level</option>
                                                                <option value="no_formal">No Formal School</option>
                                                                <option value="primary">Primary School</option>
                                                                <option value="secondary">Secondary School</option>
                                                                <option value="undergraduate">Undergraduate</option>
                                                                <option value="graduate">Graduate</option>
                                                                <option value="postgraduate">Post Graduate</option></div>
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mt-4 mt-lg-0">
                                                        <div class="mb-4">
                                                            <label class="form-label" for="household_size">Household Size</label>
                                                            <input type="number"  class="form-control input-mask" name="household_size" required>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="input-mask">Number of Dependents</label>
                                                            <input type="number" class="form-control input-mask" name="dependents" required>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="income_level">Income Leve</label>
                                                            <select  class="form-control input-mask" name="income_level" required>
                                                               <option value="">Select Income Level</option>
                                                                <option value="0-100000">Less than ₦100,000</option>
                                                                <option value="100001-250000">₦100,001 - ₦250,000</option>
                                                                <option value="250001-500000">₦250,001 - ₦500,000</option>
                                                                <option value="500001-1000000">₦500,001 - ₦1,000,000</option>
                                                                <option value="1000001+">Above ₦1,000,000</option>
                                                          </select>
    
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="lga">Local Government Area</label>
                                                            <select class="form-control input-mask" name="lga" required>
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
                                                                <option value="Vandeikya">Vandeikya</option></div>
                                                            </select>                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                  <!-- <h4 class="card-title mb-4">Farm Details</h4> -->
                                            <!-- --- row 2 ----  -->
                                              <div class="row">
                                                <div class="col-lg-6">
                                                    <div>
                                                       <div class="mb-4"> 
                                                            <label class="form-label" for="processing_capacity">Processing Capacity</label> 
                                                            <input type="number" step="0.1" class="form-control input-mask" name="processing_capacity" required> 
                                                        </div>
                                                        <div class="mb-0">
                                                            <label class="form-label" for="equipment_specs">Equipment Specifications</label> 
                                                            <textarea class="form-control input-mask" name="equipment_specs" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mt-4 mt-lg-0">
                                                        <div class="mb-4">
                                                           <label class="form-label" for="equipment_type">Equipment Type</label> 
                                                           <input type="text" class="form-control input-mask" name="equipment_type" required>
                                                        </div>
                                                         <div class="mb-0">
                                                            <label class="form-label" for="processed_items">Processed Items</label> 
                                                            <textarea class="form-control input-mask" name="processed_items"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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