
<!Doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Benue State Integrated Agricultural Assets Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">

        <!-- DataTables -->
        <link href="{{ asset('dashboard/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('dashboard/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('dashboard/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset('dashboard/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />     

        <!-- Bootstrap Css -->
        <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <!-- Toastr CSS --> 
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        

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

                        <div class="dropdown d-none d-sm-inline-block">
                            <!-- <button type="button" class="btn header-item waves-effect"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="" src="{{ asset('dashboard/images/flags/us.jpg') }}" alt="Header Language" height="16">
                            </button> -->
                            <div class="dropdown-menu dropdown-menu-end">
                    
                                <!-- item-->
                                
                            </div>
                        </div>

                       
                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <!-- <img class="rounded-circle header-profile-user" src="{{ asset('dashboard/images/users/avatar-1.jpg') }}"
                                    alt="Header Avatar"> -->
                                <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->name }}</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                           
                        </div>

                        <div class="dropdown d-inline-block">
                           
                        </div>
            
                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!-- User details -->
                    <div class="user-profile text-center mt-3">
                       
                    </div>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="index.html" class="waves-effect">
                                    <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li>
                                <a href="calendar.html" class=" waves-effect">
                                    <i class="ri-calendar-2-line"></i>
                                    <span>Calendar</span>
                                </a>
                            </li>
                
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-mail-send-line"></i>
                                    <span>Email</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="email-inbox.html">Inbox</a></li>
                                    <li><a href="email-read.html">Read Email</a></li>
                                </ul>
                            </li>

                            
                            <li class="menu-title">Pages</li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-account-circle-line"></i>
                                    <span>Authentication</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="auth-login.html">Login</a></li>
                                    <li><a href="auth-register.html">Register</a></li>
                                    <li><a href="auth-recoverpw.html">Recover Password</a></li>
                                    <li><a href="auth-lock-screen.html">Lock Screen</a></li>
                                </ul>
                            </li>

                           

                            <li class="menu-title">Components</li>

                            

                           

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-map-pin-line"></i>
                                    <span>Maps</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="maps-google.html">Google Maps</a></li>
                                    <li><a href="maps-vector.html">Vector Maps</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-share-line"></i>
                                    <span>Multi Level</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="true">
                                    <li><a href="javascript: void(0);">Level 1.1</a></li>
                                    <li><a href="javascript: void(0);" class="has-arrow">Level 1.2</a>
                                        <ul class="sub-menu" aria-expanded="true">
                                            <li><a href="javascript: void(0);">Level 2.1</a></li>
                                            <li><a href="javascript: void(0);">Level 2.2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Applications Table</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-info waves-effect">Back to Dashboard</a></li>
                                             <!-- <a class="btn btn-info mb-5 waves-effect waves-light" href="index.html">Back to Dashboard</a> -->
                                            <li class="breadcrumb-item active">Applicantions</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <!-- Toastr Notifications --->
                        
                        @if (session('status'))
                            <script>
                               @if (session('success') == 'Application approved successfully.') 
                                 toastr.success('Application approved successfully!'); 
                               @elseif (session('success') == 'Application rejected successfully.') 
                                toastr.error('Application rejected.'); 
                                @endif
                            </script>
                        @endif

        
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>LGA</th>
                                                <th>Farm Size</th>
                                                <th>Crop</th>
                                                <th>Geolocation</th>
                                                <th>Status</th>
                                                <th>Actions</th>  
                                            </tr>
                                            </thead>
        
        
                                            <tbody>
                                             @foreach($applications as $application)
                                                <tr>
                                                    <td>{{ $application->user->name }}</td>
                                                    <td>{{ $application->user->gender }}</td>
                                                    <td>{{ $application->user->lga }}</td>
                                                    <td>{{ $application->farm_size }} ha</td>
                                                    <td>{{ $application->crop }}</td>
                                                    <!-- <td>{{ number_format($application->latitude , 4) }}, {{ number_format($application->longitude , 4) }}</td> -->
                                                     <td style="cursor: pointer;">
                                                        <a href="#" 
                                                        data-toggle="modal" 
                                                        data-target="#mapModal" 
                                                        data-latitude="{{ $application->latitude }}" 
                                                        data-longitude="{{ $application->longitude }}">
                                                            {{ number_format($application->latitude , 4) }}, {{ number_format($application->longitude , 4) }} 
                                                        </a>
                                                    </td>
                                                    <td>{{ ucfirst($application->user->status) }}</td>
                                                    <td class="text-end">
                                                        <div class="btn-group">
                                                            <!-- View Details Button -->
                                                            <button class="action-btn" title="View Details" onclick="viewFarmer({{ json_encode($application) }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                                    <circle cx="12" cy="12" r="3" />
                                                                </svg>
                                                            </button>

                                                            <!-- Conditionally Render Approve and Reject Buttons -->
                                                            @if ($application->user->status === 'pending')
                                                                <form action="{{ route('admin.applications.approve', $application->user) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button class="action-btn approve-btn" type="submit" title="Approve Application">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                            <polyline points="20 6 9 17 4 12" />
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('admin.applications.reject', $application->user) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button class="action-btn reject-btn" type="submit" title="Reject Application">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                            <line x1="18" y1="6" x2="6" y2="18" />
                                                                            <line x1="6" y1="6" x2="18" y2="18" />
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>

                                        </table>

                                         <!-- Include map  modal -->
                                         @include('partials.map-modal') 
                                         <!-- Include famer view  modal -->
                                         @include('partials.farmer-modal') 
                                    </div> 
                                    <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div>
                        <!-- end row-->


                    

                       
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © <div class="text-info">Benue State Integrated Agricultural Assets Management Systems</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Powered by  BDIC
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/node-waves/waves.min.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('dashboard/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
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
        
        <!-- Responsive examples -->
        <script src="{{ asset('dashboard/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <!-- Datatable init js -->
        <script src="{{ asset('dashboard/js/pages/datatables.init.js') }}"></script>

        <script src="{{ asset('dashboard/js/app.js') }}"></script>
         <!-- Toastr JS --> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


        <!-- activates the map modal but distrubts the close and cancel button of the individual farmer view modal - -->
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 



            <!-- =============== modal javascript to display farmer's inforamtion ================ -->
             <script>


                 $(document).ready(function() {
                    $('td a[data-toggle="modal"]').on('click', function(event) {
                        event.preventDefault(); // Prevent default anchor tag behavior

                        const latitude = $(this).data('latitude');
                        const longitude = $(this).data('longitude');

                        $('#map-container').html(`
                            <iframe 
                                style="width: 100%; height: 100%; border:0;" 
                                frameborder="0" 
                                src="https://www.google.com/maps/embed/v1/place?q= {{ number_format($application->latitude , 6) }}, {{ number_format($application->longitude , 6) }}&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8">
                            </iframe>
                        `);
                    });
                });





                    // farmer view functions
                    function viewFarmer(farmer) {
                        let details = '';

                        // Common fields for all farmers
                        details += `
                        <div class='row' >
                        <div class = 'col-6' >
                            <p><strong>Name:</strong> ${farmer.user.name}</p>
                            <p><strong>Email:</strong> ${farmer.user.email}</p>
                            <p><strong>Phone:</strong> ${farmer.phone_number}</p>
                            <p><strong>Date of Birth:</strong> ${farmer.date_of_birth}</p>
                            <p><strong>Gender:</strong> ${farmer.gender}</p>
                        </div>
                        <div class='col-6'>
                            <p><strong>Education Level:</strong> ${farmer.education_level}</p>
                            <p><strong>Household Size:</strong> ${farmer.household_size}</p>
                            <p><strong>Dependents:</strong> ${farmer.dependents}</p>
                            <p><strong>Income Level:</strong> ${farmer.income_level}</p>
                            <p><strong>Local Government Area:</strong> ${farmer.local_government_area}</p>
                        </div>
                        </div>
                        `;

                        // Practice-specific fields
                        if (farmer.farm_size !== undefined) {
                            details += `
                                <p><strong>Farm Size:</strong> ${farmer.farm_size}</p>
                                <p><strong>Farming Methods:</strong> ${farmer.farming_methods}</p>
                                <p><strong>Seasonal Patterns:</strong> ${farmer.seasonal_patterns}</p>
                                <p><strong>Geolocation:</strong> ${farmer.geolocation}</p>
                            `;
                        } else if (farmer.herd_size !== undefined) {
                            details += `
                                <p><strong>Herd Size:</strong> ${farmer.herd_size}</p>
                                <p><strong>Facility Info:</strong> ${farmer.facility_info}</p>
                                <p><strong>Breeding Programs:</strong> ${farmer.breeding_programs}</p>
                            `;
                        } else if (farmer.facility_specifications !== undefined) {
                            details += `
                                <p><strong>Facility Specifications:</strong> ${farmer.facility_specifications}</p>
                                <p><strong>Operational Capacity:</strong> ${farmer.operational_capacity}</p>
                                <p><strong>Compliance Certificates:</strong> ${farmer.compliance_certificates}</p>
                            `;
                        } else if (farmer.processing_capabilities !== undefined) {
                            details += `
                                <p><strong>Processing Capabilities:</strong> ${farmer.processing_capabilities}</p>
                                <p><strong>Equipment Specifications:</strong> ${farmer.equipment_specifications}</p>
                                <p><strong>Production Capacity:</strong> ${farmer.production_capacity}</p>
                            `;
                        }

                        // Insert details into the modal
                        document.getElementById('farmerDetails').innerHTML = details;

                        // Show the modal
                        const modal = new bootstrap.Modal(document.getElementById('viewFarmerModal'));
                        modal.show();
                    }

                    
                </script>


    </body>
</html>
