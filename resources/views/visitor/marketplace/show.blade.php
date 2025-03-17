<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $listing->title }} - Benue Agro Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="{{ asset('dashboard/images/favicon.jpg') }}" type="image/x-icon">

    <style>
        :root {
            --primary-green: #2e7d32;
            --secondary-green: #4caf50;
            --light-bg: #f5f7f5;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.6;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .product-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-top: 2rem;
        }

        .product-image {
            width: 100%;
            height: 350px; /* Consistent height */
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .placeholder-image {
            width: 100%;
            height: 350px; /* Matches product-image */
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .price-tag {
            background: var(--secondary-green);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 1.2rem;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .category-badge {
            background: #e8f5e9;
            color: var(--primary-green);
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        .sidebar-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 1.5rem;
        }

        .similar-item img, .similar-item .placeholder-image {
            width: 80px;
            height: 80px; /* Consistent size for similar items */
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-primary {
            background: var(--secondary-green);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-green);
            transform: translateY(-2px);
        }

        .footer {
            background: #1a3c34;
            color: #e0e0e0;
            padding: 3rem 0;
            margin-top: 3rem;
        }

        .contact-details {
            display: none; /* Hidden by default */
            margin-top: 1rem;
        }

        .contact-details.show {
            display: block; /* Shown when authorized */
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-leaf me-2" style="padding-right:120px; font-size: 2rem;"></i>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto" style="font-weight: bold;">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('/')}}">Home</a> </li>
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

    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('visitor.marketplace') }}" class="text-decoration-none text-success">
                <i class="fas fa-arrow-left me-2"></i> Back to Marketplace
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <!-- Main Product Details -->
            <div class="col-lg-8">
                <div class="product-container">
                    <div class="row">
                        <div class="col-md-6">
                            @if($listing->image)
                                <img src="{{ asset('storage/' . $listing->image) }}" class="product-image" alt="{{ $listing->title }}">
                            @else
                                <div class="placeholder-image">
                                    <i class="fas fa-leaf fa-5x text-success"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h2 class="fw-bold text-success mb-3">{{ $listing->title }}</h2>
                            <div class="price-tag">
                                ₦{{ number_format($listing->price) }}
                                @if($listing->unit)
                                    / {{ $listing->unit }}
                                @endif
                            </div>
                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $listing->location }}
                            </p>
                            <span class="category-badge">{{ $listing->category->name }}</span>
                            @if($listing->quantity > 0)
                                <p class="mt-3">Available: {{ $listing->quantity }} {{ $listing->unit }}</p>
                            @endif
                            <p class="text-muted small">Listed: {{ $listing->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h5 class="fw-bold text-success">Description</h5>
                        <p>{{ $listing->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Seller Info & Similar Listings) -->
            <div class="col-lg-4">
                <div class="sidebar-card mb-4">
                    <h5 class="fw-bold text-success mb-3">Seller Information</h5>
                    <p class="fw-bold">{{ $seller->name }}</p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt me-1"></i> {{ $listing->location }}
                    </p>
                    <p class="text-muted mb-3">
                        <i class="fas fa-user-check me-1"></i> Since {{ $seller->created_at->format('M Y') }}
                    </p>
                    @auth
                        <button class="btn btn-primary w-100" onclick="showContact()">Show Contact</button>
                        <div id="contactDetails" class="contact-details">
                            <p class="mt-2"><i class="fas fa-envelope me-1"></i> {{ $seller->email }}</p>
                            <!-- Add phone if available in your User model -->
                            <a href="{{ route('marketplace.messages.conversation', ['listing' => $listing, 'partner_id' => $listing->user_id]) }}" 
                               class="btn btn-outline-success w-100 mt-2">
                                <i class="fas fa-envelope me-2"></i> Message Seller
                            </a>
                        </div>
                    @else
                        <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i> Show Contact
                        </a>
                    @endauth
                </div>

                @if($similarListings->count() > 0)
                    <div class="sidebar-card">
                        <h5 class="fw-bold text-success mb-3">Similar Listings</h5>
                        @foreach($similarListings as $similarListing)
                            <a href="{{ route('visitor.marketplace.show', $similarListing) }}" class="d-flex align-items-center mb-3 text-decoration-none similar-item">
                                @if($similarListing->image)
                                    <img src="{{ asset('storage/' . $similarListing->image) }}" alt="{{ $similarListing->title }}">
                                @else
                                    <div class="placeholder-image" style="width: 80px; height: 80px;">
                                        <i class="fas fa-leaf text-success"></i>
                                    </div>
                                @endif
                                <div class="ms-3">
                                    <h6 class="mb-1 text-dark">{{ $similarListing->title }}</h6>
                                    <p class="text-success mb-0">₦{{ number_format($similarListing->price) }}</p>
                                    <small class="text-muted">{{ $similarListing->location }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold">Benue Agro Market</h5>
                    <p>Empowering farmers, connecting markets</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Contact</h5>
                    <p>Email: info@bsiadams.gov.ng<br>
                       Phone: +234 000 0000 000</p>
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
                <p class="mb-0">© 2025 Benue State Integrated Agricultural Data and Access Management System<br>
                <a href="http://bdic.ng" target="_blank" class="text-light">Powered by BDIC</a></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showContact() {
            const contactDetails = document.getElementById('contactDetails');
            contactDetails.classList.toggle('show');
        }
    </script>
</body>
</html>