<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Governor | Benue State Smart Agricultural System and Data Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Benue State Smart Agricultural System and Data Management" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="BDIC Team" name="author" />

    <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">

    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    @stack('styles')

    <style>
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        #sidebar-menu .mm-active > a {
            color: #556ee6 !important;
            background-color: rgba(85, 110, 230, 0.1);
        }
    </style>
</head>

<body data-topbar="dark">

    <div id="layout-wrapper">

        <header id="page-topbar" style="background: #38761D;">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box">
                        <a href="{{ route('governor.dashboard') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Logo" height="40">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Logo" height="40">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                        <i class="ri-menu-2-line align-middle"></i>
                    </button>
                </div>

                <div class="d-flex">
                    <div class="dropdown d-inline-block user-dropdown">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" id="logout-link">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Navigation</li>

                        <li class="{{ request()->routeIs('governor.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('governor.dashboard') }}" class="waves-effect">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('governor.overview*') ? 'mm-active' : '' }}">
                            <a href="{{ route('governor.overview') }}" class="waves-effect">
                                <i class="ri-pie-chart-line"></i>
                                <span>System Overview</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('governor.farmers*') ? 'mm-active' : '' }}">
                            <a href="{{ route('governor.farmers') }}" class="waves-effect">
                                <i class="ri-group-line"></i>
                                <span>Farmer Analytics</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('governor.production*') ? 'mm-active' : '' }}">
                            <a href="{{ route('governor.production') }}" class="waves-effect">
                                <i class="ri-plant-line"></i>
                                <span>Production Analytics</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('governor.lgas*') ? 'mm-active' : '' }}">
                            <a href="{{ route('governor.lgas') }}" class="waves-effect">
                                <i class="ri-map-pin-line"></i>
                                <span>LGA Analytics</span>
                            </a>
                        </li>                     

                        <li class="{{ request()->routeIs('governor.resources.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('governor.resources.index') }}" class="waves-effect">
                                <i class="ri-database-2-line"></i>
                                <span>Resources</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('governor.vendors.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('governor.vendors.index') }}" class="waves-effect">
                                <i class="ri-store-2-line"></i>
                                <span>Vendors</span>
                            </a>
                        </li>

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
            </div>
        </div>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Benue State Smart Agricultural System and Data Management.
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
    <script src="{{ asset('dashboard/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @stack('scripts')

    <script>
        document.getElementById('logout-link').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        });
        
        document.getElementById('logout-link-sidebar').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        });
    </script>
</body>

</html>