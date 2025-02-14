
@extends('layouts.admin')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Users Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users Management</li>
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
                    <!-- Add User Button -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">List of Registered Users</h4>
                        <a class="btn btn-primary waves-effect waves-light popup-form" href="#add-user-form">
                            <i class="ri-add-line align-middle me-1"></i> Add User
                        </a>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact Info</th>
                                    <th>Demographics</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">                                                
                                                <div>
                                                    <h5 class="font-size-14 mb-1">{{ $user->name }}</h5>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>                                               
                                                {{ $user->profile->phone ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>  
                                                <span class="text-muted me-2">Age:</span>                                             
                                                {{ $user->profile ? \Carbon\Carbon::parse($user->profile->dob)->age : 'N/A' }}
                                            </div>
                                            <div>
                                                <span class="text-muted me-2">Gender:</span>
                                                {{ $user->profile->gender ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <!-- <i class="ri-map-pin-line me-1 text-muted"></i> -->
                                            {{ $user->profile->lga ?? 'N/A' }}
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'onboarded' => 'success',
                                                    'pending' => 'warning',
                                                    'default' => 'secondary'
                                                ];
                                                $statusColor = $statusColors[$user->status] ?? $statusColors['default'];
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-{{ $statusColor }} me-2"></i>
                                                <span class="text-{{ $statusColor }}">{{ ucfirst($user->status) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                        <div class="dropdown">
                                                <button class="btn btn-link font-size-16 shadow-none p-0 border-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <!-- View Details -->
                                                    <li><a class="dropdown-item" href="#"><i class="ri-eye-fill me-2 align-middle"></i>View Details</a></li>

                                                    <!-- Onboard (only show if user is not onboarded or rejected) -->
                                                    @if ($user->status !== 'onboarded' && $user->status !== 'rejected')
                                                        <li>
                                                            <form action="{{ route('admin.users.onboard', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="ri-check-double-line me-2 align-middle"></i>Onboard
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    <!-- Reject (only show if user is not onboarded or rejected) -->
                                                    @if ($user->status === 'review')
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $user->id }}">
                                                                <i class="ri-close-circle-line me-2 align-middle"></i>Reject
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <!-- Delete -->
                                                    <li>
                                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ri-delete-bin-5-line me-2 align-middle"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
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
    </div>


                        <!-- Reject Modal (only show if user is not onboarded or rejected) -->
                        @if ($user->status !== 'onboarded' && $user->status !== 'rejected')
                            <div class="modal fade" id="rejectModal{{ $user->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel">Reject User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.users.reject', $user->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="comment" class="form-label">Reason for Rejection</label>
                                                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif





                <!-- Add User Modal Form -->
                <div class="card mfp-hide mfp-popup-form mx-auto" id="add-user-form">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="mb-0">Add New User</h4>
                            <button type="button" class="btn-close popup-close" aria-label="Close"></button>
                        </div>

                        <form class="custom-validation" action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="name" required 
                                        placeholder="Enter full name"/>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email" required 
                                        placeholder="Enter email address"/>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" class="form-control" id="password" name="password" 
                                            placeholder="Enter password" aria-label="Password" required>
                                        <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" 
                                        required data-parsley-equalto="#password" 
                                        placeholder="Confirm password"/>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Role</label>
                                    <select class="form-select" name="role" required>
                                        <option value="" disabled selected>Select user role</option>
                                        <option value="admin">Administrator</option>
                                        <option value="user">Regular User</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light popup-close">
                                    <i class="ri-close-line align-middle me-1"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line align-middle me-1"></i> Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with better options
        $('#datatable').DataTable({
            pageLength: 10,
            ordering: true,
            responsive: true,
            dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            language: {
                search: "",
                searchPlaceholder: "Search users...",
            }
        });

        // Password visibility toggle
        $("#password-addon").on('click', function() {
            const input = $("#password");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password');
            $(this).find('i').toggleClass('mdi-eye-outline mdi-eye-off-outline');
        });

        // Close popup on cancel
        $(".popup-close").on('click', function() {
            $.magnificPopup.close();
        });
    });
</script>
@endpush
