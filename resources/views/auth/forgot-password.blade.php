@extends('layouts.loginregister')

@section('title', 'Forgot Password - Benue State Smart Agricultural System and Data Management')

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
                                        <span>Forgot Password</span>
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

<!-- forgot-password-area start -->
<section class="login-area pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="login-form">
                    <div class="text-center mb-4">
                        <h3>Reset Your Password</h3>
                        <p class="mt-3" style="color: rgb(3, 73, 3)">
                            Enter your email address and we'll send you a link to reset your password.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" class="form-control" name="email" 
                                   value="{{ old('email') }}" placeholder="Enter your registered email address" 
                                   required autofocus autocomplete="email">
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="login-btn">
                           Send Reset Link
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="back-to-login">
                                Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- forgot-password-area end -->

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
.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}
.alert i {
    margin-right: 8px;
}
</style>
@endsection