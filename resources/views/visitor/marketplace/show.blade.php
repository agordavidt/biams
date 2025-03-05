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
        <a href="{{ route('visitor.marketplace') }}" class="text-decoration-none">
            <i class="fas fa-arrow-left me-2"></i> Back to Marketplace
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Product Image and Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            @if($listing->image)
                                <img src="{{ asset('storage/' . $listing->image) }}" class="img-fluid rounded" alt="{{ $listing->title }}">
                            @else
                                <div class="bg-light text-center py-5 rounded">
                                    <i class="fas fa-leaf fa-5x text-success"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h2 class="text-success">{{ $listing->title }}</h2>
                            <p class="fs-4 fw-bold text-success mb-3">₦{{ number_format($listing->price) }} 
                                @if($listing->unit)
                                    / {{ $listing->unit }}
                                @endif
                            </p>
                            
                            <div class="mb-3">
                                <span class="badge bg-success mb-2">{{ $listing->category->name }}</span>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $listing->location }}
                                </p>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-user"></i> Listed by: {{ $seller->name }}
                                </p>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> Listed: {{ $listing->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            
                            <!-- Available quantity -->
                            @if($listing->quantity > 0)
                                <p class="mb-1">Available Quantity: {{ $listing->quantity }} {{ $listing->unit }}</p>
                            @endif
                            
                            <!-- Status -->
                            <p class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> Available for purchase
                            </p>
                            
                            <!-- Contact Seller - Only login link for non-auth users -->
                            <!-- @auth
                                <a href="{{ route('marketplace.messages.conversation', ['listing' => $listing, 'partner_id' => $listing->user_id]) }}" 
                                   class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-envelope me-2"></i> Contact Seller
                                </a>
                            @else
                                <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login to Contact Seller
                                </a>
                            @endauth -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Description -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $listing->description }}</p>
                </div>
            </div>
        </div>
        
        <!-- Seller Information and Similar Listings -->
        <div class="col-md-4">
            <!-- Seller Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Seller Information</h5>
                </div>
                <div class="card-body">
                    <h5>{{ $seller->name }}</h5>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt"></i> {{ $listing->location }}
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-user-check"></i> Member since {{ $seller->created_at->format('M Y') }}
                    </p>
                    <p class="mb-0">
                        <strong>{{ $seller->marketplaceListings->where('availability', 'available')->count() }}</strong> active listings
                    </p>
                    
                    @auth
                        <a href="{{ route('marketplace.messages.conversation', ['listing' => $listing, 'partner_id' => $listing->user_id]) }}" 
                           class="btn btn-outline-success w-100 mt-3">
                            <i class="fas fa-envelope me-2"></i> Message Seller
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="btn btn-outline-success w-100 mt-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Login to Contact
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Similar Listings Card -->
            @if($similarListings->count() > 0)
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Similar Listings</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($similarListings as $similarListing)
                            <a href="{{ route('visitor.marketplace.show', $similarListing) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex">
                                    @if($similarListing->image)
                                        <img src="{{ asset('storage/' . $similarListing->image) }}" alt="{{ $similarListing->title }}" 
                                             style="width: 60px; height: 60px; object-fit: cover;" class="me-3 rounded">
                                    @else
                                        <div class="bg-light text-center rounded me-3" style="width: 60px; height: 60px;">
                                            <i class="fas fa-leaf text-success" style="line-height: 60px;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $similarListing->title }}</h6>
                                        <p class="text-success mb-0">₦{{ number_format($similarListing->price) }}</p>
                                        <small class="text-muted">{{ $similarListing->location }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
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

