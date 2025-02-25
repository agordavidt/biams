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
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.7)), url('{{ asset('dashboard/images/farm-background.jpg') }}');
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

{{-- <img src="{{ asset('dashboard/images/bsiadams_logo.png') }}" style="border-radius: 1em"
                            alt="farm_produce" height="50"> --}}

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
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/')}}">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'about') }}">About</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'services') }}">Services</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'visitor.marketplace') }}">Market</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'contact') }}">Contact</a> </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'login') }}">PORTAL</a> </li>
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

<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('visitor.marketplace') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Marketplace
        </a>
    </div>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-5 mb-4">
            <div class="card" style="border: thin solid rgb(89, 122, 89);">
                @if($listing->image)
                    <img src="{{ asset('storage/' . $listing->image) }}" class="card-img-top" alt="{{ $listing->title }}" style="max-height: 400px; object-fit: contain;">
                @else
                    <div class="bg-light text-center py-5" style="height: 400px;">
                        <i class="fas fa-leaf fa-5x text-success mt-5"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-7">
            <div class="card" style="border: thin solid rgb(89, 122, 89);">
                <div class="card-body">
                    <h2 class="card-title text-success mb-3">{{ $listing->title }}</h2>
                    
                    <div class="mb-3">
                        <h4 class="text-success">₦{{ number_format($listing->price) }} 
                            @if($listing->unit)
                                / {{ $listing->unit }}
                            @endif
                        </h4>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted">
                            <i class="fas fa-map-marker-alt me-2"></i> {{ $listing->location }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <p class="card-text">{{ $listing->description }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="card-text">
                            <strong>Category:</strong> {{ $listing->category->name }}
                        </p>
                    </div>
                    
                    @if($listing->quantity)
                    <div class="mb-3">
                        <p class="card-text">
                            <strong>Available Quantity:</strong> {{ $listing->quantity }} {{ $listing->unit }}
                        </p>
                    </div>
                    @endif

                    <div class="mb-3">
                        <p class="card-text">
                            <strong>Listed By:</strong> {{ $seller->name }}
                        </p>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> To contact the seller and see full details, please 
                        <a href="{{ route('login') }}" class="alert-link">login</a> or 
                        <a href="{{ route('register') }}" class="alert-link">register</a>.
                    </div>
                </div>
            </div>

            <!-- Similar Products -->
            <div class="card mt-4" style="border: thin solid rgb(89, 122, 89);">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Similar Products</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($similarListings as $similarListing)
                            <div class="col-md-6">
                                <div class="card h-100 shadow-sm">
                                    <div class="row g-0">
                                        <div class="col-4">
                                            @if($similarListing->image)
                                                <img src="{{ asset('storage/' . $similarListing->image) }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="{{ $similarListing->title }}">
                                            @else
                                                <div class="bg-light text-center h-100 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-leaf fa-2x text-success"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-8">
                                            <div class="card-body p-2">
                                                <h6 class="card-title">{{ Str::limit($similarListing->title, 40) }}</h6>
                                                <p class="card-text text-success">₦{{ number_format($similarListing->price) }}</p>
                                                <a href="{{ route('visitor.marketplace.show', $similarListing) }}" class="btn btn-sm btn-outline-success">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">No similar products found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="mt-5 py-4 text-center bg-light rounded" style="border: thin solid rgb(89, 122, 89);">
        <h3 class="text-success mb-3">Want to contact sellers directly?</h3>
        <p class="mb-4">Create an account or login to contact farmers and access all features.</p>
        <a href="{{ route('register') }}" class="btn btn-success btn-lg me-2">Register Now</a>
        <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg">Login</a>
    </div>
</div>


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

