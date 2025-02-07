<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benue State Integrated Agricultural Data and Access Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="dashboard/images/favicon.jpg" type="image/x-icon">
    <link rel="shortcut icon" href="dashboard/images/favicon.jpg" type="image/x-icon" />

    <style>
        .powered_by_bdic{
            text-decoration: none; 
        color:rgb(241, 80, 112);
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.7)), url('{{ asset('dashboard/images/portalbg.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }

        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            background-color: #f8f9fa;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-leaf me-2 " style="padding-right:120px; font-size: 2rem;"></i> 
                {{-- BSIADAMS --}}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            {{-- <div class="collapse navbar-collapse" id="navbarSupportedContent">

                 <ul class="navbar-nav mr-auto" style="font-weight: bold">
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'landing_page') }}">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'about') }}">About</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'services') }}">Services</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'contact') }}">Contact</a> </li>
                </ul> 

            </div> --}}

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto" style="font-weight: bold; ">
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'landing_page') }}">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'about') }}">About</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'services') }}">Services</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'contact') }}">Contact</a> </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'portal') }}">PORTAL</a> </li>
                    {{-- @if (Route::has('login'))

                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link " href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @endauth

                    @endif --}}

                </ul>
            </div>
        </div>
    </nav>


    <!-- Hero Section -->
    <section class="hero-section" style="margin-bottom: 200px">
        <div class="container text-center" >
            <h1 class="display-4 mb-4">Welcome to our Portal</h1>
            <p class="lead mb-4">To gain access to all the features we offer, you need to create an account. If you have
                done so already, please login.</p>

            @if (Route::has('login'))

                @auth

                    <a class="btn btn-success btn-lg px-4 me-2" href="{{ route('home') }}">Dashboard</a>
                @else
                    <a class="btn btn-success btn-lg px-4 me-2" href="{{ route('login') }}">Login</a>


                    @if (Route::has('register'))
                        <a class="btn btn-outline-light btn-lg px-4" href="{{ route('register') }}">Register</a>
                    @endif
                @endauth

            @endif

        </div>
    </section>











    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Contact Us</h5>
                    <p>Email: info@bsiadams.gov.ng<br>
                        Phone: +234 000 0000 000</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Follow Us</h5>
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <div class="row">
                    <div class="col-md-8 ">
                        <p class="mb-0">&copy; 2025 &mdash; Benue State Integrated Agricultural Data and Access Management System.</p>
                    </div>
                    <div class="col-md-4">
                         <a href="http://bdic.ng" target="_blank" class="powered_by_bdic">Powered by BDIC <img src="{{ asset('/dashboard/images/bdic_logo_small.png') }}" alt="BDIC"></a>
                    </div>

                </div>

            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
