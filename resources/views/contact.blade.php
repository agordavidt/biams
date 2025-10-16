@extends('layouts.frontend')

@section('title', 'Contact - Benue Agri-Data Platform')

@section('content')
    <!-- Page Title -->
    <div class="page__title align-items-center theme-bg-primary-h1 pt-140 pb-140">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="page__title-content text-center">
                        <div class="page_title__bread-crumb">
                            <nav aria-label="breadcrumb">
                                <nav aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs">
                                    <ul>
                                        <li>
                                            <a href="{{ route('home') }}"><span>Home</span></a>
                                        </li>
                                        <li class="trail-item trail-end">
                                            <span>Contact</span>
                                        </li>
                                    </ul>
                                </nav> 
                            </nav>
                        </div>
                        <h3 class="breadcrumb-title breadcrumb-title-sd mt-30">Contact Us</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Area -->
    <div class="tp-contact-area pt-115">
        <div class="container">
            <div class="row">
                <div class="col-lg-10">
                    <div class="tp-section-wrap">                            
                        <h3 class="tp-section-title">Get in Touch with our Support Team</h3>
                    </div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-lg-4">
                    <div class="row custom-mar-20">
                        <div class="col-lg-12 col-md-4 col-sm-6">
                            <div class="tp-contact-info mb-40">
                                <div class="tp-contact-info-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="tp-contact-info-text">
                                    <h4 class="tp-contact-info-title mb-15">Official Address</h4>
                                    <p><a href="https://maps.app.goo.gl/YourMapLinkHere" target="_blank">Benue State Ministry of Agriculture<br>Makurdi, Benue State, Nigeria.</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-4 col-sm-6">
                            <div class="tp-contact-info mb-40">
                                <div class="tp-contact-info-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="tp-contact-info-text">
                                    <h4 class="tp-contact-info-title mb-15">Support Hotline</h4>
                                    <p><a href="tel:+2348001112222">+234 (0) 800 111 2222</a>
                                    <br> <a href="tel:+2348003334444">+234 (0) 800 333 4444</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-4 col-sm-6">
                            <div class="tp-contact-info mb-40">
                                <div class="tp-contact-info-icon">
                                    <i class="fas fa-envelope-open"></i>
                                </div>
                                <div class="tp-contact-info-text">
                                    <h4 class="tp-contact-info-title mb-15">Email Support</h4>
                                    <p><a href="mailto:support@bssadms.ng">support@bssadms.ng</a>
                                    <br> <a href="mailto:info@bssadms.ng">info@bssadms.ng</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="tp-contact-form">
                        <form method="POST" action="#">
                            @csrf
                            <div class="row custom-mar-20">
                                <div class="col-md-6 custom-pad-20">
                                    <div class="tp-contact-form-field mb-20">
                                        <input type="text" name="name" placeholder="Full name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 custom-pad-20">
                                    <div class="tp-contact-form-field mb-20">
                                        <input type="email" name="email" placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="col-md-6 custom-pad-20">
                                    <div class="tp-contact-form-field mb-20">
                                        <input type="text" name="phone" placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-md-6 custom-pad-20">
                                    <div class="tp-contact-form-field select-field-arrow mb-20">
                                        <select name="subject" required>
                                            <option value="">Choose Subject</option>
                                            <option value="Data Inquiry">Agricultural Data Inquiry</option>
                                            <option value="Geospatial Map Query">Geospatial Map/Field Query</option>
                                            <option value="Program Support">Government Support Programs</option>
                                            <option value="Farmer/Coop Registration">Farmer/Cooperative Registration</option>
                                            <option value="General Inquiry">General Ministry Inquiry</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 custom-pad-20">
                                    <div class="tp-contact-form-field mb-20">
                                        <textarea name="message" placeholder="Your Message" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 custom-pad-20">
                                    <div class="tp-contact-form-field">
                                        <button type="submit" class="read-btn sumit-btn"><i class="flaticon-enter"></i> Send Message</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>  

    <!-- Map Area -->
    <div class="tp-map-area pt-115 pb-110">
        <div class="container-fluid px-0">
            <div class="map-wrapper">
                <iframe 
                    src="https://www.google.com/maps/embed/v1/place?q=Ministry%20of%20Agriculture%20and%20Natural%20Resources%2CMakurdi%2CBenue%20State%20PGMJ%2BXC9%2C%20Kashim%20Ibrahim%20Road%2C%20Makurdi%2C%20970101%2C%20Benue&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8" 
                    allowfullscreen 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
@endsection