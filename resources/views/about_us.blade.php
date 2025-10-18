@extends('layouts.frontend')

@section('title', 'About Us - Benue Agri-Data Platform')

@section('content')
    <!-- Page Title -->
    <div class="page__title align-items-center theme-bg-primary-h1 pt-140 pb-140">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="page__title-content text-center">
                        <div class="page_title__bread-crumb">
                            <nav aria-label="breadcrumb">
                                <ul class="breadcrumb-trail breadcrumbs">
                                    <li><a href="{{ route('home') }}"><span>Home</span></a></li>
                                    <li class="trail-item trail-end"><span>About Us</span></li>
                                </ul>
                            </nav>
                        </div>
                        <h3 class="breadcrumb-title breadcrumb-title-sd mt-30">About The Platform</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Area -->
    <div class="about-area pt-120 pb-110">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="tp-section-wrap">
                        <span class="asub-title grace-span">- Our Story: Digital Transformation</span>
                        <h3 class="tp-section-title">Modernizing Benue's Agriculture with Data Intelligence</h3>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="about-info">
                        <p>The Benue State Ministry of Agriculture Data Management System (BSSADMS) is the flagship digital platform of the Ministry, designed to transform the state's agricultural 
                         sector into Africa's leading data hub. Our vision is a fully digital ecosystem where every farmer is verified, every input is tracked, and every opportunity is data-driven.</p>
                        <p>Launched in 2024, BSSADMS addresses critical challenges like fragmented data, inefficient program distribution, and limited market access. By integrating 
                         geospatial mapping, AI analytics, and mobile tools, we empower our vast network of farmers to increase yields and attract investments exceeding NGN 5 Billion annually.</p>
                        <p>We are committed to making Benue The Food Basket of the Nation a reality through transparency and verifiable data.</p>
                        <div class="about-button mt-30">
                            <a href="{{ route('contact') }}" class="tp-btn-ab">Get In Touch <i class="fal fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Features -->
    <div class="company-features pt-120 pb-90">
        <div class="container">
            <div class="tp-section-wrap text-center">                     
                <h3 class="tp-section-title">Strategic Pillars of the BSSADMS</h3>
                <p>Building a robust ecosystem for transparent policy, strategic investment, and farmer success.</p>
            </div>
            <div class="company-features-list mt-50">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="company-features-item mb-30">
                            <div class="features-item text-center">
                                <img src="{{ asset('frontend/assets/img/verified_database.jpg') }}" alt="Icon of verified farmer database" loading="lazy">
                                <h4 class="features-item-title">Verified Database</h4>
                                <p>NIN-linked farmer profiles and **geo-tagged farm boundaries** for accurate planning and precise program targeting.</p>
                            </div>
                            <div class="features-item-btton">
                                <a href="data-insights.html" class="features-btn">Explore Data <i class="fal fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="company-features-item mb-30">
                            <div class="features-item text-center">
                                <img src="{{ asset('frontend/assets/img/transparent_dist.jpg') }}" alt="Icon of transparent input distribution" loading="lazy">
                                <h4 class="features-item-title">Transparent Distribution</h4>
                                <p>Track subsidies, fertilizer, and logistics in real-time, completely eliminating fraudulent claims and ghost beneficiaries.</p>
                            </div>
                            <div class="features-item-btton">
                                <a href="how-it-works.html" class="features-btn">See Programs <i class="fal fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="company-features-item mb-30">
                            <div class="features-item text-center">
                                <img src="{{ asset('frontend/assets/img/investment_growth.jpg') }}" alt="Icon of cooperative growth and investment" loading="lazy">
                                <h4 class="features-item-title">Investment & Growth</h4>
                                <p>Provide investors with verified performance metrics** and forecasts to facilitate Public-Private Partnerships (PPP) and market growth.</p>
                            </div>
                            <div class="features-item-btton">
                                <a href="investor-hub.html" class="features-btn">Invest Now <i class="fal fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection