<!DOCTYPE html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Benue State Integrated Agricultural Assets Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">      
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">

        <!-- jquery.vectormap css -->
        <link href="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

        <!-- DataTables -->
        <link href="{{ asset('dashboard/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset('dashboard/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />  

        <!-- Bootstrap Css -->
        <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />


        <!--- new stuff ----->
         <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BSAMS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .practice-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .practice-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .status-tracker {
            position: relative;
            padding: 20px;
            margin-bottom: 30px;
        }

        .status-step {
            position: relative;
            padding-bottom: 20px;
        }

        .status-step::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 30px;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .status-step:last-child::before {
            display: none;
        }

        .status-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .status-icon.completed {
            background: #28a745;
            color: white;
        }

        .status-icon.pending {
            background: #ffc107;
            color: white;
        }

        .status-icon.current {
            background: #007bff;
            color: white;
        }

        .modal-xl {
            max-width: 95%;
        }
    </style>
        

    </head>

    <body data-topbar="dark">
    
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm">
                                    <i class="fas fa-leaf me-2"></i>BIAAMS
                                </span>
                                <span class="logo-lg">
                                    <i class="fas fa-leaf me-2"></i>BIAAMS
                                </span>
                            </a>
                                <!-- <a class="navbar-brand" href="#"><i class="fas fa-leaf me-2"></i>BIAAMS</a> -->
                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <i class="fas fa-leaf me-2"></i>BIAAMS
                                </span>
                                <span class="logo-lg">
                                    <i class="fas fa-leaf me-2"></i>BIAAMS
                                </span>
                            </a>
                        </div>

                        <!-- <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button> -->

                        <!-- App Search-->
                        <form class="app-search d-none d-lg-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="ri-search-line"></span>
                            </div>
                        </form>

                        <!-- <div class="dropdown dropdown-mega d-none d-lg-block ms-2">
                           
                           
                        </div> -->
                    </div>

                    <div class="d-flex">

                        <div class="dropdown d-inline-block d-lg-none ms-2">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-search-line"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-search-dropdown">
                    
                                <form class="p-3">
                                    <div class="mb-3 m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ...">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    

                        

                       <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg"
                                    alt="Header Avatar"> -->
                                <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                                <!-- <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i> -->
                            </button>
                             <div class="dropdown d-inline-block">
                           
                            </div>
                          
                        </div>                        
            
                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">
                   

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ route('home') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('farmers.crop') }}" class=" waves-effect">
                                    <i class="ri-calendar-2-line"></i>
                                    <span>Registration</span>
                                </a>
                            </li>
                             @if (auth()->user()->status === 'approved')
                             <li>
                                <a href="#" class="has-arrow waves-effect">
                                    <i class="ri-account-circle-line"></i>
                                    <span>Resources</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="#">resource 1</a></li>
                                    <li><a href="#">resource 2</a></li>
                                    <li><a href="#">resource 3</a></li>
                                    <li><a href="#">resource 4</a></li>
                                </ul>
                            </li>

                             <li>
                                <a href="#" class="has-arrow waves-effect">
                                    <i class="ri-account-circle-line"></i>
                                    <span>Trainings</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="#">training 1</a></li>
                                    <li><a href="#">training 2</a></li>
                                    <li><a href="#">training 3</a></li>
                                    <li><a href="#">training 4</a></li>
                                </ul>
                            </li>
                            @endif
                            <!-- <li class="menu-title">Pages</li>                          
                           
                            <li class="menu-title">Components</li> -->

                             <li> <a href="{{ route('profile.update') }}"><i class="ri-user-line align-middle me-1"></i><span>Profile</span></a></li>
                             <form action="{{ route('logout') }}" method="POST" id="logout-form"> 
                                @csrf 
                                <li> 
                                    <a class="text-danger" href="#" id="logout-link"> <i class="ri-shut-down-line align-middle me-1 text-danger"></i> <span>Logout</span> </a> 
                                </li> 
                            </form>
                             
                            
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->
             <div>
                     @yield('content')
             </div>          




             <!-- JAVASCRIPT -->
        <script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/node-waves/waves.min.js') }}"></script>

        
        <!-- apexcharts -->
        <script src="{{ asset('dashboard/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- jquery.vectormap map -->
        <script src="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('dashboard/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        
        <!-- Responsive examples -->
        <script src="{{ asset('dashboard/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('dashboard/js/pages/dashboard.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('dashboard/js/app.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script> 
        document.getElementById('logout-link').addEventListener('click', function(event) { event.preventDefault(); 
            document.getElementById('logout-form').submit(); }); 



          

        </script>
    </body>

</html>


