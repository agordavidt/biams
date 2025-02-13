@extends('layouts.admin')

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
                                

                                <td class="text-end">
                                    <div class="btn-group">
                                        <!-- View Details Button -->
                                        <div class="col-sm-6 col-md-4 col-xl-3 d-flex">
                                        <div class=""> 
                                            <button type="button" class="action-btn view-btn view-details-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target=".bs-example-modal-lg"
                                                    data-application="{{ json_encode($application) }}">
                                                <i class="ri-eye-fill font-size-25 text-primary align-middle me-2"></i>
                                            </button>
                                        </div> 
                                                @include('partials.crop-farming')        
                                        </div>

                                        <!-- Conditionally Render Approve and Reject Buttons -->
                                        @if ($application->status === 'pending')
                                            <form action="{{ route('admin.applications.approve', ['type' => $type, 'id' => $application->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="action-btn approve-btn" type="submit" title="Approve Application">
                                                    <i class="ri-check-fill font-size-30 text-success align-middle me-2"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.applications.reject', ['type' => $type, 'id' => $application->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button class="action-btn reject-btn" type="submit" title="Reject Application">
                                                <i class="ri-close-circle-fill font-size-30 text-danger align-middle me-2"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
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




<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all buttons with the class 'view-details-btn'
        const viewButtons = document.querySelectorAll('.view-details-btn');

        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get the application data from the data-application attribute
                const application = JSON.parse(this.getAttribute('data-application'));

                document.getElementById('modal-name').textContent = application.user.name;
                document.getElementById('modal-email').textContent = application.user.email;
                document.getElementById('modal-phone').textContent = application.user.profile.phone;
                document.getElementById('modal-gender').textContent = application.seasonal_pattern;
                document.getElementById('modal-crop').textContent = application.crop;

                // Populate the modal with the data
              
                document.getElementById('modal-farm-size').textContent = application.farm_size + ' ha';
                document.getElementById('modal-season').textContent = application.seasonal_pattern;
                document.getElementById('modal-crop').textContent = application.crop;
                document.getElementById('modal-geolocation').textContent = 
                    `${application.latitude}°, ${application.longitude}°`;
                document.getElementById('modal-status').textContent = application.status;
            });
        });
    });
</script>