<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Benue State Smart Agricultural System and Data Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Benue State Integrated Agricultural Assets Data Management System" name="description" />
    <meta content="BDIC Team" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">

    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <link href="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .topnav .navbar-nav .nav-item.dropdown .dropdown-menu {
            margin-top: 0;
        }
        .badge-notification {
            position: relative;
            top: -2px;
        }
        @media (max-width: 991.98px) {
            .topnav .navbar-nav .dropdown-menu {
                background-color: #f8f9fa;
                border: none;
                padding-left: 20px;
            }
        }
    </style>

    @stack('styles')
</head>

<body data-topbar="dark" data-layout="horizontal">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Header -->
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="{{ route('farmer.dashboard') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('dashboard/images/logo-sm.png') }}" alt="logo-sm" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                            </span>
                        </a>

                        <a href="{{ route('farmer.dashboard') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('dashboard/images/logo-sm.png') }}" alt="logo-sm-light" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" 
                            data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="ri-menu-2-line align-middle"></i>
                    </button>
                </div>

                <div class="d-flex">
                    <!-- Fullscreen Toggle -->
                    <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="ri-fullscreen-line"></i>
                        </button>
                    </div>

                    <!-- User Avatar Dropdown -->
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('farmer.profile') }}">
                                <i class="ri-user-line align-middle me-1"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Top Navigation Menu -->
        <div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('farmer.dashboard') }}">
                                    <i class="ri-dashboard-line me-2"></i> Dashboard
                                </a>
                            </li>

                            <!-- Profile -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('farmer.profile') ? 'active' : '' }}" 
                                   href="{{ route('farmer.profile') }}">
                                    <i class="ri-user-line me-2"></i> Profile
                                </a>
                            </li>

                            <!-- Resources -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('farmer.resources.*') ? 'active' : '' }}" 
                                   href="{{ route('farmer.resources.index') }}">
                                    <i class="ri-apps-2-line me-2"></i> Resources
                                </a>
                            </li>

                            <!-- Marketplace Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none {{ request()->routeIs('farmer.marketplace.*') || request()->routeIs('marketplace.*') ? 'active' : '' }}" 
                                   href="#" id="marketplaceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-store-2-line me-2"></i> Marketplace <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="marketplaceDropdown">
                                    <!-- <a href="{{ route('marketplace.index') }}" class="dropdown-item" target="_blank">
                                        <i class="ri-search-line me-2"></i>Browse Marketplace
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">My Marketplace</h6> -->
                                    <a href="{{ route('farmer.marketplace.my-listings') }}" class="dropdown-item">
                                        <i class="ri-file-list-3-line me-2"></i>My Listings
                                        @php
                                            $myListingsCount = \App\Models\Market\MarketplaceListing::where('user_id', auth()->id())->count();
                                        @endphp
                                        @if($myListingsCount > 0)
                                            <span class="badge bg-primary badge-notification ms-1">{{ $myListingsCount }}</span>
                                        @endif
                                    </a>
                                    
                                    @php
                                        $hasActiveSubscription = auth()->user()->hasActiveMarketplaceSubscription();
                                    @endphp
                                    
                                    @if($hasActiveSubscription)
                                        <a href="{{ route('farmer.marketplace.create') }}" class="dropdown-item">
                                            <i class="ri-add-circle-line me-2"></i>Create New Listing
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('farmer.marketplace.leads') }}" class="dropdown-item">
                                        <i class="ri-mail-line me-2"></i>My Leads
                                        @php
                                            $newLeadsCount = \App\Models\Market\MarketplaceInquiry::whereHas('listing', function($q) {
                                                $q->where('user_id', auth()->id());
                                            })->where('status', 'new')->count();
                                        @endphp
                                        @if($newLeadsCount > 0)
                                            <span class="badge bg-success badge-notification ms-1">{{ $newLeadsCount }}</span>
                                        @endif
                                    </a>
                                    
                                    <!-- <div class="dropdown-divider"></div>
                                    
                                    @if(!$hasActiveSubscription)
                                        <a href="{{ route('farmer.marketplace.my-listings') }}" class="dropdown-item text-warning">
                                            <i class="ri-vip-crown-line me-2"></i>Subscribe Now
                                            <span class="badge bg-warning ms-1">₦5,000/year</span>
                                        </a>
                                    @else
                                        <a href="{{ route('farmer.marketplace.my-listings') }}" class="dropdown-item">
                                            <i class="ri-shield-check-line me-2"></i>Subscription
                                            @php
                                                $subscription = auth()->user()->activeMarketplaceSubscription;
                                                $daysRemaining = $subscription ? $subscription->days_remaining : 0;
                                            @endphp
                                            @if($daysRemaining <= 30)
                                                <span class="badge bg-warning badge-notification ms-1">{{ $daysRemaining }}d left</span>
                                            @else
                                                <span class="badge bg-success badge-notification ms-1">Active</span>
                                            @endif
                                        </a>
                                    @endif -->
                                </div>
                            </li>

                            <!-- Support -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('farmer.support.*') ? 'active' : '' }}" 
                                   href="{{ route('farmer.support.index') }}">
                                    <i class="ri-customer-service-2-line me-2"></i> Support
                                </a>
                            </li>
                        </ul>

                        <!-- Right Side Navigation -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Logout Link (Desktop) -->
                            <li class="nav-item d-none d-lg-block">
                                <a class="nav-link text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                                    <i class="ri-shut-down-line me-2"></i> Logout
                                </a>
                                <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>

                            <!-- Logout Link (Mobile) -->
                            <li class="nav-item d-lg-none">
                                <a class="nav-link text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                                    <i class="ri-shut-down-line me-2"></i> Logout
                                </a>
                                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
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
    

    <!-- Scripts -->
    <script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/app.js') }}"></script>

    @stack('scripts')
</body>

</html>