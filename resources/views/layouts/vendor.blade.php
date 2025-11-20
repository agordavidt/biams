<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Vendor Portal | Benue State Smart Agricultural System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Benue State Smart Agricultural System and Data Management" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="BDIC Team" name="author" />

    <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">
    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body data-topbar="dark">
    <div id="layout-wrapper">
        <!-- Header -->
        <header id="page-topbar" style="background: #38761D;">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box">
                        <a href="#" class="logo logo-light">
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
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Menu</li>

                        @if(auth()->user()->hasRole('Vendor Manager'))
                        <!-- Vendor Manager Menu -->
                        <li>
                            <a href="{{ route('vendor.dashboard') }}" class="waves-effect">
                                <i class="ri-dashboard-line me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('vendor.team.index') }}" class="waves-effect">
                                <i class="ri-team-line me-2"></i>
                                <span>Team</span>
                            </a>
                        </li>                                          
                        <li class="nav-item">
                            <a href="{{ route('vendor.resources.index') }}" class="nav-link">
                                  <i class="ri-file-list-3-line me-2"></i>Resources
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.resources.all-applications') }}" class="nav-link">
                                <i class="ri-file-list-3-line"></i> Applications
                                
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('vendor.analytics') }}" class="waves-effect">
                                <i class="ri-bar-chart-line me-2"></i>
                                <span>Analytics</span>
                            </a>
                        </li>

                        <!-- <li>
                            <a href="{{ route('vendor.payouts') }}" class="waves-effect">
                                <i class="ri-wallet-3-line me-2"></i>
                                <span>Payouts</span>
                            </a>
                        </li> -->
                         <li>
                            <a href="{{ route('vendor.profile') }}" class="waves-effect">
                                <i class="ri-account-circle-line"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->hasRole('Distribution Agent'))
                        <!-- Distribution Agent Menu -->
                        <li>
                            <a href="{{ route('vendor.distribution.dashboard') }}" class="waves-effect">
                                <i class="ri-dashboard-line me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <!-- <li>
                            <a href="{{ route('vendor.distribution.search') }}" class="waves-effect">
                                <i class="ri-search-line me-2"></i>
                                <span>Search Farmer</span>
                            </a>
                        </li> -->

                        <li>
                            <a href="{{ route('vendor.distribution.resources') }}" class="waves-effect">
                                <i class="ri-list-check me-2"></i>
                                <span>Assigned Resources</span>
                            </a>
                        </li>
                         <li>
                            <a href="{{ route('vendor.distribution.profile') }}" class="waves-effect">
                                <i class="ri-account-circle-line"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <li>
                                <a class="text-danger" href="#" id="logout-link">
                                    <i class="ri-shut-down-line align-middle me-1 text-danger"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-checkbox-circle-line me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i>
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
                            <script>document.write(new Date().getFullYear())</script> © Benue State Smart Agricultural System.
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
    <script src="{{ asset('dashboard/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('logout-link').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>