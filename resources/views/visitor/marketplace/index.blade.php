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
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="text-success">Agricultural Marketplace</h2>
            <p class="text-muted">Browse agricultural products from registered farmers across Benue State.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('login') }}" class="btn btn-success">Login to Contact Sellers</a>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="card mb-4" style="border: thin solid rgb(89, 122, 89);">
        <div class="card-body">
            <form action="{{ route('visitor.marketplace') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ request('location') }}" placeholder="Enter location">
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search for products">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Listings -->
    <div class="row g-4">
        @forelse($listings as $listing)
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 feature-card" style="border: thin solid rgb(89, 122, 89);">
                    @if($listing->image)
                        <img src="{{ asset('storage/' . $listing->image) }}" class="card-img-top" alt="{{ $listing->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light text-center py-5">
                            <i class="fas fa-leaf fa-3x text-success"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $listing->title }}</h5>
                        <p class="card-text text-success fw-bold">₦{{ number_format($listing->price) }} 
                            @if($listing->unit)
                                / {{ $listing->unit }}
                            @endif
                        </p>
                        <p class="card-text text-muted small">
                            <i class="fas fa-map-marker-alt"></i> {{ $listing->location }}
                        </p>
                        <p class="card-text">{{ Str::limit($listing->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Listed by: {{ $listing->user->name }}</small>
                            <a href="{{ route('visitor.marketplace.show', $listing) }}" class="btn btn-sm btn-outline-success">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No products available at the moment. Please check back later or adjust your filters.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $listings->appends(request()->query())->links() }}
    </div>

    <!-- Call to Action Section -->
    <div class="mt-5 py-4 text-center bg-light rounded" style="border: thin solid rgb(89, 122, 89);">
        <h3 class="text-success mb-3">Are you a farmer looking to sell your products?</h3>
        <p class="mb-4">Join our platform to list your products and connect with buyers across Benue State.</p>
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

