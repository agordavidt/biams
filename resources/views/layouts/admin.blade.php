<!DOCTYPE html>
<html lang="en">

        <head>
            <meta charset="utf-8" />
            <title>Admin | Benue State Smart Agricultural System and Data Management</title>
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
            /* Badge soft colors */
            .badge-soft-primary { background-color: #e0e7ff; color: #4f46e5; }
            .badge-soft-success { background-color: #dcfce7; color: #166534; }
            .badge-soft-warning { background-color: #fef3c7; color: #92400e; }
            .badge-soft-danger { background-color: #fee2e2; color: #991b1b; }
            .badge-soft-info { background-color: #dbeafe; color: #1e40af; }
            .badge-soft-secondary { background-color: #f3f4f6; color: #4b5563; }
            .badge-soft-dark { background-color: #e5e7eb; color: #1f2937; }
        </style>
        </head>

    <body data-topbar="dark">
    
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <header id="page-topbar" style="background: #38761D;">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="#" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                                {{-- <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light"> --}}
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                                {{-- <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light"> --}}
                                </span>
                            </a>

                            <a href="#" class="logo logo-light">
                                <span class="logo-sm">
                                
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
                                {{-- <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light"> --}}
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" height="40">
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
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>                            

                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line me-2"></i>
                                    <span class="badge rounded-pill bg-success float-end"></span>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.users.index') }}" class="waves-effect">
                                    <i class="ri-team-line me-2"></i>
                                    <span>System Staff</span>
                                </a>                               
                            </li>

                            <!-- Farm Practices Menu -->
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-plant-line me-2"></i>
                                    <span>Farm Practices</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('admin.farm-practices.index') }}">
                                        <i class="ri-dashboard-line me-1"></i> Overview
                                    </a></li>
                                    <li><a href="{{ route('admin.farm-practices.crops') }}">
                                        <i class="ri-seedling-line me-1"></i> Crop Farming
                                    </a></li>
                                    <li><a href="{{ route('admin.farm-practices.livestock') }}">
                                        <i class="ri-bear-smile-line me-1"></i> Livestock
                                    </a></li>
                                    <li><a href="{{ route('admin.farm-practices.fisheries') }}">
                                        <i class="ri-ship-line me-1"></i> Fisheries
                                    </a></li>
                                    <li><a href="{{ route('admin.farm-practices.orchards') }}">
                                        <i class="ri-plant-line me-1"></i> Orchards
                                    </a></li>
                                </ul>
                            </li>

                            <!-- <li>
                                <a href="{{ route('admin.partners.index') }}" class="waves-effect">
                                    <i class="ri-group-line me-2"></i>
                                    <span>Partners</span>
                                </a>
                            </li>   -->
                            <li>
                                <a href="{{ route('admin.vendors.index') }}" class="waves-effect">
                                    <i class="ri-store-3-line me-2"></i>
                                    <span>Vendors</span>
                                </a>
                            </li> 
                            <!-- Resources Menu - Unified -->
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-database-2-line me-2"></i>
                                    <span>Resources</span>
                                    @php
                                        $pendingReviewCount = \App\Models\Resource::vendorResources()->where('status', 'proposed')->count();
                                        $pendingApplications = \App\Models\ResourceApplication::where('status', 'pending')->count();
                                    @endphp
                                    @if($pendingReviewCount > 0 || $pendingApplications > 0)
                                        <span class="badge rounded-pill bg-warning float-end">
                                            {{ $pendingReviewCount + $pendingApplications }}
                                        </span>
                                    @endif
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a href="{{ route('admin.resources.index') }}" class="waves-effect">
                                            <i class="ri-list-check me-2"></i>
                                            <span>All Resources</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.resources.review.index') }}" class="waves-effect">
                                            <i class="ri-file-list-3-line me-2"></i>
                                            <span>Vendor Review</span>
                                            @if($pendingReviewCount > 0)
                                                <span class="badge rounded-pill bg-warning float-end">{{ $pendingReviewCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.applications.index') }}" class="waves-effect">
                                            <i class="ri-file-check-line me-2"></i>
                                            <span>Applications</span>
                                            @if($pendingApplications > 0)
                                                <span class="badge rounded-pill bg-warning float-end">{{ $pendingApplications }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.resources.create') }}" class="waves-effect">
                                            <i class="ri-add-line me-2"></i>
                                            <span>Create Resource</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                         
                            <li class="nav-item">
                                <a href="{{ route('admin.resources.applications.index') }}" class="nav-link">
                                    <i class="ri-file-list-line"></i> Applications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.support.index') }}" class="waves-effect">
                                    <i class="ri-customer-service-2-line me-2"></i>
                                    <span>Support System</span>
                                </a>
                            </li>

                            <!-- Marketplace Menu -->
                            @can('manage_supplier_catalog')
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-store-2-line me-2"></i>
                                    <span>Marketplace</span>
                                    @php
                                        $pendingCount = \App\Models\Market\MarketplaceListing::where('status', 'pending_review')->count();
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="badge rounded-pill bg-warning float-end">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li>
                                        <a href="{{ route('admin.marketplace.dashboard') }}">
                                            <i class="ri-dashboard-line me-1"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.marketplace.listings') }}">
                                            <i class="ri-shopping-bag-line me-1"></i> Listings
                                            @if($pendingCount > 0)
                                                <span class="badge rounded-pill bg-warning float-end">{{ $pendingCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.marketplace.categories') }}">
                                            <i class="ri-list-check me-1"></i> Categories
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.marketplace.subscriptions') }}">
                                            <i class="ri-vip-crown-line me-1"></i> Subscriptions
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.marketplace.analytics') }}">
                                            <i class="ri-line-chart-line me-1"></i> Analytics
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endcan
                            
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

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="ri-alert-line me-2"></i>
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="ri-information-line me-2"></i>
                            {{ session('info') }}
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

                <!-- container-fluid -->   
                    </div> 
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

        <!-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> -->
        <!--- sweet alert ---->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script> 
        document.getElementById('logout-link').addEventListener('click', function(event) { 
            event.preventDefault(); 
            document.getElementById('logout-form').submit(); 
        }); 
        </script>

        <!-- Auto-hide alerts after 5 seconds -->
        <script>
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