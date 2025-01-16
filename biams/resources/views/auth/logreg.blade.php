@extends('layouts.loginregister')
@section('content')



@endsection



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriTech - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    <!-- Login Page -->
    <div class="auth-wrapper d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                 <!-- Session Status -->
                <div class="alert alert-info mb-4" role="alert">
                    {{ session('status') }}
                </div>
                <div class="col-lg-5 col-md-8">
                    <div class="auth-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="fas fa-seedling fa-3x text-primary mb-3"></i>
                                <h4 class="auth-title">Welcome to BIAAMS</h4>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                            @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="emailInput" placeholder="name@example.com" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                                    <label for="emailInput">{{ __('Email address') }}</label>
                                    @error('email')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-4 position-relative">
                                    <input type="password" class="form-control" id="passwordInput" placeholder="Password" name="password" required autocomplete="current-password">
                                    <label for="passwordInput">{{ __('Password') }}</label>
                                    <span class="password-toggle">
                                        <i class="far fa-eye"></i>
                                    </span>
                                    @error('password')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                        <label class="form-check-label" for="rememberMe">
                                           {{ __('Remember me') }}
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a class="text-primary text-decoration-none" href="{{ route('password.request') }}">
                                            {{ __('Forgot your password?') }}
                                        </a>
                                    @endif                                   
                                </div>

                                <button type="submit" class="btn btn-primary w-100" >{{ __('Log in') }}</button>
                            </form>

                           

                            <div class="auth-footer">
                                Don't have an account? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Register</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Page -->
    <!DOCTYPE html>
    <html lang="en">
    <!-- Same head section as login page -->
    <body>
        <div class="auth-wrapper d-flex align-items-center py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-8">
                        <div class="auth-card">
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <i class="fas fa-seedling fa-3x text-primary mb-3"></i>
                                    <h4 class="auth-title">Create Account</h4>
                                </div>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-floating  mb-3">
                                        <input class="form-control" id="firstNameInput" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                                        <label for="firstNameInput">{{ __('Name') }}</label>
                                         @error('name')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="emailInput"  name="email" value="{{ old('email') }}" required autocomplete="username">
                                        <label for="emailInput">{{ __('Email') }}</label>
                                         @error('email')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-3 position-relative">
                                        <input type="password" class="form-control" id="passwordInput" name="password" required autocomplete="new-password">
                                        <label for="passwordInput">{{ __('Password') }}</label>
                                        <span class="password-toggle">
                                            <i class="far fa-eye"></i>
                                        </span>
                                         @error('password')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-4 position-relative">
                                        <input type="password" class="form-control" id="confirmPasswordInput" name="password_confirmation" required autocomplete="new-password">
                                        <label for="confirmPasswordInput">{{ __('Confirm Password') }}</label>
                                        <span class="password-toggle">
                                            <i class="far fa-eye"></i>
                                        </span>
                                        @error('password_confirmation')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                     <input type="hidden" name="role" value="user">

                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" id="termsCheck">
                                        <label class="form-check-label" for="termsCheck">
                                            I agree to the <a href="#" class="text-primary">Terms & Conditions</a>
                                        </label>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">  {{ __('Register') }}</button>
                                </form>

                                

                                <div class="auth-footer">
                                    Already have an account? <a href="{{ route('login') }}" class="text-primary text-decoration-none">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Password visibility toggle
            document.querySelectorAll('.password-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        </script>
    </body>
    </html>
</body>
</html>