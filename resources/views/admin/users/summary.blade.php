@extends('layouts.table')

@section('content')


                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Data Tables</h4>

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
                
