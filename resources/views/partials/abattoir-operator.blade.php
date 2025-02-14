
    <!--  Modal content for the above example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="card-header">
                    <h4><span id="modal-name"></span> </h4>
                    <p>{{ $application->user->email }}</p>
                    <p>{{ $application->user->profile->phone}}</p>
                </div>
                <div class="card-body">
                <div class="row">
                        <div class="col-6">
                            <!-- Profile Information -->
                        @if($application->user->profile)
                            <h5 class="card-title mt-2">Farmer Profile</h5>
                            <ul>
                                <li><strong>Age:</strong> {{ $application->user->profile->dob }} years</li>
                                <li><strong>Gender:</strong> {{ $application->user->profile->gender }}</li>
                                <li><strong>Education:</strong> {{ $application->user->profile->lga }}</li>
                                <li><strong>Income:</strong> {{ $application->user->profile->income_level }}</li>
                                <li><strong>Hosehold Size:</strong> {{ $application->user->profile->household_size }}</li>
                                <li><strong>Dependents:</strong> {{ $application->user->profile->dependents  }}</li>
                                <li><strong>Local Government Area:</strong> {{ $application->user->profile->lga  }}</li>
                                <li><strong>Ward:</strong> {{ $application->user->profile->gender }}</li>
                                <li><strong>Address:</strong> {{ $application->user->profile->address }}</li>
                            </ul>
                        @endif
                        </div>
                        <div class="col-6">
                        <h5 class="card-title">Farm Details</h5>
                    
                        </div>

                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
