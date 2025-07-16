@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Setting::get('site_title', 'Benue State Integrated Agricultural Data and Access Management System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('dashboard/css/css/MyStyles.css') }}">

    <!-- <link rel="icon" href="{{ asset('dashboard/images/favicon.jpg') }}" type="image/x-icon"> -->
    <link rel="shortcut icon" href="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/favicon.ico') }}">

    <style>
        :root {
            --primary-green: #2e7d32;
            --secondary-green: #4caf50;
            --light-bg: #f5f7f5;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Poppins', sans-serif;
            /* Updated font family */
            font-size: 1rem;
            /* Base font size: 16px */
            line-height: 1.6;
        }

        /* Navigation */
        .navbar {
            background: var(--primary-green);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand img {
            height: 50px;
            margin-right: 2rem;
        }

        .navbar-nav .nav-link {
            font-weight: 600;
            /* Semi-bold for better emphasis */
            font-size: 1.1rem;
            /* Slightly larger: ~17.6px */
            padding: 0.75rem 1.25rem;
            color: white;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #e8f5e9;
        }

        .navbar .ms-auto .nav-link {
            background: var(--secondary-green);
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            margin-left: 1rem;
            font-size: 1rem;
            /* ~16px */
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('dashboard/images/agro_bg1.jpg') }}');
            
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            text-align: center;


        }

        .hero-section h1 {
            font-size: 3rem;
            /* ~48px, larger for impact */
            font-weight: 700;
            /* Bold */
            margin-bottom: 1.5rem;

        }

        .hero-section .lead {
            font-size: 1.5rem;
            /* ~24px, larger for readability */
            font-weight: 300;
            /* Light for contrast */
            margin-bottom: 2rem;
        }

        .hero-section .btn-primary {
            background: var(--primary-green);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            /* ~17.6px */
            font-weight: 500;
        }

        .hero-section .btn-primary:hover {
            background: var(--secondary-green);
            transform: translateY(-2px);
        }

        .hero-section .btn-outline-light {
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-size: 1.1rem;
            /* ~17.6px */
            font-weight: 500;
        }



        /* About Section */
        .about-section {
            background: #e8f5e9;
            padding: 5rem 0;
        }

        .about-section h2 {
            font-size: 2.5rem;
            /* ~40px */
            font-weight: 700;
        }

        .about-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .about-card p {
            font-size: 1.125rem;
            /* ~18px */
            font-weight: 400;
        }

        .about-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Features Section */
        .feature-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }



        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .icon-circle-counter {
                    width: 100px;
                    height: 100px;
                    border-radius: 50%;
                    background: #e8f5e9;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 0.5rem;
                }

        .icon-circle i {
            font-size: 1.75rem;
            /* ~28px */
        }

        /* Footer */
        .footer {
            background: #1a3c34;
            color: #e0e0e0;
            padding: 3rem 0;
        }

        .footer h5 {
            font-size: 1.25rem;
            /* ~20px */
            font-weight: 600;
        }

        .footer p,
        .footer a {
            font-size: 1rem;
            /* ~16px */
            font-weight: 400;
        }

        .footer a {
            color: #e0e0e0;
            text-decoration: none;
        }

        .footer a:hover {
            color: var(--secondary-green);
        }

        .footer-logo {
            height: 50px;
            /* Matches navbar logo size */
            margin-bottom: 1rem;
        }

        .powered_by_bdic {
            color: rgb(241, 80, 112);
            /* Original pink color */
            text-decoration: none;
        }

        .powered_by_bdic img {
            height: 20px;
            /* Adjust as needed for small logo */
            vertical-align: middle;
            margin-left: 0.5rem;
        }

        .active {
            color: black;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/bsiadams_logo_new.png') }}" alt="logo-light">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item active"> <a class="nav-link active" href="{{ url('/') }}">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('about') }}">About</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('services') }}">Services</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('visitor.marketplace') }}">Market</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('contact') }}">Contact</a> </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('login') }}">PORTAL</a> </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Benue State Integrated Agricultural Data and Access Management System
            </h1>
            <p class="lead mb-5">Empowering farmers with digital solutions for better agricultural management</p>
            <a href="{{ route('login') }}" class="btn btn-primary me-3">Get Started</a>
            <a href="#features" class="btn btn-outline-light">Learn More</a>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">


                        
                            
                            <h3 class="icon-circle-counter text-success fw-bold">5,000+</h3>
                       

                        
                        <p class="text-muted mb-0 stat-card-p">Registered Farmers</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                         <h3 class="icon-circle-counter text-success fw-bold">23</h3>
                        {{-- <h3 class="text-success fw-bold">23</h3> --}}
                        <p class="stat-card-p text-muted mb-0">Local Governments</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                         <h3 class="icon-circle-counter text-success fw-bold">100+</h3>
                        {{-- <h3 class="text-success fw-bold">100+</h3> --}}
                        <p class="stat-card-p text-muted mb-0">Resources Available</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                         <h3 class="icon-circle-counter text-success fw-bold">4</h3>
                        {{-- <h3 class="text-success fw-bold">4</h3> --}}
                        <p class="stat-card-p text-muted mb-0">Agricultural Sectors</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h2 class="text-center text-success fw-bold mb-5">Welcome to Benue Agro System</h2>
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="about-card">
                        <p class="text-muted mb-3" style="text-align: justify">This is a system that digitally connects
                            farmers, buyers, and agricultural product vendors. By offering these connections, the
                            platform enhances agricultural data/market access and supports productivity for all
                            stakeholders.</p>
                        <p class="text-muted mb-4" style="text-align: justify">In addition, the system empowers
                            government agencies to implement targeted support programs such as subsidies, grants, and
                            capacity-building initiatives.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">Get Started</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('dashboard/images/green_beans.jpg') }}" class="about-image" alt="farm_produce">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center text-success fw-bold mb-5">Our Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-user-plus fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold">Easy Registration</h5>
                        <p class="">Simple and straightforward registration process for all agricultural
                            practitioners.</p>

                        <div class="container mt-5">
                            <div class="stepper">
                                <div class="step started">
                                    <div class="circle">1</div>
                                    <div class="content">
                                        <h5 class="text-muted">Account Registration</h5>
                                        <p class="text-muted">Click on 'Register' and fill in your details to get
                                            started.</p>
                                    </div>
                                </div>
                                <div class="step active">
                                    <div class="circle">2</div>
                                    <div class="content">
                                        <h5 class="text-muted">Email Verification</h5>
                                        <p class="text-muted">Check your email and click on the 'Verify Email' link.
                                        </p>
                                    </div>
                                </div>
                                <div class="step completed">
                                    <div class="circle">3</div>
                                    <div class="content">
                                        <h5 class="text-muted">Finish Setup</h5>
                                        <p class="text-muted">Complete your profile to start using the App and its
                                            services.</p>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-tools fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold">Resource Access</h5>
                        <p class="">Access to agricultural implements, support programs, and training resources.
                        </p>

                        <div class="container mt-5">
                            <div class="stepper">
                                <div class="step started">
                                    <div class="circle">1</div>
                                    <div class="content">
                                        <h5 class="text-muted">Account Approval</h5>
                                        <p class="text-muted">Your completed profile is quickly approved by an Admin.
                                        </p>
                                    </div>
                                </div>
                                <div class="step active">
                                    <div class="circle">2</div>
                                    <div class="content">
                                        <h5 class="text-muted">Register Agric Practice</h5>
                                        <p class="text-muted">Register as many agricultural practices you're into as
                                            possible.</p>
                                    </div>
                                </div>
                                <div class="step completed">
                                    <div class="circle">3</div>
                                    <div class="content">
                                        <h5 class="text-muted">Resource-tailored Access</h5>
                                        <p class="text-muted">Access the available resources based on your registered
                                            practice.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-circle">
                            <i class="fas fa-chart-line fa-2x text-success"></i>
                        </div>
                        <h5 class="fw-bold">Data Management</h5>
                        <p class="">Comprehensive data management and tracking for agricultural activities.</p>

                        <div class="container mt-5">
                            <div class="stepper">
                                <div class="step started">
                                    <div class="circle">1</div>
                                    <div class="content">
                                        <h5 class="text-muted">Data Availability</h5>
                                        <p class="text-muted">Your supplied data is structured and made available for
                                            use.</p>
                                    </div>
                                </div>
                                <div class="step active">
                                    <div class="circle">2</div>
                                    <div class="content">
                                        <h5 class="text-muted">Activity Tracking</h5>
                                        <p class="text-muted">Your data, services and activities are tracked for proper
                                            usage.</p>
                                    </div>
                                </div>
                                <div class="step completed">
                                    <div class="circle">3</div>
                                    <div class="content">
                                        <h5 class="text-muted">Data Protection</h5>
                                        <p class="text-muted">Be assured of secure data in accordance with the NDPC.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo"
                        class="footer-logo">
                    <p>Empowering farmers, connecting markets.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Contact</h5>
                    <p>Email: {{ Setting::get('contact_email', 'info@bsiadams.gov.ng') }}<br>
                        Phone: {{ Setting::get('contact_phone', '+234 000 0000 000') }}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <h5 class="fw-bold">Connect</h5>
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">© 2025 {{ Setting::get('site_title', 'Benue State Integrated Agricultural Data and Access Management System') }}<br>
                    <a href="http://bdic.ng" target="_blank" class="powered_by_bdic">Powered by
                        <img src="{{ asset('/dashboard/images/bdic_logo_small.png') }}" alt="BDIC">
                    </a>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
