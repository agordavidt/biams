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
                                        <form method="POST" action="#">
                                            @csrf 
                                              <div class="row">
                                                <div class="col-lg-6">
                                                    <div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="herd_size">Herd Size</label>
                                                            <input type="number" class="form-control input-mask" name="herd_size" id="herd_size" placeholder="Enter herd size" required>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="facility_type">Facility Type</label>
                                                            <select class="form-control input-mask" name="facility_type" id="facility_type" required>
                                                                <option value="">Select Facility Type</option>
                                                                <option value="Open Grazing">Open Grazing</option>
                                                                <option value="Fenced Pasture">Fenced Pasture</option>
                                                                <option value="Zero Grazing">Zero Grazing</option>
                                                                <option value="Indoor Housing">Indoor Housing</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                       
                                                        <div class="mb-4">
                                                            <label class="form-label" for="breeding_program">Breeding Program</label>
                                                            <select class="form-control input-mask" name="breeding_program" id="breeding_program" required>
                                                                <option value="">Select Breeding Program</option>
                                                                <option value="Artificial Insemination">Artificial Insemination</option>
                                                                <option value="Natural Mating">Natural Mating</option>
                                                                <option value="Crossbreeding">Crossbreeding</option>
                                                                <option value="Selective Breeding">Selective Breeding</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mt-4 mt-lg-0">
                                                         <div class="mb-4">
                                                            <label class="form-label" for="farm_location">Location</label>
                                                            <input type="text" class="form-control input-mask" name="farm_location" id="location" placeholder="Enter location" required>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="livestock">Livestock Type</label>
                                                            <select class="form-control input-mask" name="livestock" id="livestock" onchange="handleOtherLivestock()" required>
                                                                <option value="">Select Livestock</option>
                                                                <option value="Cattle">Cattle</option>
                                                                <option value="Goats">Goats</option>
                                                                <option value="Sheep">Sheep</option>
                                                                <option value="Poultry">Poultry</option>
                                                                <option value="Pigs">Pigs</option>
                                                                <option value="Fish">Fish</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>

                                                        <!-- Hidden input for specifying "Other" livestock -->
                                                        <div class="mb-4" id="otherLivestockField" style="display: none;">
                                                            <label for="other_livestock">Specify the livestock type:</label>
                                                            <input type="text" class="form-control input-mask" name="other_livestock" id="other_livestock">
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
                                    Powered by BDIC
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                
            </div>



            <script>
                // Handle "Other" option for Livestock
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