@extends('layouts.admin')

@section('content')

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Dashboard</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Analytics</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Total Users</p>
                                                <h4 class="mb-2">{{ $totalUsers }}</h4>
                                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>9.23%</span>from previous period</p>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-primary rounded-3">
                                                    <i class="ri-shopping-cart-2-line font-size-24"></i>  
                                                </span>
                                            </div>
                                        </div>                                            
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Pending Users</p>
                                                <h4 class="mb-2">{{ $pendingUsers }}</h4>
                                                <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>1.09%</span>from previous period</p>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-success rounded-3">
                                                    <i class="mdi mdi-currency-usd font-size-24"></i>  
                                                </span>
                                            </div>
                                        </div>                                              
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Onboarded Users</p>
                                                <h4 class="mb-2">{{ $approvedUsers }}</h4>
                                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>16.2%</span>from previous period</p>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-primary rounded-3">
                                                    <i class="ri-user-3-line font-size-24"></i>  
                                                </span>
                                            </div>
                                        </div>                                              
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-truncate font-size-14 mb-2">Unique Visitors</p>
                                                <h4 class="mb-2">29670</h4>
                                                <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>11.7%</span>from previous period</p>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-light text-success rounded-3">
                                                    <i class="mdi mdi-currency-btc font-size-24"></i>  
                                                </span>
                                            </div>
                                        </div>                                              
                                    </div><!-- end cardbody -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div><!-- end row -->

                     
    
                       
                    </div>
                    
                </div>
                <!-- End Page-content -->








<div class="container">    
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Add User</a>
    <a href="{{ route('admin.users.summary') }}" class="btn btn-info mb-3">View Summary</a>
<!-- 
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        @if ($user->status === 'pending')
                            <form action="{{ route('admin.users.onboard', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Onboard</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.users.notify', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="text" name="message" placeholder="Enter notification message" required>
                            <button type="submit" class="btn btn-warning">Notify</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> -->
</div>




                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Users Table</h4>
                                        <p class="card-title-desc">                                           
                                        </p>

                                        <table id="key-datatable" class="table dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                     <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Gender</th>
                                                    <th>Age</th>
                                                    <th>LGA</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>                                                   
                                                </tr>
                                            </thead>
                                        
                                            <tbody>
                                                @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->profile?->phone ?? 'N/A' }}</td> 
                                                    <td>{{ $user->profile?->gender ?? 'N/A' }}</td>
                                                    <td>{{ $user->profile?->dob ? \Carbon\Carbon::parse($user->profile->dob)->age : 'N/A' }}</td>
                                                    <td>{{ $user->profile?->lga ?? 'N/A' }}</td>
                                                    <!-- <td>{{ $user->profile?->address ?? 'N/A' }}</td> -->
                                                    <td>{{ $user->role }}</td>
                                                    <td>{{ $user->status }}</td>
                                                    <td>
                                                        @if ($user->status === 'pending')
                                                            <form action="{{ route('admin.users.onboard', $user) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success">Onboard</button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('admin.users.notify', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <!-- <input type="text" name="message" placeholder="Enter notification message" required>
                                                            <button type="submit" class="btn btn-warning">Notify</button> -->
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div>
                        <!-- end row-->






               
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script> document.write(new Date().getFullYear()) </script> © Benue State Integrated Agricultural Assets Management System.
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
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
       
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

@endsection