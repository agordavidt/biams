<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Benue State Smart Agricultural System and Data Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="/dashboard/images/favicon.ico">

    <style>
        :root {
            --primary-green: #2e7d32;
            --secondary-green: #4caf50;
            --light-bg: #f5f7f5;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem; /* Base: 16px */
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
            font-size: 1.1rem; /* ~17.6px */
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
            font-size: 1rem; /* ~16px */
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.8)), url('/dashboard/images/agro_bg1.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: left;
        }

        .hero-section h1 {
            font-size: 2.5rem; /* ~40px */
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.25rem; /* ~20px */
            font-weight: 300;
            font-style: italic;
            color: #c0bcbc;
        }

        /* Contact Section */
        .contact-section {
            padding: 5rem 0;
        }

        .contact-section h2 {
            font-size: 2rem; /* ~32px */
            font-weight: 700;
            color: var(--primary-green);
            margin-bottom: 2rem;
        }

        .contact-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 2rem;
        }

        .contact-card iframe {
            width: 100%;
            height: 400px;
            border: 0;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .contact-form {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 2rem;
        }

        .contact-form h2 {
            font-size: 1.75rem; /* ~28px */
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 1.5rem;
        }

        .contact-form .form-control {
            font-size: 1rem; /* ~16px */
            font-weight: 400;
            margin-bottom: 1rem;
            border-radius: 10px;
        }

        .contact-form textarea {
            height: 120px;
        }

        .contact-form .btn-primary {
            background: var(--secondary-green);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            font-size: 1.1rem; /* ~17.6px */
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .contact-form .btn-primary:hover {
            background: var(--primary-green);
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            background: #1a3c34;
            color: #e0e0e0;
            padding: 3rem 0;
            margin-top: 3rem;
        }

        .footer h5 {
            font-size: 1.25rem; /* ~20px */
            font-weight: 600;
        }

        .footer p, .footer a {
            font-size: 1rem; /* ~16px */
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
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/dashboard/images/bsiadams_logo_new.png" alt="logo-light">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"> <a class="nav-link" href="/">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="/about">About</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="/services">Services</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="/marketplace">Market</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="/contact">Contact</a> </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"> <a class="nav-link" href="/login">PORTAL</a> </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Contact Us</h1>
            <p>Let's stay connected...</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="contact-card">
                        <h2>We'd Love to Hear From You</h2>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.5164163509003!2d8.52852477381703!3d7.734916207904055!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x105081adab1f32bb%3A0xfbd3eeca2b605aad!2sMinistry%20of%20Agriculture%20and%20Natural%20Resources%2CMakurdi%2CBenue%20State!5e0!3m2!1sen!2sng!4v1738934084814!5m2!1sen!2sng"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="contact-form">
                        <h2>Get in Touch</h2>
                        <form action="#">
                            <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                            <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                            <input type="tel" class="form-control" name="phone" placeholder="Phone" maxlength="11" required>
                            <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                            <textarea class="form-control" name="message" placeholder="Message" rows="4" required></textarea>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="/dashboard/images/bsiadams_logo_new.png" alt="Benue Agro Market Logo" class="footer-logo">
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
                <a href="http://bdic.ng" target="_blank" class="powered_by_bdic">Powered by BDIC 
                    <img src="/dashboard/images/bdic_logo_small.png" alt="BDIC">
                </a></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>