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
                                        <form method="POST" action="{{ route('farmers.abattoir.store') }}">
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
                                                    <div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="facility_type">Facility Type</label>
                                                            <input type="text" step="0.1" class="form-control input-mask" name="facility_type" required>                                                            
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="facility_specs">Facility Specs</label>
                                                            <textarea class="form-control input-mask" name="facility_specs" required></textarea>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mt-4 mt-lg-0">
                                                        <div class="mb-4">
                                                            <label class="form-label" for="operational_capacity">Operation Capacity</label>
                                                            <input type="text" step="0.1" class="form-control input-mask" name="operational_capacity" required>                                                            
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label" for="certifications">Certifications</label>
                                                            <div>
                                                                <input type="checkbox" name="certifications[]" id="health_cert" value="Health Certificate">
                                                                <label for="health_cert">Health Certificate</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" name="certifications[]" id="env_cert" value="Environmental Compliance">
                                                                <label for="env_cert">Environmental Compliance</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" name="certifications[]" id="safety_cert" value="Safety Compliance">
                                                                <label for="safety_cert">Safety Compliance</label>
                                                            </div>
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