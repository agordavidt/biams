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

    <style>
        .powered_by_bdic {
            text-decoration: none;
            color: rgb(241, 80, 112);
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.7)), url('<?php echo e(asset('dashboard/images/portalbg.jpg')); ?>');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }



        /*********************************
Inner Header Start
*********************************/

        .inner-header {
            background: url('<?php echo e(asset('dashboard/images/portalbg.jpg')); ?>') no-repeat;
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

        /* ===================================== */


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
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-leaf me-2 " style="padding-right:120px; font-size: 2rem;"></i> 
                
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto" style="font-weight: bold; ">
                    <li class="nav-item"> <a class="nav-link" href="<?php echo e(route(name: 'landing_page')); ?>">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="<?php echo e(route(name: 'about')); ?>">About</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="<?php echo e(route(name: 'services')); ?>">Services</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="<?php echo e(route(name: 'contact')); ?>">Contact</a> </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"> <a class="nav-link" href="<?php echo e(route(name: 'portal')); ?>">PORTAL</a> </li>
                    

                </ul>
            </div>
        </div>
    </nav>


    <!--Inner Header Start-->
    <section class="inner-header">
        <div class="container">
            <h1>About Us</h1>
            <ul>
                
                <li style="font-style: italic;"><a href="<?php echo e(route(name: 'about')); ?>">Know more about what we do</a></li>
            </ul>
        </div>
    </section>
    <!--Inner Header End-->



   










    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
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
                                src="<?php echo e(asset('/dashboard/images/bdic_logo_small.png')); ?>" alt="BDIC"></a>
                    </div>

                </div>

            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php /**PATH C:\wamp\www\BDIC\biams\resources\views/about.blade.php ENDPATH**/ ?>