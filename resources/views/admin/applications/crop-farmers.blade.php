@extends('layouts.table')

@section('content')


                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Crop Farming</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Applications</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
        
                                        <h4 class="card-title"></h4>
                                        <p class="card-title-desc">
                                        </p>
        
                                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                            <tr>
                                                <!-- <th>#</th> -->
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>Farm Size</th>
                                                <th>Season</th>
                                                <th>Crop</th>
                                                <th>Geolocation</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                             @foreach($applications as $application)
                                                <tr>
                                                    <!-- <td>{{ $loop->iteration }}</td> -->
                                                    <td>{{ $application->created_at->format('Y/m/d') }}</td>
                                                    <td>{{ $application->user->name }}</td>
                                                    <!-- <td>{{ $application->user->profile->gender }}</td>
                                                    <td>{{ $application->user->profile->lga }}</td> -->
                                                    <td>{{ $application->farm_size }} ha</td>
                                                    <td>{{ $application->seasonal_pattern }}</td>
                                                    <td>{{ $application->crop }}</td>

                                                    <td>
                                                        <a class="popup-gmaps" 
                                                            href="https://maps.google.com/?ll={{ $application->latitude }},{{ $application->longitude }}" 
                                                            target="_blank">
                                                            {{ number_format($application->latitude, 4) }}°, {{ number_format($application->longitude, 4) }}°
                                                        </a>
                                                    </td>  

                                                    <td>
                                                         <div class="font-size-13">
                                                            @if($application->status == 'approved')
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                                            @elseif($application->status == 'pending')
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>
                                                            @else
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-secondary align-middle me-2"></i>
                                                            @endif
                                                            {{ ucfirst($application->status) }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-sm-6 col-md-4 col-xl-3 d-flex">
                                                            <div class="my-4 text-center">                                                    
                                                                <button type="button" class="" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg"><i class="ri-eye-fill font-size-25 text-primary align-middle me-2"></i></button>
                                                            </div> 
                                                                @include('partials.crop-farming')        
                                                        </div>
                                                        <i class="ri-eye-fill font-size-25 text-primary align-middle me-2"></i>
                                                        <i class="ri-check-fill font-size-25 text-success align-middle me-2"></i>
                                                        <i class="ri-close-circle-fill font-size-25 text-danger align-middle me-2"></i>
                                                    </td>
                                                </tr>
                                            @endforeach   
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div> 
                        </div> 

                                        

@endsection

                   