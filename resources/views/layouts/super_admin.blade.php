@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">

        <head>
            <meta charset="utf-8" />
            <title>Super Admin | {{ Setting::get('site_title', 'Benue State Integrated Agricultural Data Assets Management System') }}</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta content="Benue State Integrated Agricultural Data Assets Management System" name="description" />
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <meta content="BDIC Team" name="author" />

            <link rel="shortcut icon" href="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/favicon.ico') }}">

             <!-- Preload critical assets -->
            <link rel="preload" href="{{ asset('dashboard/css/bootstrap.min.css') }}" as="style">
            <link rel="preload" href="{{ asset('dashboard/js/app.js') }}" as="script">

            <!-- Consolidated CSS bundles -->
            <link href="{{ asset('dashboard/css/vendor-bundle.css') }}" rel="stylesheet">
            <link href="{{ asset('dashboard/css/app-bundle.css') }}" rel="stylesheet">

            <link href="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('dashboard/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('dashboard/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('dashboard/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('dashboard/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
            <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
            <link href="{{ asset('dashboard/libs/magnific-popup/magnific-popup.css') }}" rel="stylesheet" type="text/css" />
            <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

            <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

            <style>
             .status-badge {
                padding: 0.25rem 0.5rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 500;
            }
            .status-pending {
                background-color: #fef3c7;
                color: #92400e;
            }
            .status-approved {
                background-color: #dcfce7;
                color: #166534;
            }
            .status-rejected {
                background-color: #fee2e2;
                color: #991b1b;
            }
            #map {
                height: 500px;
                width: 100%;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
            }
            .action-btn {
                padding: 0.25rem;
                border-radius: 0.375rem;
                border: none;
                background: transparent;
            }
            .action-btn:hover {
                background-color: #f3f4f6;
            }
            .approve-btn:hover {
                color: #059669;
                background-color: #ecfdf5;
            }
            .reject-btn:hover {
                color: #dc2626;
                background-color: #fef2f2;
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
                            <a href="#" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                {{-- <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light"> --}}
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                {{-- <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light"> --}}
                                </span>
                            </a>

                            <a href="#" class="logo logo-light">
                                <span class="logo-sm">

                                    <img src="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                {{-- <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light"> --}}
                                </span>
                                <span class="logo-lg">
                                    
                                    <img src="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                {{-- <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light"> --}}

                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>

                        <!-- App Search-->
                        <form class="app-search d-none d-lg-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="ri-search-line"></span>
                            </div>
                        </form>

                      
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
                          
                        </div>                        
            
                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">
                   

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ route('super_admin.dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('super_admin.users') }}" class="waves-effect">
                                    <i class="ri-user-line"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('super_admin.analytics') }}" class="waves-effect">
                                    <i class="ri-bar-chart-line"></i>
                                    <span>Analytics</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('super_admin.reports') }}" class="waves-effect">
                                    <i class="ri-file-list-line"></i>
                                    <span>Report</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('super_admin.settings') }}" class="waves-effect">
                                    <i class="ri-settings-3-line"></i>
                                    <span>Settings</span>
                                </a>
                               
                            </li>                           

                            <li>
                                <a href="{{ route('super_admin.content') }}" class="waves-effect">
                                    <i class="ri-image-edit-line"></i>
                                    <span>Content Management</span>
                                </a>
                            </li>

                            <li>
                                <a href="#" class="waves-effect">
                                    <i class="ri-shield-keyhole-line"></i>
                                    <span>Security</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a href="{{ route('super_admin.login_logs') }}" class="waves-effect">
                                            <i class="ri-login-circle-line"></i>
                                            <span>Login Logs</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('super_admin.activity_logs') }}" class="waves-effect">
                                            <i class="ri-file-list-line"></i>
                                            <span>Activity Logs</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('super_admin.audit_logs') }}" class="waves-effect">
                                            <i class="ri-shield-check-line"></i>
                                            <span>Audit Logs</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                           

                            <li>
                                <a href="#" class="waves-effect">
                                    <i class="ri-file-search-line"></i>
                                    <span>Audit</span>
                                </a>
                            </li>
                           

                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <li>
                                    <a class="text-danger" href="#" id="logout-link"> <i class="ri-logout-box-r-line align-middle me-1 text-danger"></i> <span>Logout</span> </a>
                                </li>
                            </form>
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->
              <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                    @yield('content')

                <!-- container-fluid -->   
                    </div> 
                </div>
                <!-- End Page-content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © {{ Setting::get('site_title', 'Benue State Integrated Agricultural Data Assets Management System') }}.
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

        <script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/node-waves/waves.min.js') }}"></script>

        <script src="{{ asset('dashboard/libs/apexcharts/apexcharts.min.js') }}"></script>

        <script src="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

        <script src="{{ asset('dashboard/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/pdfmake/build/vfs_fonts.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>

        <script src="{{ asset('dashboard/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('dashboard/js/pages/datatables.init.js') }}"></script>

        <script src="{{ asset('dashboard/js/app.js') }}"></script>

        <script src="{{ asset('dashboard/libs/magnific-popup/jquery.magnific-popup.min.js') }}"></script>

        <script src="{{ asset('dashboard/js/pages/lightbox.init.js') }}"></script>
        <!-- Optimized Script Loading -->
        <script src="{{ asset('dashboard/js/vendor-bundle.js') }}" defer></script>
        <script src="{{ asset('dashboard/js/app-bundle.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <!-- Add Alpine.js for form interactions -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.11.1/dist/cdn.min.js" defer></script>

        @stack('scripts')

        <script> 
        document.getElementById('logout-link').addEventListener('click', function(event) { event.preventDefault(); 
            document.getElementById('logout-form').submit(); }); 
        </script>
    </body>

</html>


