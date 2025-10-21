<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Commissioner | Benue State Smart Agricultural System and Data Management</title>
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
        .action-btn {
            padding: 0.25rem;
            border-radius: 0.375rem;
            border: none;
            background: transparent;
        }
        .action-btn:hover {
            background-color: #f3f4f6;
        }
        /* Active menu item styling */
        #sidebar-menu .mm-active > a {
            color: #556ee6 !important;
            background-color: rgba(85, 110, 230, 0.1);
        }
        #sidebar-menu .mm-active > a i {
            color: #556ee6 !important;
        }
        .nav-header {
            background: linear-gradient(135deg, #38761D 0%, #2E5C16 100%);
            color: white;
        }
    </style>
</head>

<body data-topbar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar" class="nav-header">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="{{ route('commissioner.dashboard') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                            </span>
                        </a>

                        <a href="{{ route('commissioner.dashboard') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect text-white" id="vertical-menu-btn">
                        <i class="ri-menu-2-line align-middle"></i>
                    </button>

                    <!-- App Search-->
                    <form class="app-search d-none d-lg-block">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Search reports...">
                            <span class="ri-search-line text-white"></span>
                        </div>
                    </form>
                </div>

                <div class="d-flex">
                    <div class="dropdown d-inline-block d-lg-none ms-2">
                        <button type="button" class="btn header-item noti-icon waves-effect text-white" id="page-header-search-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-search-line"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-search-dropdown">
                            <form class="p-3">
                                <div class="mb-3 m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search reports...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block user-dropdown">
                        <button type="button" class="btn header-item waves-effect text-white" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-user-3-line align-middle me-1"></i>
                            <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Commissioner Portal</h6>
                            <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" id="logout-link">
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
                        <li class="menu-title">Commissioner Navigation</li>

                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('commissioner.dashboard') }}" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Executive Dashboard</span>
                            </a>
                        </li>

                        <!-- Policy & Analytics -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-bar-chart-box-line"></i>
                                <span>Policy & Analytics</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('commissioner.policy_insights.index') }}">Policy Insights</a></li>
                                <li><a href="{{ route('commissioner.policy_insights.demographic_analysis') }}">Demographic Analysis</a></li>
                                <li><a href="{{ route('commissioner.policy_insights.youth_engagement') }}">Youth Engagement</a></li>
                                <li><a href="{{ route('commissioner.policy_insights.yield_projections') }}">Yield Projections</a></li>
                                <li><a href="{{ route('commissioner.policy_insights.production_patterns') }}">Production Patterns</a></li>
                            </ul>
                        </li>

                        <!-- Trend Analysis -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-line-chart-line"></i>
                                <span>Trend Analysis</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('commissioner.trends.index') }}">Trends Overview</a></li>
                                <li><a href="{{ route('commissioner.trends.enrollment_trends') }}">Enrollment Trends</a></li>
                                <li><a href="{{ route('commissioner.trends.production_trends') }}">Production Trends</a></li>
                                <li><a href="{{ route('commissioner.trends.resource_utilization_trends') }}">Resource Utilization</a></li>
                                <li><a href="{{ route('commissioner.trends.gender_parity_trends') }}">Gender Parity</a></li>
                            </ul>
                        </li>

                        <!-- LGA Comparison -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-map-2-line"></i>
                                <span>Regional Analysis</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('commissioner.lga_comparison.index') }}">LGA Comparison</a></li>
                                <li><a href="{{ route('commissioner.lga_comparison.performance_ranking') }}">Performance Ranking</a></li>
                                <li><a href="{{ route('commissioner.lga_comparison.capacity_analysis') }}">Capacity Analysis</a></li>
                                <li><a href="{{ route('commissioner.lga_comparison.compare_lgas') }}">Compare LGAs</a></li>
                                <li><a href="{{ route('commissioner.lga_comparison.geographic_analysis') }}">Geographic Analysis</a></li>
                            </ul>
                        </li>

                        <!-- Intervention Tracking -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="ri-heart-pulse-line"></i>
                                <span>Intervention Tracking</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('commissioner.interventions.index') }}">Interventions Overview</a></li>
                                <li><a href="{{ route('commissioner.interventions.beneficiary_report') }}">Beneficiary Reports</a></li>
                                <li><a href="{{ route('commissioner.interventions.partner_activities') }}">Partner Activities</a></li>
                                <li><a href="{{ route('commissioner.interventions.coverage_analysis') }}">Coverage Analysis</a></li>
                            </ul>
                        </li>

                        <!-- Reports & Exports -->
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ri-file-chart-line"></i>
                                <span>Reports & Exports</span>
                            </a>
                        </li>

                        <!-- System Administration -->
                        <li class="menu-title mt-2">Administration</li>

                        <!-- Logout -->
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <li>
                                <a class="text-danger waves-effect" href="#" id="logout-link-sidebar">
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
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Benue State Smart Agricultural System and Data Management.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Commissioner Portal | Powered by BDIC
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
        // Logout functionality
        document.getElementById('logout-link').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        });
        
        document.getElementById('logout-link-sidebar').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        });

        // Set active menu item based on current route
        $(document).ready(function() {
            var currentUrl = window.location.href;
            $('#sidebar-menu a').each(function() {
                var href = $(this).attr('href');
                if (currentUrl.indexOf(href) !== -1 && href !== 'javascript: void(0);') {
                    $(this).closest('li').addClass('mm-active');
                    $(this).closest('.sub-menu').addClass('mm-show');
                    $(this).closest('.sub-menu').prev('a').addClass('mm-active');
                }
            });
        });
    </script>
</body>

</html>