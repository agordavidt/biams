{{-- resources/views/auth/reset-password.blade.php --}}
@extends('layouts.loginregister')

@section('title', 'Reset Password - Benue State Smart Agricultural System and Data Management')

@section('content')
<!-- page__title -start -->
<div class="page__title align-items-center theme-bg-primary-h1 pt-15 pb-15">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page__title-content text-center">
                    <div class="page_title__bread-crumb">
                        <nav aria-label="breadcrumb">
                            <nav aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs">
                                <ul>
                                    <li>
                                        <a href="{{ url('/') }}"><span>Home</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('login') }}"><span>Login</span></a>
                                    </li>
                                    <li class="trail-item trail-end">
                                        <span>Reset Password</span>
                                    </li>
                                </ul>
                            </nav> 
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- page__title -end -->

<!-- reset-password-area start -->
<section class="login-area pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="login-form">
                    <div class="text-center mb-4">
                        <h3>Set New Password</h3>
                        <p class="mt-3" style="color: rgb(3, 73, 3)">
                            Please enter your new password below.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        
                        <!-- Hidden token field -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" class="form-control" name="email" 
                                   value="{{ old('email', $request->email) }}" 
                                   placeholder="Enter your email address" 
                                   required autofocus autocomplete="email">
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="password">New Password</label>
                            <input type="password" id="password" class="form-control" name="password" 
                                   placeholder="Enter new password (minimum 8 characters)" 
                                   required autocomplete="new-password">
                            <span class="password-toggle" id="togglePassword">
                                <i class="far fa-eye" id="eyeIcon"></i>
                            </span>
                            @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Password must be at least 8 characters long.
                            </small>
                        </div>

                        <div class="form-group position-relative">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" id="password_confirmation" class="form-control" 
                                   name="password_confirmation" 
                                   placeholder="Re-enter your new password" 
                                   required autocomplete="new-password">
                            <span class="password-toggle" id="togglePasswordConfirm">
                                <i class="far fa-eye" id="eyeIconConfirm"></i>
                            </span>
                        </div>

                        <button type="submit" class="login-btn">
                      Reset Password
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="back-to-login">
                                <i class="fas fa-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- reset-password-area end -->

<style>
.login-area {
    padding: 100px 0;
}
.login-form {
    background: #f8f8f8;
    padding: 40px;
    border-radius: 5px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.login-form h3 {
    margin-bottom: 30px;
    color: #2a7d2e;
}
.form-group {
    margin-bottom: 20px;
    position: relative;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}
.form-control {
    width: 100%;
    padding: 12px 45px 12px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.3s;
}
.form-control:focus {
    border-color: #2a7d2e;
    box-shadow: 0 0 0 2px rgba(42, 125, 46, 0.2);
    outline: none;
}
.password-toggle {
    position: absolute;
    right: 15px;
    top: 40px;
    cursor: pointer;
    color: #666;
    z-index: 10;
}
.login-btn {
    width: 100%;
    padding: 12px;
    background: #2a7d2e;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s;
}
.login-btn:hover {
    background: #1f5e22;
}
.login-btn i {
    margin-right: 8px;
}
.back-to-login {
    color: #2a7d2e;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
}
.back-to-login:hover {
    text-decoration: underline;
}
.back-to-login i {
    margin-right: 5px;
}
.alert {
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}
.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
.form-text {
    display: block;
    margin-top: 5px;
    font-size: 12px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility for main password field
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'password') {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    }

    // Toggle password visibility for confirmation field
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');

    if (togglePasswordConfirm && passwordConfirmInput && eyeIconConfirm) {
        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            
            if (type === 'password') {
                eyeIconConfirm.classList.remove('fa-eye-slash');
                eyeIconConfirm.classList.add('fa-eye');
            } else {
                eyeIconConfirm.classList.remove('fa-eye');
                eyeIconConfirm.classList.add('fa-eye-slash');
            }
        });
    }
});
</script>
@endsection