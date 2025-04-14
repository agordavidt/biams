<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benue State Integrated Agricultural Assets Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #81C784;
            --accent-color: #FDD835;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
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

        .auth-card .card-body {
            padding: 2.5rem;
        }

        .auth-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
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

        .auth-separator {
            display: flex;
            align-items: center;
            text-align: center;
            color: #6c757d;
            margin: 1.5rem 0;
        }

        .auth-separator::before,
        .auth-separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }

        .auth-separator span {
            padding: 0 1rem;
        }

        .social-login {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            color: #6c757d;
        }

        .brand-logo {
            width: 60px;
            margin-bottom: 1rem;
        }

        .form-floating > label {
            padding: 0.75rem 1rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>
<body>
    

    @yield('content')



 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Password visibility toggle
            // document.querySelectorAll('.password-toggle').forEach(toggle => {
            //     toggle.addEventListener('click', function() {
            //         const input = this.previousElementSibling;
            //         const icon = this.querySelector('i');
                    
            //         if (input.type === 'password') {
            //             input.type = 'text';
            //             icon.classList.remove('fa-eye');
            //             icon.classList.add('fa-eye-slash');
            //         } else {
            //             input.type = 'password';
            //             icon.classList.remove('fa-eye-slash');
            //             icon.classList.add('fa-eye');
            //         }
            //     });
            // });


// JavaScript to toggle password visibility

const togglePassword1 = document.getElementById("togglePassword1");
const togglePassword2 = document.getElementById("togglePassword2");

const passwordInput1 = document.getElementById("passwordInput1");
const passwordInput2 = document.getElementById("passwordInput2");

const eyeIcon1 = document.getElementById("eyeIcon1");
const eyeIcon2 = document.getElementById("eyeIcon2");





togglePassword1.addEventListener("click", function () {
  // Toggle the input type
  const type1 = passwordInput1.type === "password" ? "text" : "password";
  passwordInput1.type = type1;

  // Toggle the icon
  if (type1 === "password") {
    eyeIcon1.classList.remove("bi-eye-slash");
    eyeIcon1.classList.add("bi-eye");
  } else {
    eyeIcon1.classList.remove("bi-eye");
    eyeIcon1.classList.add("bi-eye-slash");
  }
});


togglePassword2.addEventListener("click", function () {
    // Toggle the input type
    const type2 = passwordInput2.type === "password" ? "text" : "password";
    passwordInput2.type = type2;
  
    // Toggle the icon
    if (type2 === "password") {
      eyeIcon2.classList.remove("bi-eye-slash");
      eyeIcon2.classList.add("bi-eye");
    } else {
      eyeIcon2.classList.remove("bi-eye");
      eyeIcon2.classList.add("bi-eye-slash");
    }
  })

        </script>
    </body>
    </html>
</body>
</html>