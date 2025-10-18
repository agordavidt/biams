@extends('layouts.frontend')

@section('title', 'Benue Agri-Data Platform: The Food Basket Reimagined')

@section('content')
    <!-- Banner -->
    <div class="banner-area">
        <div class="container">
            <div class="row justify-content-start">
                <div class="col-xl-7 col-lg-7 col-md-9 col-12">
                    <div class="banner-content banner-content-2">
                        <div class="banner-info text-center">
                            <div class="banner-icon">
                                <i class="flaticon-statistics"></i>
                            </div>
                            <p>Transforming Benue Agriculture Through Data & Innovation</p>
                            <h3 class="banner-title-h1 banner-title">Empowering Farmers, Boosting Productivity, Driving Growth</h3>
                            <div class="banner-button mt-30">
                                <a href="{{ route('login') }}" class="tp-btn-h1 ms-2">Explore Marketplace</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Area -->
    <div class="tp-about-area about-area-2 pt-110 pb-45">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-4 col-xl-4 col-lg-4">
                    <div class="tp-section-wrap">                   
                        <h3 class="tp-section-title">The Digital Gateway to Benue's Agri-Future</h3>
                    </div>
                </div> 
                <div class="col-xxl-7 col-xl-7 col-lg-7 align-items-end">
                    <div class="tp-about-content-1">
                        <p>Benue State, the Food Basket of the Nation, is revolutionizing agriculture with BSSADMS. Our platform captures verified farmer data, enables transparent input distribution, and provides real-time analytics for better decision-making. From registration to marketplace sales, we empower over 500,000 farmers across 23 LGAs.</p>
                        <p class="mt-15">Join us in fostering inclusive growth, attracting investments, and ensuring food security through technology.</p>
                        <div class="author-info mt-20">
                            <div class="author-content">
                                <h5>Benue State Ministry of Agriculture & Natural Resources</h5>
                                <span>Platform Custodian</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features List -->
    <div class="tp-features-list-area mb-90">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-xl-7 col-lg-8">
                    <div class="tp-features-list">
                        <div class="tp-list-item mb-30">
                            <i class="flaticon-user-1"></i>
                            <h5 class="features-title">Verified Farmer <br> Registry</h5>
                        </div>
                        <div class="tp-list-item mb-30">
                            <i class="flaticon-map"></i>
                            <h5 class="features-title">Geospatial <br> Intelligence</h5>
                        </div>
                        <div class="tp-list-item mb-30">
                            <i class="flaticon-sapling-1"></i>
                            <h5 class="features-title">Targeted MDA <br> Interventions</h5>
                        </div>
                        <div class="tp-list-item mb-30">
                            <i class="flaticon-money"></i>
                            <h5 class="features-title">Investment <br> Opportunities Showcased</h5>
                        </div>
                        <div class="tp-list-item mb-30">
                            <i class="flaticon-hand-shake"></i>
                            <h5 class="features-title">Buyer-Farmer <br> Produce Linkages</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organic Product -->
    <div class="orgainc-product pt-120 pb-120 h2-gray-bg position-relative">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6">
                    <div class="organic-image">
                        <img src="{{ asset('frontend/assets/img/fish_girl.jpg') }}" class="img-fluid" alt="Satellite imagery overlaying farmland" loading="lazy">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="organic-product-content pl-80 mt-50">
                        <div class="tp-section-wrap">
                            <span><i class="flaticon-statistics"></i></span>
                            <h3 class="tp-section-title">Verifiable Data: The New Foundation of Trust</h3>
                            <p>Serious investment requires certainty. The Benue Agri-Data Platform moves beyond estimates, providing geospatially validated data on crops, farm yields, asset distribution, and farmer identity.</p>
                        </div>
                        <h5 class="organic-product-title mt-40">Guaranteed Data Integrity</h5>
                        <div class="row g-0">
                            <div class="col-xl-6 col-lg-6">
                                <p class="organic-features-info">We ensure every data point is linked to a unique farmer profile and geotagged farm asset, mitigating fraud and de-risking high-value policy planning and private investment.</p>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="organic-features-list">
                                    <a href="data-guide.html">- Geospatial Asset Mapping</a>
                                    <a href="data-guide.html">- NIN/BVN-Linked Farmer Profiles</a>
                                    <a href="data-guide.html">- Real-time Intervention Monitoring</a>
                                    <a href="data-guide.html">- Policy-Driven Data Standardization</a>
                                </div>
                            </div>
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
                <h3 class="tp-section-title">The Pillars of Benue Agri-Strategy</h3>
                <p>Our mandate is to centralize information, enabling precise policy implementation and direct market access, transforming potential into prosperity.</p>
            </div>
            <div class="company-features-list mt-50">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="company-features-item mb-30">
                            <div class="features-item text-center">
                                <img src="{{ asset('frontend/assets/img/icon_image.jpg') }}" alt="Analytics icon" loading="lazy">
                                <h4 class="features-item-title">Data & Policy Analytics</h4>
                                <p>Access verifiable data on yield gaps, soil health, and production forecasts to guide strategic state and federal interventions (e.g., RAAMP, ACReSAL).</p>
                            </div>
                            <div class="features-item-btton">
                                <a href="data-portal.html" class="features-btn">View Data Portal <i class="fal fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="company-features-item mb-30">
                            <div class="features-item text-center">
                                <img src="{{ asset('frontend/assets/img/data_anlytics.jpg') }}" alt="Handshake icon" loading="lazy">
                                <h4 class="features-item-title">Intervention Transparency Hub</h4>
                                <p>Track the reach and impact of all State and Partner programs (IFAD, BNARDA, BENTHA) ensuring resources reach the last mile farmer, not ghost accounts.</p>
                            </div>
                            <div class="features-item-btton">
                                <a href="interventions.html" class="features-btn">Track Interventions <i class="fal fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="company-features-item mb-30">
                            <div class="features-item text-center">
                                <img src="{{ asset('frontend/assets/img/icon_image.jpg') }}" alt="Market cart icon" loading="lazy">
                                <h4 class="features-item-title">Direct Market Linkage</h4>
                                <p>Connect validated buyers directly to verified farmer cooperatives. Secure bulk off-take deals for **Yam, Cassava, Maize, and Soybean** at verifiable source prices.</p>
                            </div>
                            <div class="features-item-btton">
                                <a href="{{ route('login') }}" class="features-btn">Go to Marketplace <i class="fal fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Area -->
    <div class="video-area-2 position-relative mt-50">
        <div class="video-area play-area" style="background-image: url('{{ asset('frontend/assets/img/survey_1.jpg') }}');"></div>
        <div class="row g-0 justify-content-end">
            <div class="col-xl-6 col-lg-6 video-col col-md-6 col-12">
                <div class="video-box theme-bg pt-120 pb-90">
                    <div class="video-content pl-120">
                        <div class="tp-section-wrap tp-section-wrap-video">                        
                            <h3 class="tp-section-title">Empowering Data-Driven Agricultural Policy</h3>
                            <p>Our platform provides Benue State administrators with the verified, actionable intelligence needed to move beyond assumption and toward precision policy-making.</p>
                        </div>
                        <div class="video-features-list mt-50">
                            <div class="row">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-6">
                                    <div class="video-features-item mb-30">
                                        <i class="flaticon-save"></i>
                                        <h5 class="video-features-title">Targeted Subsidies</h5>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-6">
                                    <div class="video-features-item mb-30">
                                        <i class="flaticon-digging"></i>
                                        <h5 class="video-features-title">Precision Land Use</h5>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-6">
                                    <div class="video-features-item mb-30">
                                        <i class="flaticon-wheat-1"></i>
                                        <h5 class="video-features-title">Improved Yield Forecast</h5>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-6">
                                    <div class="video-features-item mb-30">
                                        <i class="flaticon-box"></i>
                                        <h5 class="video-features-title">Streamlined Extension</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="video-bg-image">
                            <img src="{{ asset('frontend/assets/img/bg/bg-img-1.png') }}" alt="Background image" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest News -->
    <div class="latest-news-area-2 latest-news-area pt-120 pb-90 fix">
        <div class="container container-fluid">
            <div class="row">
                <div class="col-xl-4 col-lg-4">
                    <div class="tp-section-wrap blog-slider-content mb-30">                    
                        <h3 class="tp-section-title">System Insights & Guides</h3>
                        <p>Stay updated on platform enhancements, data collection best practices, and digital literacy guidance for all enrolled farmers and agents.</p>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8">
                    <div class="blog-slider blog-slider_active owl-carousel">
                        <div class="latest-blog mb-30">
                            <div class="latest-blog-img">
                                <a href="news-details.html"><img src="{{ asset('frontend/assets/img/news_2.jpg') }}" class="img-fluid" alt="Insights image" loading="lazy"></a>
                                <div class="top-catagory">
                                    <a href="shop-details.html" class="postbox__meta">data</a>
                                </div>
                            </div>
                            <div class="latest-blog-content">
                                <div class="latest-post-meta mb-15">
                                    <span class="blog-date"><a href="news-details.html">July 17, 2025</a></span>
                                </div>
                                <h3 class="latest-blog-title">
                                    <a href="news-details.html">Guide: Submitting Your Farm GPS Coordinates</a>
                                </h3>
                                <div class="blog-btn mt-20">
                                    <a href="">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="latest-blog mb-30">
                            <div class="latest-blog-img">
                                <a href="news-details.html"><img src="{{ asset('frontend/assets/img/poultry.jpg') }}" class="img-fluid" alt="Insights image" loading="lazy"></a>
                                <div class="top-catagory">
                                    <a href="shop-details.html" class="postbox__meta">verification</a>
                                </div>
                            </div>
                            <div class="latest-blog-content">
                                <div class="latest-post-meta mb-15">
                                    <span class="blog-date"><a href="news-details.html">november 21, 2025</a></span>
                                </div>
                                <h3 class="latest-blog-title">
                                    <a href="news-details.html">How LGA Admins Verify Farmer Data for Approval</a>
                                </h3>
                                <div class="blog-btn mt-20">
                                    <a href="">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="latest-blog mb-30">
                            <div class="latest-blog-img">
                                <a href="news-details.html"><img src="{{ asset('frontend/assets/img/news_1.jpg') }}" class="img-fluid" alt="Insights image" loading="lazy"></a>
                                <div class="top-catagory">
                                    <a href="shop-details.html" class="postbox__meta">enrollment</a>
                                </div>
                            </div>
                            <div class="latest-blog-content">
                                <div class="latest-post-meta mb-15">
                                    <span class="blog-date"><a href="">October 7, 2025</a></span>
                                </div>
                                <h3 class="latest-blog-title">
                                    <a href="news-details.html">Understanding the Role of the Enrollment Officer (EO)</a>
                                </h3>
                                <div class="blog-btn mt-20">
                                    <a href="">Read More</a>
                                </div>
                            </div>
                        </div>
                        <div class="latest-blog mb-30">
                            <div class="latest-blog-img">
                                <a href="news-details.html"><img src="{{ asset('frontend/assets/img/pigs_photo.jpg') }}" class="img-fluid" alt="Insights image" loading="lazy"></a>
                                <div class="top-catagory">
                                    <a href="shop-details.html" class="postbox__meta">data</a>
                                </div>
                            </div>
                            <div class="latest-blog-content">
                                <div class="latest-post-meta mb-15">
                                    <span class="blog-date"><a href="news-details.html">August 28, 2025</a></span>
                                </div>
                                <h3 class="latest-blog-title">
                                    <a href="news-details.html">Guide: Submitting Your Farm GPS Coordinates</a>
                                </h3>
                                <div class="blog-btn mt-20">
                                    <a href="">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection