{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.loginregister')

@section('title', 'Login - Benue State Smart Agricultural System and Data Management')

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
                                    <li class="trail-item trail-end">
                                        <span>Login</span>
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

<!-- login-area start -->
<section class="login-area pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="login-form">
                    <div class="text-center mb-4">
                        <h3>Login to Your Account</h3>
                        <p class="mt-3" style="color: rgb(3, 73, 3)">Welcome, please login to access the system.</p>
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

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" class="form-control" name="email" 
                                   value="{{ old('email') }}" placeholder="Enter your email address" 
                                   required autofocus autocomplete="email">
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="password">Password</label>
                            <input type="password" id="password" class="form-control" name="password" 
                                   placeholder="Enter your password" required autocomplete="current-password">
                            <span class="password-toggle" id="togglePassword" style="
                                position: absolute;
                                right: 15px;
                                top: 40px;
                                cursor: pointer;
                                color: #666;
                            ">
                                <i class="far fa-eye" id="eyeIcon"></i>
                            </span>
                            @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="remember-forgot">
                            <div class="remember-me">
                                <!-- <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">Remember me</label> -->
                            </div>
                            {{-- ✅ UNCOMMENTED: Forgot Password Link --}}
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-password">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="login-btn">Login</button>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- login-area end -->

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
    padding: 12px 15px;
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
.remember-forgot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.remember-me {
    display: flex;
    align-items: center;
}
.remember-me input {
    margin-right: 8px;
}
.remember-me label {
    margin-bottom: 0;
    font-weight: normal;
}
.forgot-password {
    color: #2a7d2e;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}
.forgot-password:hover {
    text-decoration: underline;
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
.register-link {
    text-align: center;
    margin-top: 20px;
}
.register-link a {
    color: #2a7d2e;
    text-decoration: none;
    font-weight: 500;
}
.register-link a:hover {
    text-decoration: underline;
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
.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}
.password-toggle {
    position: absolute;
    right: 15px;
    top: 40px;
    cursor: pointer;
    color: #666;
    z-index: 10;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle the eye icon
            if (type === 'password') {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    }
});
</script>
@endsection