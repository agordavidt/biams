
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - Benue State Agricultural Network</title>
    
    <!-- CSS -->
    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #000;  /*  #f8f9fa;  */
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        .welcome-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)),
                        url('{{ asset("dashboard/images/farming-bg.jpg") }}') center/cover;
            padding: 2rem;
        }

        .welcome-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 800px;
            width: 100%;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 1.5rem;
        }

        .progress {
            height: 8px;
            margin: 2rem 0;
            border-radius: 4px;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }

        .btn-start {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            border-radius: 50px;
            background: #0d6efd;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .step-title {
            color: #0d6efd;
            margin-bottom: 1.5rem;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .benefits-list li {
            padding: 0.5rem 0;
            padding-left: 2rem;
            position: relative;
        }

        .benefits-list li:before {
            content: "✓";
            color: #0d6efd;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
    </style>
</head>
<body>

<div class="welcome-section">
    <div class="welcome-card">
        
        <div id="welcome-content" class="text-center">
            <img src="{{ asset('dashboard/images/favicon.jpg') }}" alt="Logo" class="logo" width="140"> 
            <h3 class="mb-4">Welcome to Benue State Smart Agricultural System and Data Management</h3>
            <p class="lead mb-4">{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
           
        
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
        
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
        
                <!-- Email Address -->
                <div class="row py-3">
                    <div class="col-12 col-md-6  mb-3">
                        {{-- <label for="email" class="form-label">Email address</label> --}}
                        <input type="email" id="email" class="form-control" id="email" name="email" :value="old('email')" required autofocus placeholder="Enter your email to get password reset link" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />  
                    </div>
            
                      <!-- Resend Password Button -->
                      <div class="col-12 col-md-6  gap-2">
                        <button type="submit" class="btn btn-primary">Email Reset Link</button>
                      </div>
                    
                    
                    
                </div>
        
                
            </form>
           
        </div>

        
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>

</script>

</body>
</html>




