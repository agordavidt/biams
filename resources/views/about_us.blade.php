@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - {{ Setting::get('site_title', 'Benue State Integrated Agricultural Data and Access Management System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            font-size: 1rem; /* Base: 16px */
            line-height: 1.6;
        }

        /* Navigation */
        .navbar {
            background: var(--primary-green);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand img {
            height: 50px;
            margin-right: 2rem;
        }

        .navbar-nav .nav-link {
            font-weight: 600;
            font-size: 1.1rem; /* ~17.6px */
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
            font-size: 1rem; /* ~16px */
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.8)), url('{{ asset('dashboard/images/agro_bg1.jpg') }}');
            /* background: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.8)), url('{{ asset('dashboard/images/background2.png') }}'); */
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: left;
        }

        .hero-section h1 {
            font-size: 2.5rem; /* ~40px */
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.25rem; /* ~20px */
            font-weight: 300;
            font-style: italic;
            color: #c0bcbc;
        }

        /* About Section */
        .about-section {
            padding: 5rem 0;
        }

        .about-section h2 {
            font-size: 2rem; /* ~32px */
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 1.5rem;
        }

        .about-section h5 {
            font-size: 1.5rem; /* ~24px */
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 1rem;
        }

        .about-section p {
            font-size: 1rem; /* ~16px */
            font-weight: 400;
            text-align: justify;
        }

        .about-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 2rem;
            transition: transform 0.3s ease;
        }

        .about-card:hover {
            transform: translateY(-5px);
        }

        .about-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
            border: 5px solid #cbe5ee;
        }

        /* Success Story Section */
        .success-story {
            padding: 3rem 0;
        }

        .success-story img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 1rem;
        }

        .timeline-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .timeline-box:hover {
            transform: translateY(-5px);
        }

        .timeline-box h6 {
            font-size: 1.25rem; /* ~20px */
            font-weight: 600;
            color: var(--primary-green);
        }

        .timeline-box p {
            font-size: 0.95rem; /* ~15.2px */
            font-weight: 400;
        }

    

        /* Buttons */
        .btn-primary {
            background: var(--secondary-green);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            font-size: 1.1rem; /* ~17.6px */
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-green);
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            background: #1a3c34;
            color: #e0e0e0;
            padding: 3rem 0;
        }

        .footer h5 {
            font-size: 1.25rem; /* ~20px */
            font-weight: 600;
        }

        .footer p, .footer a {
            font-size: 1rem; /* ~16px */
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
            margin-bottom: 1rem;
        }

        .powered_by_bdic {
            color: rgb(241, 80, 112);
            text-decoration: none;
        }

        .powered_by_bdic img {
            height: 20px;
            vertical-align: middle;
            margin-left: 0.5rem;
        }

    .navbar a.active{
        color: black;
    }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/')}}">Home</a> </li>
                    <li class="nav-item active"> <a class="nav-link active" href="{{ route('about') }}">About us</a> </li>
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
            <h1>About Us</h1>
            <p>Know more about what we do...</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="about-card">
                        <h5>Digitally connecting farmers, buyers, and agricultural product vendors</h5>
                        <p>The platform enhances agricultural data/market access and supports productivity for all stakeholders. In addition, the system empowers government agencies to implement targeted support programs such as subsidies, grants, and capacity-building initiatives.</p>
                        <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('dashboard/images/various_farm_illustrations_500.jpg') }}" alt="Assorted farm practices" class="about-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Success Story Section -->
    <section class="success-story">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 text-center">
                    <img src="{{ asset('dashboard/images/agric_0.jpg') }}" alt="Assorted farm practices">
                </div>
                <div class="col-lg-8">
                    <h5>This system is in close collaboration with the Benue State</h5>
                    <h2>Ministry of Agriculture & Natural Resources</h2>
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <div class="timeline-box">
                                <h6>Modernization</h6>
                                <p>Modernization of agricultural production, processing, storage, and distribution.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="timeline-box">
                                <h6>Food Security</h6>
                                <p>Attainment of self-sufficiency in basic food products for enhanced food security.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="timeline-box">
                                <h6>Living Standards</h6>
                                <p>Improve the living standards of farmers and rural dwellers through enhanced income.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Structure Section -->
    <section class="structure-section">
        <div class="container">
            <h5>How we are structured</h5>
            <h2>Administrative Hierarchy of the Ministry</h2>
            <p>The structural arrangement of the Ministry is an effective design to accommodate and harness the agricultural resources endowment of the State. Each of the Directorates acts as a complement to the other as they are headed by capable hands as follows:</p>
            <div class="row g-4 mt-4">
                <div class="col-md-6">
                    <div class="about-card">
                        <h5 class="text-center">The Hon. Commissioner</h5>
                        <ul>
                            <li>Directorate of Administration and Supplies</li>
                            <li>Directorate of Finance and Accounts</li>
                            <li>Directorate of Agriculture</li>
                            <li>Directorate of Engineering Services</li>
                            <li>Directorate of Livestock Services</li>
                            <li>Directorate of Fisheries</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-card">
                        <h5 class="text-center">Other offices under the Ministry</h5>
                        <ul>
                            <li>Benue State Tractor Hiring Agency, BENTHA</li>
                            <li>Benue State Agricultural and Rural Development, BNARDA</li>
                            <li>Akperan Orshi College of Agriculture Yandev</li>
                            <li>Agricultural Training Center, Mbatie</li>
                            <li>Agricultural Vocational Training Center, Otobi – Otukpa</li>
                            <li>Accelerated Food Production Programme</li>
                        </ul>
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
                    <img src="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/bsiadams_logo_new.png') }}" alt="Benue Agro Market Logo" class="footer-logo">
                    <p>Empowering farmers, connecting markets</p>
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
                </a></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>