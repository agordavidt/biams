@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - {{ Setting::get('site_title', 'Benue State Integrated Agricultural Data and Access Management System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ Setting::imageUrl('site_logo') ?? asset('dashboard/images/favicon.ico') }}">

    <style>
        :root {
            --primary-green: #2e7d32;
            --secondary-green: #4caf50;
            --light-bg: #f5f7f5;
            --black: #000000;
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

        /* Services Section */
        .services-section {
            padding: 5rem 0;
        }

        .services-section h2 {
            font-size: 2rem; /* ~32px */
            font-weight: 700;
            color: var(--primary-green); 
            margin-bottom: 1.5rem;
        }

        .services-section h5 {
            font-size: 1.5rem; /* ~24px */
            font-weight: 600;
            color: var(--black); /* var(--primary-green); */
            margin-bottom: 1rem;
        }

        .services-section img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 1rem;
        }

        .service-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 1.5rem;
            transition: transform 0.3s ease;
            margin-bottom: 1rem;
        }

        .service-card:hover {
            transform: translateY(-5px);
        }

        .service-card h6 {
            font-size: 1.25rem; /* ~20px */
            font-weight: 600;
            color: var(--primary-green);
        }

        .service-card p {
            font-size: 0.95rem; /* ~15.2px */
            font-weight: 400;
        }

        /* Additional Services Section */
        .additional-services {
            padding: 5rem 0;
        }

        .additional-services .service-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 1.5rem;
            transition: transform 0.3s ease;
        }

        .additional-services .service-box:hover {
            transform: translateY(-5px);
        }

        .additional-services .service-box h5 {
            font-size: 1.25rem; /* ~20px */
            font-weight: 600;
            color: var(--primary-green);
        }

        .additional-services .service-box i {
            font-size: 2rem;
            color: var(--secondary-green);
            margin-bottom: 1rem;
        }

        .additional-services .service-card {
            padding: 2rem;
        }

        .additional-services ul {
            list-style: none;
            padding-left: 0;
            font-size: 1rem; /* ~16px */
            font-weight: 400;
        }

        .additional-services li {
            margin-bottom: 1rem;
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
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/') }}">Home</a> </li>
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
            <h1>Our Services</h1>
            <p>Have a feel of the services we render...</p>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 text-center">
                    <img src="{{ asset('dashboard/images/farmer_harvest.jpg') }}" alt="Farmer harvesting" style="width: 300px;" height="300px">
                </div>
                <div class="col-lg-8">
                    <h5>We render services, collaborating with various Agencies of the Benue State</h5>
                    <h2>Ministry of Agriculture & Natural Resources</h2>
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <div class="service-card ">
                                <h6 >RAAMP</h6>
                                <p>Rural Access Agricultural Marketing Project.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service-card">
                                <h6>IFAD</h6>
                                <p>International Funds for Agricultural Development.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service-card">
                                <h6>ACReSAL</h6>
                                <p>Agro-Climatic Resilience in Semi-Arid Landscapes.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service-card">
                                <h6>BNARDA</h6>
                                <p>Benue State Agricultural and Rural Development.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service-card">
                                <h6>BENTHA</h6>
                                <p>Benue State Tractor Hiring Agency.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service-card">
                                <h6>FADAMA CARES</h6>
                                <p>World Bank assisted agricultural project.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services Section -->
    <section class="additional-services">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <h5>Presenting</h5>
                    <h2>Our Services</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="service-box">
                                <i class="far fa-handshake"></i>
                                <h5>Agro Farm Inputs</h5>
                                <p>Farmers who have need for various farm inputs can access them from us.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-box">
                                <i class="far fa-money-bill-alt"></i>
                                <h5>Dry Season Farming</h5>
                                <p>We have all it needs for farmers to do an all-year-round farming.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-box">
                                <i class="fas fa-heart"></i>
                                <h5>Meat Inspection</h5>
                                <p>For healthy meat consumption, we carry out meat inspections.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-box">
                                <i class="fas fa-coffee"></i>
                                <h5>Interventions</h5>
                                <p>There are interventions and support for deserving farmers.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-card">
                        <h2>Other Services</h2>
                        <ul>
                            <li>We register vendors who wish and have the requisite qualifications to supply agricultural inputs to the Government of Benue State.</li>
                            <li>There are subsidies given to farmers when need arises such as: seeds, fertilizers, chemicals, and the likes.</li>
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