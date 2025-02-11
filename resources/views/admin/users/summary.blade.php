@extends('layouts.admin')

@section('content')


                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Users Table</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                            <le class="breadcrumb-item active">Users Table</le>
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
                                        <div class="button-items float-end">                                           
                                            <!-- <a href="" type="button" class="btn btn-primary mt-3 w-lg waves-effect waves-light">Add Users</a> -->
                                            <!-- <a type="button" class="btn btn-primary mt-4 btn-sm waves-effect waves-light">Small button</a> -->

                                            <div class="card">
                                                <div class="card-body">
                                                    <div>
                                                        <a class="popup-form btn btn-primary mt-4 btn-sm waves-effect waves-light" href="#test-form">Add Users</a>
                                                    </div>

                                                    <div class="card mfp-hide w-50 mfp-popup-form mx-auto" id="test-form">
                                                        <div class="card-body">
                                                            <h4 class="mb-4">Add a user</h4>   
                                                            <form class="custom-validation m-4" action="{{ route('admin.users.store') }}" method="POST">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label>Name</label>
                                                                    <input type="text" class="form-control" name="name" required placeholder="Enter full name"/>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label>E-Mail</label>
                                                                    <div>
                                                                        <input type="email" class="form-control" name="email" required parsley-type="email" placeholder="Enter a valid e-mail"/>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label>Password</label>
                                                                    <div>
                                                                        <input type="password" id="pass2" class="form-control" name="password" required placeholder="Password"/>
                                                                    </div>
                                                                    <div class="mt-2">
                                                                        <input type="password" class="form-control" name="password_confirmation" required data-parsley-equalto="#pass2" placeholder="Confirm Password"/>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label>Role</label>
                                                                    <div>
                                                                        <select class="form-select" name="role" required>
                                                                            <option selected disabled>Select role</option>
                                                                            <option value="admin">Admin</option>
                                                                            <option value="user">User</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-0">
                                                                    <div>
                                                                        <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                                                            Create User
                                                                        </button>
                                                                        <button type="reset" class="btn btn-secondary waves-effect">
                                                                            Cancel
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
        
                                        <h4 class="card-title">List of registered users</h4>                                     
        
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                            <tr>
                                                 <!-- <th>#</th> -->
                                                <th>Name</th>
                                                <!-- <th>Email</th> -->
                                                <!-- <th>Phone</th> -->
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>LGA</th>                                                
                                                <th>Status</th>
                                                <th>Actions</th>   
                                            </thead>
                                            <tbody>
                                                 @foreach($users as $user)
                                                <tr>
                                                    <!-- <td>{{ $loop->iteration }}</td> -->
                                                    <td>{{ $user->name }}</td>
                                                    <!-- <td>{{ $user->email }}</td> -->
                                                    <!-- <td>{{ $user->profile->phone ?? 'N/A' }}</td> -->
                                                    <td>{{ $user->profile->gender ?? 'N/A' }}</td>
                                                    <td>{{ $user->profile ? \Carbon\Carbon::parse($user->profile->dob)->age : 'N/A' }}</td>                                                  
                                                    <td>{{ $user->profile->lga ?? 'N/A' }}</td>                                                                                                     
                                                    <td>
                                                        <div class="font-size-13">
                                                            @if($user->status == 'onboarded')
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                                            @elseif($user->status == 'pending')
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>
                                                            @else
                                                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-secondary align-middle me-2"></i>
                                                            @endif
                                                            {{ ucfirst($user->status) }}
                                                        </div>
                                                    </td>
                                                   <td>
                                                    <div class="btn-group dropstart">
                                                        <button type="button" class="btn text-primary waves-effect waves-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                           <i class="mdi mdi-dots-horizontal"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#">View</a>
                                                            <a class="dropdown-item" href="#">onboard</a>
                                                            <a class="dropdown-item" href="#">reject</a>
                                                        </div>
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
                        <!-- end row -->

                        
@endsection
                
