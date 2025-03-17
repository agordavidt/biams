<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benue State Agro Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="dashboard/images/favicon.jpg" type="image/x-icon">

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

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: none;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .product-card img {
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
            width: 100%;
            cursor: pointer;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card .card-body {
            padding: 1.5rem;
        }

        .price-tag {
            background: var(--secondary-green);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .category-badge {
            background: #e8f5e9;
            color: var(--primary-green);
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-size: 0.85rem;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .product-card h5 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .product-card .text-muted {
            margin-bottom: 0.75rem;
        }

        .footer {
            background: #1a3c34;
            color: #e0e0e0;
            padding: 3rem 0;
            margin-top: 3rem;
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
    </style>
</head>
<body>
    <!-- Navigation (Original) -->
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

    <div class="container mt-4">
        <!-- Filter Section (Retained) -->
        <div class="filter-card">
        <form action="{{ route('visitor.marketplace') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="location" value="{{ request('location') }}" placeholder="Location">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search products...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Product Listings (Updated) -->
        <div class="row g-4">
            @forelse($listings as $listing)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card h-100">
                        <div class="position-relative">
                            @if($listing->image)
                                <a href="{{ route('visitor.marketplace.show', $listing) }}">
                                    <img src="{{ asset('storage/' . $listing->image) }}" alt="{{ $listing->title }}">
                                </a>
                            @else
                                <a href="{{ route('visitor.marketplace.show', $listing) }}">
                                    <div class="bg-light text-center py-5">
                                        <i class="fas fa-leaf fa-3x" style="color: var(--primary-green);"></i>
                                    </div>
                                </a>
                            @endif
                            <span class="category-badge">
                                {{ $listing->category->name ?? 'Agro Product' }}
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $listing->title }}</h5>
                            <div class="price-tag">
                                ₦{{ number_format($listing->price) }}
                                @if($listing->unit)
                                    / {{ $listing->unit }}
                                @endif
                            </div>
                            <p class="text-muted small">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $listing->location }}
                            </p>
                            <p class="text-muted small">{{ Str::limit($listing->description, 80) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-tractor fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No products found. Try adjusting your filters.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $listings->appends(request()->query())->links() }}
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
</body>
</html>