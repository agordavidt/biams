<!DOCTYPE html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Dashboard | Benue e_Agriculture</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">      
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">

        <!-- jquery.vectormap css -->
        <link href="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

        <!-- DataTables -->
        <link href="{{ asset('dashboard/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset('dashboard/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />  

        <!-- Bootstrap Css -->
        <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />


        <!--- new stuff ----->
         <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BSAMS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .practice-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .practice-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .status-tracker {
            position: relative;
            padding: 20px;
            margin-bottom: 30px;
        }

        .status-step {
            position: relative;
            padding-bottom: 20px;
        }

        .status-step::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 30px;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .status-step:last-child::before {
            display: none;
        }

        .status-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .status-icon.completed {
            background: #28a745;
            color: white;
        }

        .status-icon.pending {
            background: #ffc107;
            color: white;
        }

        .status-icon.current {
            background: #007bff;
            color: white;
        }

        .modal-xl {
            max-width: 95%;
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
                                    <i class="fas fa-leaf me-2"></i>BIAAMS
                                </span>
                                <span class="logo-lg">
                                    <i class="fas fa-leaf me-2"></i>BIAAMS
                                </span>
                            </a>
                                <!-- <a class="navbar-brand" href="#"><i class="fas fa-leaf me-2"></i>BIAAMS</a> -->
                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <i class="fas fa-leaf me-2"></i>BIAAMS
                                </span>
                                <span class="logo-lg">
                                    <i class="fas fa-leaf me-2"></i>BIAAMS
                                </span>
                            </a>
                        </div>

                        <!-- <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button> -->

                        <!-- App Search-->
                        <form class="app-search d-none d-lg-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="ri-search-line"></span>
                            </div>
                        </form>

                        <!-- <div class="dropdown dropdown-mega d-none d-lg-block ms-2">
                           
                           
                        </div> -->
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
                                <a href="{{ route('home') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('farmers.crop') }}" class=" waves-effect">
                                    <i class="ri-calendar-2-line"></i>
                                    <span>Registration</span>
                                </a>
                            </li>
                
                            <!-- <li class="menu-title">Pages</li>                          
                           
                            <li class="menu-title">Components</li> -->

                             <li> <a href="#"><i class="ri-user-line align-middle me-1"></i><span>Profile</span></a></li>
                             <form action="{{ route('logout') }}" method="POST" id="logout-form"> 
                                @csrf 
                                <li> 
                                    <a class="text-danger" href="#" id="logout-link"> <i class="ri-shut-down-line align-middle me-1 text-danger"></i> <span>Logout</span> </a> 
                                </li> 
                            </form>
                             
                            
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->
             <div>
                     @yield('content')
             </div>          




             <!-- JAVASCRIPT -->
        <script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/node-waves/waves.min.js') }}"></script>

        
        <!-- apexcharts -->
        <script src="{{ asset('dashboard/libs/apexcharts/apexcharts.min.js') }}"></script>

        <!-- jquery.vectormap map -->
        <script src="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('dashboard/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        
        <!-- Responsive examples -->
        <script src="{{ asset('dashboard/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <script src="{{ asset('dashboard/js/pages/dashboard.init.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('dashboard/js/app.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script> 
        document.getElementById('logout-link').addEventListener('click', function(event) { event.preventDefault(); 
            document.getElementById('logout-form').submit(); }); 



            // Practice-specific form fields object
            const practiceFields = {
                crop_farming: `
                    <h5 class="mb-3">Crop Farming Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Farm Size (hectares)</label>
                            <input type="number" step="0.1" class="form-control" name="farm_size" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Farming Methods</label>
                            <select class="form-select" name="farming_methods" required>
                                <option value="organic">Organic</option>
                                <option value="conventional">Conventional</option>
                                <option value="mixed">Mixed</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Crop Types</label>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="maize">
                                        <label class="form-check-label">Maize</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="rice">
                                        <label class="form-check-label">Rice</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="cassava">
                                        <label class="form-check-label">Cassava</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="yam">
                                        <label class="form-check-label">Yam</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Seasonal Pattern</label>
                            <select class="form-select" name="seasonal_pattern" required>
                                <option value="rainy">Rainy Season</option>
                                <option value="dry">Dry Season</option>
                                <option value="both">Both Seasons</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Geolocation</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="latitude" placeholder="Latitude" readonly required>
                                <input type="text" class="form-control" name="longitude" placeholder="Longitude" readonly required>
                                <button type="button" class="btn btn-outline-secondary" onclick="getLocation()">
                                    <i class="fas fa-map-marker-alt"></i> Get Location
                                </button>
                            </div>
                        </div>
                    </div>
                `,
                
                animal_farming: `
                    <h5 class="mb-3">Animal Farming Details</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Livestock Types</label>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="livestock[]" value="cattle">
                                        <label class="form-check-label">Cattle</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="livestock[]" value="goats">
                                        <label class="form-check-label">Goats</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="livestock[]" value="sheep">
                                        <label class="form-check-label">Sheep</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="livestock[]" value="poultry">
                                        <label class="form-check-label">Poultry</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Herd Size</label>
                            <input type="number" class="form-control" name="herd_size" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Facility Type</label>
                            <select class="form-select" name="facility_type" required>
                                <option value="">Select Facility Type</option>
                                <option value="intensive">Intensive</option>
                                <option value="semi_intensive">Semi-Intensive</option>
                                <option value="extensive">Extensive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Breeding Program Details</label>
                            <textarea class="form-control" name="breeding_program" rows="3"></textarea>
                        </div>
                    </div>
                `,
                
                processing: `
                    <h5 class="mb-3">Processing Details</h5>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Items Processed</label>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="processed_items[]" value="grains">
                                        <label class="form-check-label">Grains</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="processed_items[]" value="tubers">
                                        <label class="form-check-label">Tubers</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="processed_items[]" value="fruits">
                                        <label class="form-check-label">Fruits</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="processed_items[]" value="vegetables">
                                        <label class="form-check-label">Vegetables</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Processing Capacity (tons/day)</label>
                            <input type="number" step="0.1" class="form-control" name="processing_capacity" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Equipment Type</label>
                            <select class="form-select" name="equipment_type" required>
                                <option value="">Select Equipment Type</option>
                                <option value="manual">Manual</option>
                                <option value="semi_automated">Semi-Automated</option>
                                <option value="fully_automated">Fully Automated</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Equipment Specifications</label>
                            <textarea class="form-control" name="equipment_specs" rows="3" required></textarea>
                        </div>
                    </div>
                `,
                
                abattoir: `
                    <h5 class="mb-3">Abattoir Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Daily Capacity (animals)</label>
                            <input type="number" class="form-control" name="daily_capacity" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Facility Type</label>
                            <select class="form-select" name="facility_type" required>
                                <option value="">Select Facility Type</option>
                                <option value="small">Small Scale</option>
                                <option value="medium">Medium Scale</option>
                                <option value="large">Large Scale</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Facility Specifications</label>
                            <textarea class="form-control" name="facility_specs" rows="3" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Certifications</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="certifications[]" value="health">
                                        <label class="form-check-label">Health Certificate</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="certifications[]" value="environmental">
                                        <label class="form-check-label">Environmental Compliance</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="certifications[]" value="safety">
                                        <label class="form-check-label">Safety Compliance</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `
            };

            // Initialize on document ready
            $(document).ready(function() {
                // Initialize LGA dropdown
                initializeLGA();
                
                // Handle practice card clicks
            //     $('.practice-card').click(function() {
            //         const practiceType = $(this).data('practice');
            //         showPracticeForm(practiceType);
            //     });
                
            //     // Handle form submission
            //     $('#submitPractice').click(function() {
            //         submitPracticeForm();
            //     });
            // });

            // Initialize LGA dropdown
            function initializeLGA() {
                const lgas = [
                    'ADO', 'AGATU', 'APA', 'BURUKU', 'GBOKO', 'GUMA', 'GWER EAST', 
                    'GWER WEST', 'KATSINA-ALA', 'KONSHISHA', 'KWANDE', 'LOGO', 
                    'MAKURDI', 'OBI', 'OGBADIBO', 'OJU', 'OHIMINI', 'OKPOKWU', 
                    'OTUKPO', 'TARKA', 'UKUM', 'USHONGO', 'VANDEIKYA'
                ];
                
                const lgaSelect = $('select[name="lga"]');
                lgas.forEach(lga => {
                    lgaSelect.append(`<option value="${lga}">${lga}</option>`);
                });
            }

            // Show practice form
            function showPracticeForm(practiceType) {
                $('#practiceFields').html(practiceFields[practiceType]);
                $('#practiceForm').data('practice-type', practiceType);
                $('#practiceModal').modal('show');
            }

            // Get geolocation
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            $('input[name="latitude"]').val(position.coords.latitude);
                            $('input[name="longitude"]').val(position.coords.longitude);
                        },
                        function(error) {
                            alert('Error getting location: ' + error.message);
                        }
                    );
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            }

            // Form validation and submission
            function submitPracticeForm() {
                const form = $('#practiceForm')[0];
                
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
                
                const formData = new FormData(form);
                formData.append('practice_type', $('#practiceForm').data('practice-type'));
                
                // Show loading state
                $('#submitPractice').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Submitting...');
                
                $.ajax({
                    url: '/api/submit-practice',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Show success message
                        alert('Form submitted successfully!');
                        $('#practiceModal').modal('hide');
                        // Refresh the page or update status
                        location.reload();
                    },
                    error: function(xhr) {
                        // Show error message
                        alert('Error submitting form: ' + xhr.responseText);
                    },
                    complete: function() {
                        // Reset button state
                        $('#submitPractice').prop('disabled', false).text('Submit');
                    }
                });
            }

            // Form reset when modal is closed
            $('#practiceModal').on('hidden.bs.modal', function() {
                $('#practiceForm')[0].reset();
            });

            // Image preview functionality (if needed)
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }


        </script>
    </body>

</html>


