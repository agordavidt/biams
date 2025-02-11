<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Benue State Integrated Agricultural Assets Data Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
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

    </head>

    <body data-topbar="dark" data-layout="horizontal">

        <!-- Begin page -->
        <div id="layout-wrapper">

            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/images/logo-sm.png') }}" alt="logo-sm" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/images/logo-dark.png') }}" alt="logo-dark" height="20">
                                </span>
                            </a>

                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/images/logo-sm.png') }}" alt="logo-sm-light" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/images/logo-light.png') }}" alt="logo-light" height="20">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>

                        
                    </div>

                    <div class="d-flex">

                        
                        <div class="dropdown d-none d-lg-inline-block ms-1">
                            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                                <i class="ri-fullscreen-line"></i>
                            </button>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                                  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-notification-3-line"></i>
                                <span class="noti-dot"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0"> Notifications </h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#!" class="small"> View All</a>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    <a href="" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <h6 class="mb-1">Status Updated</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">Your registration is approved</p>
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>                                   
                                 
                                </div>
                                <div class="p-2 border-top">
                                    <div class="d-grid">
                                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                            <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                                
                                <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="{{ route('profile.update') }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>                               
                                <!-- <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a> -->
                                <div class="dropdown-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form"> 
                                        @csrf 
                                        <li> 
                                            <!-- <a class="text-danger" href="#" id="logout-link"> <i class="ri-shut-down-line align-middle me-1 text-danger"></i> <span>Logout</span> </a> -->
                                            <a class="dropdown-item text-danger" href="#"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a> 
                                        </li> 
                                    </form>
                                </div>
                        </div>

                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                                
                            </button>
                        </div>
            
                    </div>
                </div>
            </header>
    
                <div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    
                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('home') }}">
                                            <i class="ri-dashboard-line me-2"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('farmers.submissions') }}">
                                        <i class="ri-stack-line me-2"></i> Practices 
                                        </a>
                                    </li>
                                   
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('user.resources.index') }}">
                                        <i class="ri-apps-2-line me-2"></i> Resources 
                                        </a>
                                    </li> 
                                    <li class="nav-item">
                                        <a class="nav-link" href="index.html">
                                        <i class="ri-pencil-ruler-2-line me-2"></i> Market  
                                        </a>
                                    </li> 
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

       
            <div class="main-content">

                <div class="page-content">

                    <div class="container-fluid">


                    @yield('content')

                    </div>
                    
                </div>
                <!-- End Page-content -->
               
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © Benue State Integrated Agricultural Data Assets Management System.
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

       

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

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

        <script src="{{ asset('dashboard/js/app.js') }}"></script>

    </body>
</html>
