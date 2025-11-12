<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Super Admin | {{ 'Benue State Smart Agricultural System and Data Management' }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Benue State Smart Agricultural System and Data Management" name="description" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="BDIC Team" name="author" />
        <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">
        
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
        
        @stack('styles')
        
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
            /* Form Styles */
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
            h4 {
                margin-bottom: 1rem;
            }
            /* Active menu item styling */
            #sidebar-menu .mm-active > a {
                color: #556ee6 !important;
                background-color: rgba(85, 110, 230, 0.1);
            }
            #sidebar-menu .mm-active > a i {
                color: #556ee6 !important;
            }
            /* Submenu Styles */
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
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="{{ route('super_admin.dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                </span>
                            </a>
                            <a href="{{ route('super_admin.dashboard') }}" class="logo logo-light">
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
                                <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                                <a class="dropdown-item" href="#"><i class="ri-settings-3-line align-middle me-1"></i> Settings</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" id="logout-link-header">
                                    <i class="ri-logout-box-r-line align-middle me-1 text-danger"></i> Logout
                                </a>
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
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Navigation</li>

                            <!-- Dashboard -->
                            <li class="{{ request()->routeIs('super_admin.dashboard') ? 'mm-active' : '' }}">
                                <a href="{{ route('super_admin.dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <!-- Management Module -->
                            <li class="{{ request()->routeIs('super_admin.management.*') ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-user-settings-line"></i>
                                    <span>Management</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li class="{{ request()->routeIs('super_admin.management.users.*') ? 'mm-active' : '' }}">
                                        <a href="{{ route('super_admin.management.users.index') }}" class="submenu-item">
                                            <i class="ri-user-line me-1"></i> Users
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('super_admin.management.departments.*') ? 'mm-active' : '' }}">
                                        <a href="{{ route('super_admin.management.departments.index') }}" class="submenu-item">
                                            <i class="ri-building-line me-1"></i> Departments
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('super_admin.management.agencies.*') ? 'mm-active' : '' }}">
                                        <a href="{{ route('super_admin.management.agencies.index') }}" class="submenu-item">
                                            <i class="ri-community-line me-1"></i> Agencies
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('super_admin.management.lgas.*') ? 'mm-active' : '' }}">
                                        <a href="{{ route('super_admin.management.lgas.index') }}" class="submenu-item">
                                            <i class="ri-map-pin-line me-1"></i> LGAs
                                        </a>
                                    </li>
                                </ul>
                            </li>    
                            
                            <!-- Vendors Module -->
                            <li class="{{ request()->routeIs('super_admin.vendors.*') ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-store-2-line"></i>
                                    <span>Vendors</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li class="{{ request()->routeIs('super_admin.vendors.index') ? 'mm-active' : '' }}">
                                        <a href="{{ route('super_admin.vendors.index') }}" class="submenu-item">
                                            All Vendors
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Resources Module -->
                            <li class="{{ request()->routeIs('super_admin.resources.*') ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-database-2-line"></i>
                                    <span>Resources</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li class="{{ request()->routeIs('super_admin.resources.index') ? 'mm-active' : '' }}">
                                        <a href="{{ route('super_admin.resources.index') }}" class="submenu-item">
                                            All Resources
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('super_admin.resources.analytics') ? 'mm-active' : '' }}">
                                        <a href="{{ route('super_admin.resources.analytics') }}" class="submenu-item">
                                            Analytics
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            <!-- Analytics -->
                            <!-- @can('view_analytics')
                            <li class="{{ request()->routeIs('analytics.*') ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-line-chart-line"></i>
                                    <span>Analytics</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li class="{{ request()->routeIs('analytics.dashboard') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.dashboard') }}" class="submenu-item">
                                            <i class="ri-dashboard-3-line me-1"></i> Dashboard
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.demographics') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.demographics') }}" class="submenu-item">
                                            <i class="ri-group-line me-1"></i> Demographics
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.production') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.production') }}" class="submenu-item">
                                            <i class="ri-plant-line me-1"></i> Production
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.crops') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.crops') }}" class="submenu-item">
                                            <i class="ri-seedling-line me-1"></i> Crops
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.livestock') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.livestock') }}" class="submenu-item">
                                            <i class="ri-bear-smile-line me-1"></i> Livestock
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.cooperatives') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.cooperatives') }}" class="submenu-item">
                                            <i class="ri-team-line me-1"></i> Cooperatives
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.enrollment') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.enrollment') }}" class="submenu-item">
                                            <i class="ri-user-add-line me-1"></i> Enrollment
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.trends') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.trends') }}" class="submenu-item">
                                            <i class="ri-line-chart-line me-1"></i> Trends
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.lga_comparison') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.lga_comparison') }}" class="submenu-item">
                                            <i class="ri-pie-chart-line me-1"></i> LGA Comparison
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endcan -->

                            <!-- Advanced Analytics -->
                            <!-- @can('view_analytics')
                            <li class="{{ request()->routeIs('analytics.advanced.*') ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-bar-chart-box-line"></i>
                                    <span>Advanced Analytics</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li class="{{ request()->routeIs('analytics.advanced.index') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.advanced.index') }}" class="submenu-item">
                                            <i class="ri-filter-line me-1"></i> Custom Filters
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('analytics.advanced.predefined') ? 'mm-active' : '' }}">
                                        <a href="{{ route('analytics.advanced.predefined') }}" class="submenu-item">
                                            <i class="ri-file-list-3-line me-1"></i> Predefined Reports
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endcan -->

                            <!-- Reports -->
                            <!-- @can('export_analytics')
                            <li class="{{ request()->routeIs('analytics.export') ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-file-list-line"></i>
                                    <span>Reports</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a href="{{ route('analytics.export', ['type' => 'comprehensive']) }}" class="submenu-item">
                                            <i class="ri-file-text-line me-1"></i> Comprehensive Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('analytics.export', ['type' => 'users']) }}" class="submenu-item">
                                            <i class="ri-user-line me-1"></i> Users Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('analytics.export', ['type' => 'farmers']) }}" class="submenu-item">
                                            <i class="ri-plant-line me-1"></i> Farmers Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('analytics.export', ['type' => 'resources']) }}" class="submenu-item">
                                            <i class="ri-database-2-line me-1"></i> Resources Report
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endcan -->

                            <!-- Support System -->
                            <!-- @can('view_support_chats')
                            <li class="{{ request()->routeIs('admin.support.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('admin.support.index') }}" class="waves-effect">
                                    <i class="ri-customer-service-2-line"></i>
                                    <span>Support System</span>
                                </a>
                            </li>
                            @endcan -->

                            <!-- Audit Logs -->
                            <!-- @can('view_audit_logs')
                            <li>
                                <a href="#" class="waves-effect">
                                    <i class="ri-history-line"></i>
                                    <span>Audit Logs</span>
                                </a>
                            </li>
                            @endcan -->

                            <!-- System Settings -->
                            <!-- @can('system_settings')
                            <li>
                                <a href="#" class="waves-effect">
                                    <i class="ri-settings-3-line"></i>
                                    <span>System Settings</span>
                                </a>
                            </li>
                            @endcan -->
                                                      
                            <!-- Logout -->
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <li>
                                    <a class="text-danger waves-effect" href="#" id="logout-link">
                                        <i class="ri-logout-box-r-line align-middle me-1 text-danger"></i>
                                        <span>Logout</span>
                                    </a>
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
                    </div>
                </div>
                <!-- End Page-content -->
                
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
        <script src="{{ asset('dashboard/js/vendor-bundle.js') }}" defer></script>
        <script src="{{ asset('dashboard/js/app-bundle.js') }}" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.11.1/dist/cdn.min.js" defer></script>
        
        @stack('scripts')
        
        <script>
            // Logout functionality
            document.getElementById('logout-link').addEventListener('click', function(event) { 
                event.preventDefault();
                document.getElementById('logout-form').submit(); 
            });
            
            document.getElementById('logout-link-header').addEventListener('click', function(event) { 
                event.preventDefault();
                document.getElementById('logout-form').submit(); 
            });

            // Set active menu item based on current route
            $(document).ready(function() {
                var currentUrl = window.location.href;
                $('#sidebar-menu a').each(function() {
                    var href = $(this).attr('href');
                    if (currentUrl.indexOf(href) !== -1 && href !== 'javascript: void(0);' && href !== '#') {
                        $(this).closest('li').addClass('mm-active');
                        $(this).closest('.sub-menu').addClass('mm-show');
                        $(this).closest('.sub-menu').prev('a').addClass('mm-active');
                    }
                });
            });
        </script>
    </body>
</html>