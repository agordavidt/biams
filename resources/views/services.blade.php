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

    <!-- CSS FILES START -->
    {{-- <link href=" {{ asset('dashboard/css/css/custom.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('dashboard/css/css/color.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('dashboard/css/css/responsive.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('dashboard/css/css/owl.carousel.min.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('dashboard/css/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('dashboard/css/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/css/all.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('dashboard/css/css/slick.css') }}" rel="stylesheet">

    <!-- CSS FILES End -->

    <style>
        .powered_by_bdic {
            text-decoration: none;
            color: rgb(241, 80, 112);
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.7)), url('{{ asset('dashboard/images/portalbg.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 200px 10px 10px 10px;
        }



        /*********************************
Inner Header Start
*********************************/

        .inner-header {
            background: url('{{ asset('dashboard/images/portalbg.jpg') }}') no-repeat;
            padding: 100px 0;
            width: 100%;
            float: left;
        }

        .inner-header h1 {
            color: #fff;
            margin: 0 0 20px;
            font-weight: 700;
        }

        .inner-header ul {
            margin: 0px;
            padding: 0px;
            list-style: none;
        }

        .inner-header ul li {
            display: inline-block;
            font-family: 'Poppins', sans-serif;
        }

        .inner-header ul li:after {
            content: " : : ";
            color: #fff;
            margin: 0 10px;
            font-size: 24px;
        }

        .inner-header ul li:last-child:after {
            display: none;
        }

        .inner-header ul li a {
            color: #fff;
            font-weight: 400;
            font-size: 24px;
            text-decoration: none;
        }

        /*********************************
Inner Header End
*********************************/



        /* =========== about us slick slide starts ======================== */

        .timeline {
            text-align: left;
        }

        .timeline img {
            border-radius: 3px;
            width: 100%;
            height: auto;
        }

        .timeline-nav {
            position: relative;
            padding: 10px 0;
        }

        .slick-list {
            z-index: 99;
        }

        .timeline-nav:after {
            background: #cccccc;
            height: 8px;
            border-radius: 5px;
            position: absolute;
            width: 100%;
            content: "";
            left: 0;
            bottom: 28px;
            z-index: 1;
        }

        .timeline-nav .slick-slide {
            position: relative;
            height: 88px;
            cursor: pointer;
        }

        .timeline-nav .slick-slide strong {
            font-size: 22px;
            line-height: 22px;
            font-weight: 400;
            color: #333333;
            position: relative;
            display: block;
            text-align: center;
        }

        .timeline-nav .slick-slide strong:after {
            position: absolute;
            left: 0;
            right: 0;
            height: 8px;
            width: 100%;
            content: "";
            bottom: -48px;
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#cccccc+0,66bb6a+50,cccccc+100 */
            background: rgb(204, 204, 204);
            /* Old browsers */
            background: -moz-linear-gradient(left, rgba(204, 204, 204, 1) 0%, rgba(102, 187, 106, 1) 50%, rgba(204, 204, 204, 1) 100%);
            /* FF3.6-15 */
            background: -webkit-linear-gradient(left, rgba(204, 204, 204, 1) 0%, rgba(102, 187, 106, 1) 50%, rgba(204, 204, 204, 1) 100%);
            /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to right, rgba(204, 204, 204, 1) 0%, rgba(102, 187, 106, 1) 50%, rgba(204, 204, 204, 1) 100%);
            /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#cccccc', endColorstr='#cccccc', GradientType=1);
            /* IE6-9 */
            opacity: 0;
        }

        .timeline-nav .slick-slide:after {
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
            height: 30px;
            width: 30px;
            line-height: 24px;
            border: 3px solid #ccc;
            border-radius: 100%;
            text-align: center;
            z-index: 9;
            bottom: 0px;
            background: #fff;
            content: "";
            bottom: 7px;
        }

        .timeline-nav .slick-slide:before {
            position: absolute;
            left: 0;
            right: 0;
            background: #ccc;
            content: "";
            width: 14px;
            height: 14px;
            border-radius: 30px;
            margin: auto;
            bottom: 15px;
            z-index: 99;
        }

        .timeline-nav .slick-slide span:after {
            width: 8px;
            height: 8px;
            background: #ccc;
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
            content: "";
            border-radius: 10px;
            bottom: 50px;
        }

        .timeline-nav .slick-slide span:before {
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
            bottom: 35px;
            width: 2px;
            height: 15px;
            background: #ccc;
            content: "";
        }

        .slick-slide.slick-current:after {
            border-color: #66bb6a;
            background: #66bb6a;
        }

        .slick-slide.slick-current:before {
            background: #fff;
        }

        .slick-slide.slick-current span:after,
        .slick-slide.slick-current span:before {
            border-color: #66bb6a;
            background: #66bb6a;
            color: #fff;
        }

        .slick-slide.slick-current strong:after {
            opacity: 1;
        }

        .slick-slide.slick-current strong {
            color: #66bb6a;
        }

        .timeline .slick-slide h3 {
            margin-bottom: 19px;
        }

        .timeline .slick-slide p {
            margin-bottom: 18px;
            font-size: 16px;
            line-height: 26px;
        }

        .timeline .slick-slide p:last-child {
            margin: 0px;
        }

        .timeline .slick-slide .checklist {
            padding: 0px;
            margin: 0px;
            list-style: none;
        }

        .timeline .slick-slide .checklist li {
            line-height: 26px;
        }

        .timeline .slick-slide .checklist li:before {
            content: "\f105";
            font-family: FontAwesome;
            color: #d32f2f;
            margin-right: 5px;
        }

        .timeline-box {
            background: #f7f7f7;
            border: 1px solid #eeeeee;
            border-radius: 5px;
            padding: 20px;
        }

        .timeline-box h6 {
            color: #222;
            font-weight: 600;
        }

        .timeline-box p {
            font-size: 16px;
            color: #666;
            line-height: 24px;
        }

        .timeline-box-services {
            background: #daf1f1;
            border: 1px solid #157347;
            border-radius: 5px;
            padding: 20px;
        }

        .timeline-box-services:hover  {
            background: #c2f8de;
            border: 1px solid #66bb6a;
            border-radius: 5px;
            padding: 20px;
            
            /*color: #157347; #66bb6a; c7f5df */
        }

        .timeline-box-services h6 {
            color: #222;
            font-weight: 600;
        }

        .timeline-box-services p {
            font-size: 16px;
            color: #666;
            line-height: 24px;
        }

        .timeline-nav .slick-slide {
            position: relative;
            height: 88px;
            cursor: pointer;
        }


        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }


        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
        }

        .container,
        .container-fluid,
        .container-lg,
        .container-md,
        .container-sm,
        .container-xl,
        .container-xxl {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            overflow: hidden;
        }

        html body {
            font-weight: 400;
            font-family: 'Segoe UI', sans-serif;
            color: #555555;
        }

        body {


            /* background-color: #ecf4f5; f8f9fa */
            font-family: 'Segoe UI', sans-serif !important;
            color: rgba(33, 37, 41, 0.75);


            --fbc-blue-60: #0060df;
            --fbc-blue-70: #003eaa;
            --fbc-gray-20: #ededf0;
            --fbc-light-gray: #F0F0F4;
            --fbc-white: #ffffff;
            --fbc-transition: all .15s cubic-bezier(.07, .95, 0, 1);
            --fbc-borders: 1px solid #ededf0;
            --fbc-primary-text: #15141A;
            --fbc-secondary-text: #5B5B66;
            --fbc-font-size: 13px;
        }

        /* =========== about us slick slide ends ======================== */



        gradient-custom-1 {
            /* fallback for old browsers */
            background: #ecf4f5;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, rgb(58, 87, 58), #ecf4f5, #ecf4f5, #ffffff);

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+  #ecf4f5; f8f9fa */
            background: linear-gradient(to right, rgb(58, 87, 58), #ecf4f5, #ecf4f5, #ffffff);
        }


        .gradient-custom-2 {
            /* fallback for old browsers */
            background: #ecf4f5;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to top, rgb(61, 104, 61), #ffffff, #ffffff, #ffffff);

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+   */
            background: linear-gradient(to top, rgb(61, 104, 61), #ffffff, #ffffff, #ffffff);
        }

        @media (min-width: 768px) {
            .gradient-form {
                height: 100vh !important;
            }
        }

        @media (min-width: 769px) {
            .gradient-custom-2 {
                border-top-right-radius: .3rem;
                border-bottom-right-radius: .3rem;
            }
        }

        .auth-wrapper {
            min-height: 100vh;
            background: linear-gradient(rgba(46, 125, 50, 0.1), rgba(46, 125, 50, 0.1)),
                url('https://api.placeholder.com/1920/1080') center/cover;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }


        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.2);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1B5E20;
            transform: translateY(-1px);
        }

        .text-success {
            color: #157347 !important;
            /* rgba(25,135,84,1) */
            opacity: 1;
        }


        /* =====================================  */


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


        .eco-box {
            position: relative;
            padding: 0 0 0 83px;
            margin: 0 0 33px;
            -webkit-transition: all ease-in-out 0.3s;
        }

        .eco-box:hover .econ-icon {
            color: #157347;
            /* #66bb6a; */
        }

        .eco-box h5 {
            color: #333;
            margin: 0 0 10px;
            font-weight: 600;
        }


        .eco-box:hover .econ-icon {
            color: #ffffff;
            background-color: #157347;

        }

        .eco-box .econ-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 68px;
            height: 68px;
            border: 2px solid #157347;
            color: #66bb6a;
            border-radius: 100%;
            text-align: center;
            line-height: 64px;
            font-size: 28px;
        }

        .eco-box:hover h5 {
            color: #157347;
            /* #66bb6a; */
        }

        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        .col-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
            position: relative;
            width: 100%;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .choose-ecova {
            background: url(' {{ asset('dashboard/images/patt.png') }} ');
        }

        .choose-ecova .volunteer-form {
            border-radius: 5px;
            box-shadow: 0 10px 40px rgba(102, 187, 106, .20);
            padding: 46px 30px 50px;
        }

        .choose-ecova .volunteer-form h3 {
            font-weight: 700;
            margin: 0px;
            color: #333;
        }

        .choose-ecova .volunteer-form .form-control {
            border: 2px solid #dddddd;
            height: 60px;
            line-height: 56px;
            border-radius: 5px;
            padding: 0 30px;
            color: #999999;
            font-size: 16px;
        }

        .choose-ecova .volunteer-form input.fsubmit {
            border-radius: 5px;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 500;
            text-transform: uppercase;
            background: #66bb6a;
        }

        .choose-ecova .section-title-2 h2 {
            margin: 0 0 30px;
        }

        .wf100 {
            width: 100%;
            float: left;
        }

        .p100 {
            padding: 100px 0;
        }

        .p80 {
            padding: 80px 0;
        }

        .p80top {
            padding: 80px 0 0;
        }

        .p80bottom {
            padding: 0 0 80px;
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <!--Header Start-->


        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-success">
            <div class="container">
                <a class="navbar-brand" href="/">
                        <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light" >
                    {{-- BSIADAMS --}}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto" style="font-weight: bold; ">
                        <li class="nav-item"> <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'about') }}">About</a> </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'services') }}">Services</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'contact') }}">Contact</a> </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"> <a class="nav-link" href="{{ route(name: 'login') }}">PORTAL</a> </li>


                    </ul>
                </div>
            </div>
        </nav>

        <!--Header End-->



        <!--Inner Header Start-->



        <!-- Hero Section -->
        <section class="hero-section" style="">
            <div class="container text-left" style="padding-bottom: 1px;">
                <p class="display-6 mb-4" style="color: #fff;">Our Services</p>
                <p class="lead mb-4" style="font-style: italic; color:#c0bcbc">Have a feel of the services we render...
                </p>
            </div>
        </section>




        {{-- <section class="inner-header">
            <div class="container">
                <h5 style="color: #fff; font-weight: bold">About Us</h5>
                <ul>
                    {{-- <li><a href="#"><span style="font-style: italic; color: darkgrey;">Home</span></a></li> --}
                    <li><a href="{{ route(name: 'about') }}"><p style="font-style: italic; color:#c0bcbc">Know more about what we do</p></a>
                    </li>
                </ul>
            </div>
        </section> --}}
        <!--Inner Header End-->





        <!--Our Success Story Start-->
        <div class="" style="padding: 80px 0 80px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 text-center">

                        <img src="{{ asset('dashboard/images/farmer_harvest.jpg') }}" alt="assorted farm practices"
                            style="height: 300px; margin-bottom:5px;">
                        {{-- <div class="story-title-bg"> <strong class="t1">ecova.</strong> <strong
                                    class="t2">20+</strong> <strong class="t3">Years of
                                    Services</strong>
                            </div> --}}
                    </div>
                    <div class="col-lg-8">
                        <div class="section-title-2">
                            <h5 class="text-success">We render services, collaborating with various Agencies of the
                                Benue State</h5>
                            <h2>Ministry of Agriculture & Natural Resources</h2>
                        </div>
                        <!--eco History Start-->
                        <div class="eco-history">
                            <div class="">
                                <div class="py-4">
                                    <div><span><strong> Agencies/Parastatals: </strong></span></div>
                                    <div><span><strong> </strong></span></div>
                                    <div><span><strong></strong></span></div>
                                    <div><span><strong> </strong></span></div>
                                    <div><span><strong> </strong></span></div>
                                    <div><span><strong> </strong></span></div>
                                </div>
                                <div class="timeline">
                                    <!--Timeline Text Start-->
                                    <div class="">
                                        <div class="row py-2">
                                            <div class=" col-4">
                                                <div class=" timeline-box-services">

                                                    <h6>RAAMP</h6>
                                                    <p>Rural Access Agricultural Marketing Project. </p>
                                                </div>
                                            </div>
                                            <div class=" col-4">
                                                <div class="timeline-box-services">
                                                    <h6>IFAD</h6>
                                                    <p> International Funds for Agricultural Development. </p>
                                                </div>
                                            </div>
                                            <div class=" col-4">
                                                <div class="timeline-box-services">

                                                    <h6>ACReSAL</h6>
                                                    <p> Agro-Climatic Resilience in Semi-Arid Landscapes. </p>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class=" col-4">
                                                <div class="timeline-box-services">

                                                    <h6>BNARDA</h6>
                                                    <p>Benue State Agricultural and Rural Development. </p>
                                                </div>
                                            </div>
                                            <div class=" col-4">
                                                <div class="timeline-box-services">
                                                    <h6>BENTHA</h6>
                                                    <p> Benue State Tractor Hiring Agency. </p>
                                                </div>
                                            </div>
                                            <div class=" col-4">
                                                <div class="timeline-box-services">
                                                    <h6>FADAMA CARES</h6>
                                                    <p> World Bank assisted agricultural project. </p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Timeline Text End-->

                                    <!--Timeline Text End-->
                                </div>
                            </div>
                        </div>
                        <!--Political History End-->
                    </div>
                </div>
            </div>
        </div>
        <!--Our Success Story End-->



        <!--Why you Need to Choose Ecova Start-->
        <div class="choose-ecova wf100 p80">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-title-2">
                            <h5 class="text-success">Presenting</h5>
                            <h2>Our Services</h2>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="eco-box">
                                    <span class="econ-icon"><i class="far fa-handshake"></i></span>
                                    <h5> Agro Farm Inputs </h5>
                                    <p> Farmers who have need for various farm inputs can access them from us. </p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="eco-box">
                                    <span class="econ-icon"><i class="far fa-money-bill-alt"></i></span>
                                    <h5> Dry Season Farming </h5>
                                    <p> We have all it needs for farmers to do an all-year-round farming. </p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="eco-box">
                                    <span class="econ-icon"><i class="fas fa-heart"></i></span>
                                    <h5> Meat Inspection </h5>
                                    <p>For healthy meat consumption, we carry out meat inspections.</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="eco-box">
                                    <span class="econ-icon"> <i class="fas fa-coffee"></i> </span>
                                    <h5> Intervetions </h5>
                                    <p>There are interventions and support for deserving farmers. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="volunteer-form">
                            <div class="section-title">
                                <h2>Other Services</h2>
                            </div>
                            <ul>
                                <li>We register venders who wish and have the requisite qualifications to supply
                                    agricultural inputs to the Government of Benue State.</li><br>
                                    <li>There are subsidies given to farmers when need arises such as: seeds, Fertilizers, Chemicals and the likes..</li>
                            </ul>
                            {{-- <ul>
                                <li>
                                    <input type="text" class="form-control" placeholder="Your Name"
                                        aria-label="Your Name">
                                </li>
                                <li>
                                    <input type="text" class="form-control" placeholder="Email Address"
                                        aria-label="Email Address">
                                </li>
                                <li>
                                    <input type="text" class="form-control" placeholder="Contact"
                                        aria-label="Contact">
                                </li>
                                <li>
                                    <input type="submit" class="fsubmit" value="Signup to Join us">
                                </li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Why you Need to Choose Ecova End-->



        <!-- Div to keep the layout from scattering -->

        <div class="row g-4"></div>
        <!-- Div to keep the layout from scattering ends -->


        </section>






    </div>









    <!--Footer Start-->



    <footer class="bg-dark text-white py-4">
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
                        <p class="mb-0">&copy; 2025 &mdash; Benue State Integrated Agricultural Data and Access
                            Management System.</p>
                    </div>
                    <div class="col-md-4">
                        <a href="http://bdic.ng" target="_blank" class="powered_by_bdic">Powered by BDIC <img
                                src="{{ asset('/dashboard/images/bdic_logo_small.png') }}" alt="BDIC"></a>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <!--Footer End-->





    <!--   JS Files Start  -->
    <script src="{{ asset('/dashboard/js/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('/dashboard/js/js/jquery-migrate-1.4.1.min.js') }}"></script>
    <script src="{{ asset('/dashboard/js/js/popper.min.js') }}"></script>
    <script src="{{ asset('/dashboard/js/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/dashboard/js/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('/dashboard/js/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('/dashboard/js/js/slick.min.js') }}"></script>
    <script src="{{ asset('/dashboard/js/js/custom.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
