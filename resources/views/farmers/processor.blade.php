@extends('layouts.forms')

@section('content')


        <div class="main-content"  Style="margin-left: 10%; margin-right: 5%">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <p class="mb-sm-0">Registration for Processing and Value Addition Practices</p>

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