<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Divisioanl Agriculture Officers | {{ 'Benue State Smart Agricultural System and Data Management' }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Benue State Smart Agricultural System and Data Management" name="description" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="BDIC Team" name="author" />
        <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">
        <link rel="preload" href="{{ asset('dashboard/css/bootstrap.min.css') }}" as="style">
        <link rel="preload" href="{{ asset('dashboard/js/app.js') }}" as="script">
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
            .status-onboarded {
                background-color: #dcfce7;
                color: #166534;
            }
            .status-rejected {
                background-color: #fee2e2;
                color: #991b1b;
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
            .edit-btn:hover {
                color: #2563eb;
                background-color: #eff6ff;
            }
            .delete-btn:hover {
                color: #dc2626;
                background-color: #fef2f2;
            }
            .form-control, select {
                margin-bottom: 1rem;
                padding: 0.5rem;
                border-radius: 0.375rem;
                border: 1px solid #e5e7eb;
                width: 100%;
            }
            button[type="submit"] {
                background-color: #1e40af;
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                border: none;
                cursor: pointer;
            }
            button[type="submit"]:hover {
                background-color: #1e3a8a;
            }
            .submenu-item {
                padding-left: 2.5rem !important;
                font-size: 0.875rem;
            }
            .submenu-item.active {
                background-color: rgba(59, 130, 246, 0.1);
                color: #1e40af;
            }
        </style>
    </head>
    <body data-topbar="dark">
        <div id="layout-wrapper">
            <header id="page-topbar" style="background: #38761D;">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            <a href="#" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                </span>
                            </a>
                            <a href="#" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                </span>
                            </a>
                        </div>
                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>
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
                                <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Core</li>

                        <li>
                            <a href="{{ route('lga_admin.dashboard') }}" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>                      


                        <li>
                            <a href="{{ route('lga_admin.farmers.index') }}" class="waves-effect">
                                <i class="ri-list-check-2"></i>
                                <span>Review Submissions</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('lga_admin.farmers.index') }}" class="has-arrow waves-effect">
                                <i class="ri-user-line"></i>
                                <span>Farmer Management</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                {{-- The main index page now serves the primary review function --}}
                                <li><a href="{{ route('lga_admin.farmers.index') }}" class="submenu-item">Pending Review</a></li>
                                {{-- Placeholder for all farmers (active/approved) in the LGA --}}
                                <li><a href="#" class="submenu-item">View All Farmers</a></li> 
                            </ul>
                        </li>
                         <li>
                            <a href="{{ route('lga_admin.cooperatives.index') }}" class="waves-effect">
                                <i class="ri-customer-service-2-line"></i>
                                <span>Corporatives</span>
                            </a>
                        </li>                      

                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-team-line"></i>
                                <span>Enrollment Agents</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('lga_admin.agents.index') }}" class="submenu-item">Manage Agents</a></li>
                                <li><a href="{{ route('lga_admin.agents.create') }}" class="submenu-item">Create New Agent</a></li>
                            </ul>
                        </li>
                        <!-- <li>
                            <a href="{{ route('lga_admin.support.index') }}" class="waves-effect">
                                <i class="ri-customer-service-2-line"></i>
                                <span>Support Queue</span>
                            </a>
                        </li> -->
                         <li>
                            <a href="{{ route('lga_admin.profile') }}" class="waves-effect">
                                <i class="ri-account-circle-line"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                       
                        {{-- Logout Functionality --}}
                       
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <li>
                                <a class="text-danger" href="#" id="logout-link"> 
                                    <i class="ri-logout-box-r-line align-middle me-1 text-danger"></i> 
                                    <span>Logout</span> 
                                </a>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>

<script>
    // Required to handle the POST request for logout
    document.getElementById('logout-link').addEventListener('click', function (event) {
        event.preventDefault();
        document.getElementById('logout-form').submit();
    });
</script>
            
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="ri-check-line me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @yield('content')
                    </div>
                </div>
                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © {{ 'Benue State Smart Agricultural System and Data Management' }}.
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
        </div>
        
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
        <script src="{{ asset('dashboard/js/vendor-bundle.js') }}" defer></script>
        <script src="{{ asset('dashboard/js/app-bundle.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.11.1/dist/cdn.min.js" defer></script>
        @stack('scripts')
        <script>
            document.getElementById('logout-link').addEventListener('click', function(event) { 
                event.preventDefault();
                document.getElementById('logout-form').submit(); 
            });
        </script>
    </body>
</html>