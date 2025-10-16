<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Benue Agri-Data Platform: The Food Basket Reimagined')</title>
    <meta name="description" content="Official platform showcasing Benue State's verified agricultural data, farmer profiles, and investment opportunities.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/assets/img/favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/preloader.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper-bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/backToTop.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/flaticon/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/fontAwesome5Pro.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    
    @stack('styles')
    
    <style>
        body {
          font-family: 'Inter', sans-serif;
        }
        
        .banner-area {
          height: 70vh;
          display: flex;
          align-items: center;
          background-size: cover;
          background-position: center center;
          background-repeat: no-repeat;
          background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), url('{{ asset('frontend/assets/img/hero_pages.jpg') }}');
        }
        
        .banner-content * {
          color: white !important;
          text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.9);
        }
        
        .banner-button a.tp-btn-h1 {
          color: #FFFFFF !important;
          background-color: #007bff;
          border-color: #007bff;
          text-shadow: none;
        }
        
        .banner-title {
          font-size: 3rem;
          font-weight: 700;
        }
        
        .tp-btn-h1,
        .theme-bg,
        .theme-bg-primary-h1 {
          background-color: #38761D !important;
          border-color: #38761D !important;
          color: #ffffff !important;
        }
        
        .tp-btn-h1:hover {
          background-color: #2F5F17 !important;
          border-color: #2F5F17 !important;
        }
        
        .text-success,
        a:hover,
        .features-btn {
          color: #38761D !important;
        }
        
        .banner-icon i,
        .flaticon-statistics,
        .tp-section-wrap span i {
          color: #38761D !important;
        }
        
        img {
          border-radius: 8px;
        }
        
        .company-features-item {
          display: flex;
          flex-direction: column;
          height: 100%;
          border: 1px solid #e0e0e0;
          border-radius: 12px;
          overflow: hidden;
          transition: all 0.3s ease;
          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
          background-color: #ffffff;
        }
        
        .company-features-item:hover {
          box-shadow: 0 8px 25px rgba(56, 118, 29, 0.15);
          transform: translateY(-5px);
        }
        
        .features-item {
          flex-grow: 1;
          padding: 30px;
        }
        
        .features-item-btton {
          margin-top: auto;
          padding: 15px 30px;
          border-top: 1px solid #f0f0f0;
        }
        
        .features-item img {
          height: 60px;
          width: 60px;
          object-fit: cover;
          margin-bottom: 15px;
          border-radius: 50%;
          border: 3px solid #E6F0E3;
          padding: 5px;
        }
        
        .latest-blog {
          height: 100%;
          border-radius: 12px;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
          transition: all 0.3s ease;
          background-color: #ffffff;
          display: flex;
          flex-direction: column;
        }
        
        .latest-blog-content {
          flex-grow: 1;
          padding: 20px;
          display: flex;
          flex-direction: column;
        }
        
        .blog-btn {
          margin-top: auto;
        }
        
        .footer-top-2 {
          padding-top: 40px !important;
          padding-bottom: 20px !important;
        }
        
        .footer-top .footer-widget {
          margin-bottom: 20px !important;
        }
        
        .footer-top .footer-widget p {
          margin-bottom: 10px;
          font-size: 14px;
        }
        
        .footer-top .footer-title-h1 {
          margin-bottom: 15px !important;
        }
        
        .footer-top .footer-menu-2 ul li a {
          padding-top: 3px;
          padding-bottom: 3px;
          font-size: 14px;
        }
        
        .copy-right-area {
          padding-top: 15px !important;
          padding-bottom: 15px !important;
        }
        
        .copy-right-text-1 {
          margin-bottom: 0 !important;
        }
        
        .page__title {
          background-image: url('{{ asset('frontend/assets/img/hero_pages.jpg') }}') !important;
          background-size: cover !important;
          background-position: center center !important;
          background-repeat: no-repeat !important;
          position: relative;
          z-index: 1;
        }
        
        .page__title::before {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.4);
          z-index: -1;
        }
        
        .page__title-content,
        .page_title__bread-crumb {
          position: relative;
          z-index: 2;
        }
        
        .page__title .breadcrumb-title,
        .page__title .breadcrumb-trail span,
        .page__title .breadcrumb-trail a span {
          color: #ffffff;
        }
        
        .map-wrapper {
          position: relative;
          width: 100%;
          padding-bottom: 56.25%;
          height: 0;
          overflow: hidden;
        }
        
        .map-wrapper iframe {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          border: 0;
        }

        .hero-title {
    line-height: 1.2;
    margin-bottom: 20px;
}

.hero-text {
    line-height: 1.6;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 32px;
        line-height: 1.1;
        padding-right: 20px;
        padding-left: 20px;
    }

    .hero-text {
        font-size: 14px;
        line-height: 1.4;
        padding-right: 15px;
        padding-left: 15px;
        max-width: 90%;
        margin-left: auto;
        margin-right: auto;
    }

    .index-banner-area {
        padding-top: 80px;
        padding-bottom: 80px;
    }
}
    </style>
</head>
<body>
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <!-- Header -->
    <header>
        <div class="header__area header-area-white">
            <div class="header__area-top-bar header-2-top-bar theme-bg-primary-h1">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-6 col-lg-6 col-md-8 col-sm-6">
                            <div class="headerinfo">
                                <ul>
                                    <li><a href="mailto:agridata@benue.gov.ng"><i class="fal fa-envelope"></i>agridata@benue.gov.ng</a></li>
                                    <li class="d-none d-md-inline-block"><a href=""><i class="fal fa-map-marker-alt"></i>Ministry of Agriculture, Makurdi</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-4 col-sm-6"></div>
                    </div>
                </div>
            </div>
            <div class="header-white-area theme-bg-secondary-h1" id="header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3 col-md-8 col-8">
                            <div class="logo">
                                <a href="">
                                    <img src="{{ asset('frontend/assets/img/benue_logo.jpeg') }}" alt="Benue State Agri-Data Management System Logo" loading="lazy" style="height: 40px;">
                                    <span class="text-success">BSSADMS</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 col-md-4 col-4 d-flex align-items-center justify-content-end">
                            <div class="main-menu-h1 main-menu main-menu-white text-center">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li><a href="{{ url('/') }}" class="active">Home</a></li>
                                         <li><a href="{{ route('about') }}">About Us</a></li>  
                                        <li><a href="">Data</a></li>                                        
                                        <li><a href="{{ route('marketplace.index') }}">Marketplace</a></li>     
                                        <li><a href="">News</a></li>
                                        <li><a href="{{ route('contact') }}">Contact</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="side-menu-icon d-lg-none text-end">
                                <a href="javascript:void(0)" class="info-toggle-btn f-right sidebar-toggle-btn"><i class="fal fa-bars"></i></a>
                            </div>
                            <div class="header-cta">
                                <div class="phone-number">
                                    <a href="{{ route('login') }}" class="tp-btn-h1">Visit Portal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="sidebar__area">
        <div class="sidebar__wrapper">
            <div class="sidebar__close">
                <button class="sidebar__close-btn" id="sidebar__close-btn">
                    <i class="fal fa-times"></i>
                </button>
            </div>
            <div class="sidebar__content">
                <div class="sidebar__logo mb-40">
                    <a href="">
                        <img src="{{ asset('frontend/assets/img/benue_logo.jpeg') }}" alt="Benue State Agri-Data Management System Logo" loading="lazy" style="height: 40px;">
                        <span class="text-success">BSSADMS</span>
                    </a>
                </div>
                <div class="mobile-menu fix"></div>
                <div class="sidebar__contact mt-30 mb-20">
                    <ul>                   
                        <li class="d-flex align-items-center">                        
                            <div class="sidebar__contact-text">
                                <a href="{{ route('login') }}" class="tp-btn-h1">Visit Portal</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="sidebar__social">
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-top footer-top-2 pt-40 pb-20">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <div class="footer-widget footer-col-1 mb-20">
                            <div class="footer-logo mb-15">
                                <a href="">
                                    <img src="{{ asset('frontend/assets/img/benue_logo.jpeg') }}" alt="Benue State Agri-Data Management System Logo" loading="lazy" style="height: 40px;">
                                    <span class="text-success">BSSADMS</span>
                                </a>
                            </div>
                            <p>The official data platform of the Benue State Ministry of Agriculture. We provide verifiable data and geospatial mapping to guide policy and attract strategic investors.</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                        <div class="footer-widget footer-col-5 mb-20">
                            <h5 class="footer-title-h1 footer-title mb-20 footer-sm-title">Key Resources</h5>
                            <div class="footer-menu footer-menu-2">
                                <ul>
                                    <li><a href="">Agricultural Statistics</a></li>
                                    <li><a href="">Geospatial Map</a></li>
                                    <li><a href="">Support Programs</a></li>
                                    <li><a href="">Cooperative Listings</a></li>
                                    <li><a href="">News & Events</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-4 col-sm-6">
                        <div class="footer-widget footer-col-5 mb-20">
                            <h5 class="footer-title-h1 footer-title mb-20 footer-sm-title">Quick Links</h5>
                            <ul class="footer-menu-1 footer-menu footer-menu-2 footer-menu-c">
                                <li><a href="">Project Overview</a></li>
                                <li><a href="{{ route('contact') }}">Contact Support</a></li>
                                <li><a href="">Privacy Policy</a></li>
                                <li><a href="">Farmer Portal Login</a></li>
                                <li><a href="">Admin Login</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copy-right-area theme-bg-common pt-15 pb-15">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                        <p class="mb-0 copy-right-text-1">
                            © <script>document.write(new Date().getFullYear())</script> BSSADMS. Powered by 
                            <a href=""><img src="{{ asset('frontend/assets/img/bdic_logo_small.png') }}" alt="BDIC Logo" loading="lazy" style="height: 20px; vertical-align: middle;"></a>
                        </p>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JS Scripts -->
    <script src="{{ asset('frontend/assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/vendor/waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/meanmenu.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/parallax.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/backToTop.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nice-select.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/counterup.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.knob.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
    
    @stack('scripts')
</body>
</html>