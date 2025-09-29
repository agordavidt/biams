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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Users</p>
                            <h4 class="mb-0">{{ number_format($totalUsers) }}</h4>
                        </div>
                        <div class="text-primary">
                            <i class="ri-user-3-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Pending Users</p>
                            <h4 class="mb-0 text-warning">{{ number_format($pendingUsers) }}</h4>
                        </div>
                        <div class="text-warning">
                            <i class="ri-time-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Approved Users</p>
                            <h4 class="mb-0 text-success">{{ number_format($approvedUsers) }}</h4>
                        </div>
                        <div class="text-success">
                            <i class="ri-check-double-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Rejected Users</p>
                            <h4 class="mb-0 text-danger">{{ number_format($rejectedUsers) }}</h4>
                        </div>
                        <div class="text-danger">
                            <i class="ri-close-circle-line font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Header with Add User Button -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">List of Registered Users</h4>
                        <!-- <a class="btn btn-primary waves-effect waves-light popup-form" href="#add-user-form">
                            <i class="ri-add-line align-middle me-1"></i> Add User
                        </a> -->
                    </div>

                    <!-- Search and Filter Form -->
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <form method="GET" action="{{ route('admin.users.summary') }}" class="d-flex gap-3 align-items-end">
                                <div class="flex-grow-1">
                                    <label class="form-label">Search Users</label>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search by name, email, phone, or location..." 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="flex-shrink-0" style="min-width: 150px;">
                                    <label class="form-label">Filter by Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="onboarded" {{ request('status') == 'onboarded' ? 'selected' : '' }}>Onboarded</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-search-line"></i> Search
                                    </button>
                                </div>
                                @if(request('search') || request('status'))
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('admin.users.summary') }}" class="btn btn-light">
                                            <i class="ri-refresh-line"></i> Clear
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Contact Info</th>
                                    <th>Demographics</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
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
                                                <i class="ri-phone-line me-1 text-muted"></i>
                                                {{ $user->profile->phone ?? 'N/A' }}
                                            </div>
                                            @if($user->email_verified_at)
                                                <small class="text-success">
                                                    <i class="ri-check-line"></i> Email Verified
                                                </small>
                                            @else
                                                <small class="text-warning">
                                                    <i class="ri-alert-line"></i> Email Unverified
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>  
                                                <span class="text-muted me-2">Age:</span>                                             
                                                {{ $user->profile && $user->profile->dob ? \Carbon\Carbon::parse($user->profile->dob)->age : 'N/A' }}
                                            </div>
                                            <div>
                                                <span class="text-muted me-2">Gender:</span>
                                                {{ $user->profile->gender ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <i class="ri-map-pin-line me-1 text-muted"></i>
                                            {{ $user->profile->lga ?? 'N/A' }}
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'onboarded' => 'success',
                                                    'pending' => 'warning',
                                                    'rejected' => 'danger',
                                                    'default' => 'secondary'
                                                ];
                                                $statusText = ($user->status === 'onboarded') ? 'Activated' : ucfirst($user->status);
                                                $statusColor = $statusColors[strtolower($user->status)] ?? $statusColors['default'];
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-{{ $statusColor }} me-2"></i>
                                                <span class="text-{{ $statusColor }}">{{ $statusText }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link font-size-16 shadow-none p-0 border-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ri-more-fill"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <!-- View Details -->
                                                    <li>
                                                        <button type="button" class="dropdown-item view-user-details" 
                                                                data-user-id="{{ $user->id }}" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#userDetailsModal">
                                                            <i class="ri-eye-fill me-2 align-middle"></i>View Details
                                                        </button>
                                                    </li>

                                                    <!-- Onboard (only show if user is pending) -->
                                                    @if ($user->status === 'pending')
                                                        <li>
                                                            <form action="{{ route('admin.users.onboard', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="ri-check-double-line me-2 align-middle"></i>Activate
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    <!-- Reject (only show if user is pending) -->
                                                    @if ($user->status === 'pending')
                                                        <li>
                                                            <button type="button" class="dropdown-item text-warning" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#rejectModal{{ $user->id }}">
                                                                <i class="ri-close-circle-line me-2 align-middle"></i>Reject
                                                            </button>
                                                        </li>
                                                    @endif

                                                    <!-- Send Notification -->
                                                    <li>
                                                        <button type="button" class="dropdown-item" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#notifyModal{{ $user->id }}">
                                                            <i class="ri-notification-line me-2 align-middle"></i>Send Notification
                                                        </button>
                                                    </li>

                                                    <!-- Delete (show for rejected users or with caution) -->
                                                    <li>
                                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
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

                                    <!-- Reject Modal -->
                                    @if ($user->status === 'pending')
                                        <div class="modal fade" id="rejectModal{{ $user->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectModalLabel">Reject User - {{ $user->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.users.reject', $user->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="alert alert-warning">
                                                                <i class="ri-alert-line me-2"></i>
                                                                This will reject the user's onboarding request and deactivate their account. 
                                                                The user will not receive any notification at this stage.
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="comment{{ $user->id }}" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" id="comment{{ $user->id }}" name="comment" 
                                                                          rows="4" required maxlength="1000" 
                                                                          placeholder="Please provide a detailed reason for rejecting this user's onboarding request..."></textarea>
                                                                <div class="form-text">This information will be stored for internal audit purposes.</div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="ri-close-circle-line me-1"></i>Reject User
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Notify Modal -->
                                    <div class="modal fade" id="notifyModal{{ $user->id }}" tabindex="-1" aria-labelledby="notifyModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="notifyModalLabel">Send Notification to {{ $user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.users.notify', $user->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="message{{ $user->id }}" class="form-label">Message <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="message{{ $user->id }}" name="message" 
                                                                      rows="4" required 
                                                                      placeholder="Enter your message to the user..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="ri-send-plane-line me-1"></i>Send Notification
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ri-user-line font-size-48 text-muted mb-2"></i>
                                                <h5 class="text-muted">No users found</h5>
                                                @if(request('search') || request('status'))
                                                    <p class="text-muted mb-3">Try adjusting your search criteria</p>
                                                    <a href="{{ route('admin.users.summary') }}" class="btn btn-primary">
                                                        <i class="ri-refresh-line me-1"></i>View All Users
                                                    </a>
                                                @else
                                                    <p class="text-muted">No registered users yet</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="row mt-4">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info">
                                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers float-end">
                                    {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="userDetailsContent">
                        <div class="text-center py-4">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading user details...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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

        // View user details
        $('.view-user-details').on('click', function() {
            const userId = $(this).data('user-id');
            
            // Show loading state
            $('#userDetailsContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading user details...</p>
                </div>
            `);

            // Fetch user details
            $.get(`/admin/users/${userId}/details`)
                .done(function(data) {
                    const statusBadges = {
                        'pending': 'warning',
                        'onboarded': 'success', 
                        'rejected': 'danger'
                    };
                    
                    const statusBadge = statusBadges[data.status] || 'secondary';
                    const statusText = data.status === 'onboarded' ? 'Activated' : data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    
                    let rejectionInfo = '';
                    if (data.status === 'rejected' && data.rejection_reason) {
                        rejectionInfo = `
                            <div class="alert alert-danger mt-3">
                                <h6 class="alert-heading">Rejection Reason:</h6>
                                <p class="mb-1">${data.rejection_reason}</p>
                                <small class="text-muted">Rejected on: ${data.rejected_at}</small>
                            </div>
                        `;
                    }
                    
                    $('#userDetailsContent').html(`
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Basic Information</h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td class="text-muted" width="40%">Name:</td>
                                            <td><strong>${data.name}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Email:</td>
                                            <td>${data.email}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status:</td>
                                            <td><span class="badge bg-${statusBadge}">${statusText}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Role:</td>
                                            <td class="text-capitalize">${data.role}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Email Verified:</td>
                                            <td>
                                                ${data.email_verified_at 
                                                    ? '<span class="text-success"><i class="ri-check-line"></i> Verified on ' + data.email_verified_at + '</span>'
                                                    : '<span class="text-warning"><i class="ri-close-line"></i> Not Verified</span>'
                                                }
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Joined:</td>
                                            <td>${data.created_at}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-3">Profile Information</h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td class="text-muted" width="40%">Phone:</td>
                                            <td>${data.profile.phone}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Date of Birth:</td>
                                            <td>${data.profile.dob}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Age:</td>
                                            <td>${data.profile.age}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Gender:</td>
                                            <td class="text-capitalize">${data.profile.gender}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">NIN:</td>
                                            <td>${data.profile.nin}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Education:</td>
                                            <td class="text-capitalize">${data.profile.education}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Household Size:</td>
                                            <td>${data.profile.household_size}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Dependents:</td>
                                            <td>${data.profile.dependents}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Income Level:</td>
                                            <td class="text-capitalize">${data.profile.income_level}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">LGA:</td>
                                            <td>${data.profile.lga}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Address:</td>
                                            <td>${data.profile.address}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        ${rejectionInfo}
                    `);
                })
                .fail(function() {
                    $('#userDetailsContent').html(`
                        <div class="alert alert-danger">
                            <i class="ri-error-warning-line me-2"></i>
                            Failed to load user details. Please try again.
                        </div>
                    `);
                });
        });
    });
</script>
@endpush