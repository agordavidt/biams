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




