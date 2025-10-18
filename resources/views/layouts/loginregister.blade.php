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

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>      
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