@extends('layouts.frontend')

@section('title', 'Agricultural Data Portal | BSSADMS')

@section('content')
<!-- Page Title -->
<div class="page__title" style="background-image: url('{{ asset('frontend/assets/img/hero_pages.jpg') }}');">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page__title-content text-center pt-120 pb-80">
                    <h3 class="breadcrumb-title text-white mb-3">Agricultural Data Portal</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Overview -->
<div class="orgainc-product pt-120 pb-80 h2-gray-bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6 order-2 order-lg-1 mt-4 mt-lg-0">
                <div class="organic-product-content">
                    <div class="tp-section-wrap mb-30">
                        <span><i class="flaticon-statistics text-success"></i> Verified Intelligence</span>
                        <h3 class="tp-section-title mt-2">Benue’s Digital Agricultural Backbone</h3>
                        <p class="lead">
                            A centralized, geotagged, and NIN/BVN-verified database powering policy, investment, and extension services across the Food Basket of the Nation.
                        </p>
                    </div>

                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="flaticon-map text-success me-3" style="font-size: 1.8rem;"></i>
                                <div>
                                    <h6 class="mb-1">Geospatial Mapping</h6>
                                    <small class="text-muted">Every farm plot, GPS-verified</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="flaticon-user-1 text-success me-3" style="font-size: 1.8rem;"></i>
                                <div>
                                    <h6 class="mb-1">Farmer Registry</h6>
                                    <small class="text-muted">Identity-linked profiles</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="flaticon-wheat-1 text-success me-3" style="font-size: 1.8rem;"></i>
                                <div>
                                    <h6 class="mb-1">Crop Analytics</h6>
                                    <small class="text-muted">Yield, variety, and seasonality</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex">
                                <i class="flaticon-box text-success me-3" style="font-size: 1.8rem;"></i>
                                <div>
                                    <h6 class="mb-1">Input Tracking</h6>
                                    <small class="text-muted">From warehouse to farmer</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 order-1 order-lg-2">
                <div class="organic-image text-center">
                    <img src="{{ asset('frontend/assets/img/data_visualization.jpg') }}" 
                         class="img-fluid rounded shadow-lg" 
                         alt="Benue Agricultural Data Dashboard Preview" 
                         loading="lazy"
                         style="max-height: 420px;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Portal Coming Soon -->
<div class="company-features pt-80 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="text-center mb-50">
                    <h3 class="tp-section-title">Interactive Data Portal – Final Development</h3>
                    <p class="lead text-muted">
                        The public-facing agricultural data dashboard is undergoing final integration and security hardening.
                    </p>
                </div>

                <div class="bg-white p-5 rounded shadow border">
                    <div class="text-center mb-4">
                        <i class="flaticon-database display-1 text-success" style="opacity: 0.12;"></i>
                    </div>
                    <h5 class="mb-3">Public Launch: Q1 2026</h5>
                    <p class="text-muted">
                        Features include interactive maps, downloadable CSV/PDF reports, real-time yield forecasts, and LGA-level dashboards.
                    </p>
                    <hr class="my-4">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h3 class="text-success fw-bold">500,000+</h3>
                            <p class="text-muted small">Farmers to be Registered</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="text-success fw-bold">23</h3>
                            <p class="text-muted small">LGAs Fully Covered</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h3 class="text-success fw-bold">100%</h3>
                            <p class="text-muted small">Geotagged Farm Assets</p>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <p class="mb-3"><strong>Request Preview Access</strong> (Government, Researchers, Investors)</p>
                        <a href="{{ route('contact') }}" class="tp-btn-h1">
                            Apply for Early Access
                        </a>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-light rounded">
                    <p class="mb-0 small text-muted text-center">
                        <strong>Data Sources:</strong> Field agents, satellite imagery, soil sensors, cooperative records, and extension officer reports.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection