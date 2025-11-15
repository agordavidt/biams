<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Enrollment Agent | Benue State Smart Agricultural System and Data Management</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Benue State Smart Agricultural System and Data Management" name="description" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="BDIC Team" name="author" />
        <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">
        
        <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
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
            .status-verified {
                background-color: #dcfce7;
                color: #166534;
            }
            .status-unverified {
                background-color: #fee2e2;
                color: #991b1b;
            }
            .status-approved {
                background-color: #dbeafe;
                color: #1e40af;
            }
            .status-rejected {
                background-color: #ffe4e6;
                color: #be123c;
            }
        </style>
    </head>
    <body data-topbar="dark">
        <div id="layout-wrapper">
            <header id="page-topbar" style="background: #38761D;">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            <a href="{{ route('enrollment.dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Site Logo" height="40">
                                </span>
                            </a>
                            <a href="{{ route('enrollment.dashboard') }}" class="logo logo-light">
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
                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                                <i class="ri-arrow-down-s-line d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('enrollment.profile') }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" id="logout-link">
                                    <i class="ri-logout-box-r-line align-middle me-1 text-danger"></i> Logout
                                </a>
                                {{-- Hidden form for logout --}}
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <div class="vertical-menu">
                <div data-simplebar class="h-100">
                    <div id="sidebar-menu">
                        <ul class="metismenu list-unstyled" id="side-menu">
                            
                            <li>
                                <a href="{{ route('enrollment.dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{ route('enrollment.farmers.index') }}" class="waves-effect">
                                    <i class="ri-file-list-3-line"></i>
                                    <span>My Enrollments</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('enrollment.farmers.create') }}" class="waves-effect">
                                    <i class="ri-user-add-line"></i>
                                    <span>New Enrollment</span>
                                </a>
                            </li>
                             <li>
                            <a href="{{ route('enrollment.profile') }}" class="waves-effect">
                                <i class="ri-account-circle-line"></i>
                                <span>Profile</span>
                            </a>
                        </li>

                        </ul>
                    </div>
                </div>
            </div>
            
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
        <script src="{{ asset('dashboard/js/app.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        @stack('scripts')
        
        <script>
            document.getElementById('logout-link').addEventListener('click', function(event) { 
                event.preventDefault();
                document.getElementById('logout-form').submit(); 
            });
        </script>
    </body>
</html>