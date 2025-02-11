@extends('layouts.admin')

@section('content')

   
                        
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
                                <!-- <h4 class="mb-2">{{ $totalUsers }}</h4> -->
                                <!-- <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>9.23%</span>from previous period</p> -->
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <!-- <i class="ri-shopping-cart-2-line font-size-24"></i>   -->
                                    <h4 class="mb-2">{{ $totalUsers }}</h4
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
                                
                                <!-- <h4 class="mb-2">{{ $pendingUsers }}</h4> -->
                                <!-- <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>1.09%</span>from previous period</p> -->
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <!-- <i class="mdi mdi-currency-usd font-size-24"></i>  -->
                                    <h4 class="mb-2">{{ $pendingUsers }}</h4> 
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
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-primary rounded-3">
                                    <!-- <i class="ri-user-3-line font-size-24"></i>   -->
                                    <h4 class="mb-2">{{ $approvedUsers }}</h4>
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
                                <p class="text-truncate font-size-14 mb-2">Total Visitors</p>
                                <!-- <h4 class="mb-2">{{ $totalVisits }}</h4> -->
                                <!-- <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>11.7%</span>from previous period</p> -->
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-light text-success rounded-3">
                                    <!-- <i class="mdi mdi-currency-btc font-size-24"></i>   -->
                                    <h4 class="mb-2">{{ $totalVisits }}</h4>
                                </span>
                            </div>
                        </div>                                              
                    </div>
                </div>
            </div>
        </div><!-- end row -->


        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                
                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                
                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>

                        <h4 class="card-title mb-4">Latest Registrations</h4>

                        <div class="table-responsive">
                            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>LGA</th>
                                        <th>Status</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th style="width: 120px;">Age</th>
                                    </tr>
                                </thead><!-- end thead -->
                            <tbody>
                                @foreach ($users->sortByDesc('created_at')->take(7) as $user) 
                                    <tr>
                                        <td><h6 class="mb-0">{{ $user->name }}</h6></td>
                                        <td>{{ $user->profile?->lga ?? 'N/A' }}</td>
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
                                        <td>{{ $user->profile?->phone ?? 'N/A' }}</td>
                                        <td>{{ $user->profile?->gender ?? 'N/A' }}</td>
                                        <td>{{ $user->profile?->dob ? \Carbon\Carbon::parse($user->profile->dob)->age : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table> <!-- end table -->
                        </div>
                    </div><!-- end card -->
                </div><!-- end card -->
            </div>
<!-- end col -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <select class="form-select shadow-none form-select-sm">
                                <option selected>Apr</option>
                                <option value="1">Mar</option>
                                <option value="2">Feb</option>
                                <option value="3">Jan</option>
                            </select>
                        </div>
                        <h4 class="card-title mb-4">Chart tiltle</h4>
                        
                        <div class="row">
                            <div class="col-4">
                                <div class="text-center mt-4">
                                    <h5>3475</h5>
                                    <p class="mb-2 text-truncate">item 1</p>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-4">
                                <div class="text-center mt-4">
                                    <h5>10458</h5>
                                    <p class="mb-2 text-truncate">item 2</p>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-4">
                                <div class="text-center mt-4">
                                    <h5>9062</h5>
                                    <p class="mb-2 text-truncate">item 3</p>
                                </div>
                            </div>
                            <!-- end col -->
                                
                        </div>
                        <!-- end row -->

                        <div class="mt-4">
                            <div id="donut-chart" class="apex-charts"></div>
                        </div>
                    </div>
                </div><!-- end card -->
            </div><!-- end col -->
        </div>

@endsection