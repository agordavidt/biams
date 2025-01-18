@extends('layouts.loginregister')
@section('content')

 <!-- Login Page -->
    <div class="auth-wrapper d-flex align-items-center py-5" >
        <div class="container">
            <div class="row justify-content-center">
                 <!-- Session Status -->
                <!-- <div class="alert alert-info mb-4" role="alert" >
                    {{ session('status') }}
                </div> -->
                <div class="col-lg-5 col-md-8" >
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

@endsection