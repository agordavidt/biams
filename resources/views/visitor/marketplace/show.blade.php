<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $listing->title }} - Benue Agro Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">

    <style>
        :root {
            --primary-green: #2e7d32;
            --secondary-green: #4caf50;
            --light-bg: #f5f7f5;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
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
            font-size: 1.1rem;
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
        }

        /* Product Container */
        .product-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-top: 2rem;
        }

        .product-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .placeholder-image {
            width: 100%;
            height: 350px;
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .product-container h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
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

        .product-container .text-muted {
            font-size: 0.95rem;
            font-weight: 400;
        }

        .product-container h5 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .product-container p {
            font-size: 1rem;
            font-weight: 400;
        }

        /* Sidebar */
        .sidebar-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 1.5rem;
        }

        .sidebar-card h5 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .sidebar-card p {
            font-size: 1rem;
            font-weight: 400;
        }

        .similar-item img, .similar-item .placeholder-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .similar-item h6 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .similar-item p {
            font-size: 1rem;
            font-weight: 400;
        }

        .similar-item small {
            font-size: 0.9rem;
            font-weight: 400;
        }

        /* Buttons */
        .btn-primary {
            background: var(--secondary-green);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: var(--primary-green);
            transform: translateY(-2px);
        }

        .btn-outline-success {
            font-size: 1rem;
            font-weight: 500;
        }

        /* Footer */
        .footer {
            background: #1a3c34;
            color: #e0e0e0;
            padding: 3rem 0;
            margin-top: 3rem;
        }

        .footer h5 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .footer p, .footer a {
            font-size: 1rem;
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

        /* Safety tips */
        .safety-tips {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .safety-tips h6 {
            color: #ff8f00;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .safety-tips ul {
            margin-bottom: 0;
            padding-left: 1.2rem;
        }

        .safety-tips li {
            margin-bottom: 0.3rem;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="logo-light">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
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

                        <!-- Safety Tips Section -->
                        <div class="safety-tips">
                            <h6><i class="fas fa-shield-alt me-2"></i>Safety Tips</h6>
                            <ul>
                                <li>Avoid paying in advance, even for delivery</li>
                                <li>Meet with the seller at a safe public place</li>
                                <li>Inspect the item and ensure it's exactly what you want</li>
                                <li>Make sure that the packed item is the one you've inspected</li>
                                <li>Only pay if you're satisfied</li>
                            </ul>
                            <p class="mt-2 mb-0 small text-muted fst-italic">Note: BSIADAMS is not responsible for any transactions between buyers and sellers.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
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
                    <p class="text-muted mb-3">
                        <i class="fas fa-phone me-1"></i> {{ $listing->contact}}
                    </p>
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
                    <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="logo-light">
                    <!-- <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="Benue Agro Market Logo" class="footer-logo"> -->
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
                <p class="mb-0">© 2025 Benue State Smart Agricultural System and Data Management<br>
                <a href="http://bdic.ng" target="_blank" class="powered_by_bdic">Powered by 
                    <img src="{{ asset('/dashboard/images/bdic_logo_small.png') }}" alt="BDIC">
                </a></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>