@extends('layouts.loginregister')
@section('content')
    <!-- Login Page -->
    <div class="auth-wrapper d-flex align-items-center py-5">
        <div class="container">



            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">

                                    <div class="text-center">
                                        {{-- <img src="{{ asset('/dashboard/images/maize_icon.png') }}" style="width: 185px;"
                                    alt="logo"> --}}
                                        <h4 class="mt-1 mb-5 pb-1">Benue State Agricultural Data and Access Management
                                            System</h4>

                                    </div>

                                    <div class="auth-card">
                                        <p class="text-center mt-3" style="color: rgb(3, 73, 3)">Please, login.</p>
                                        <div class="card-body">

                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="form-floating mb-3">
                                                    <input style="border: thin solid darkgreen;" type="email"
                                                        class="form-control" id="emailInput" placeholder="name@example.com"
                                                        name="email" value="{{ old('email') }}" required autofocus
                                                        autocomplete="email">
                                                    <label for="emailInput">{{ __('Email address') }}</label>
                                                    @error('email')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-floating mb-4 position-relative">
                                                    <input style="border: thin solid darkgreen;" type="password"
                                                        class="form-control" id="passwordInput" placeholder="Password"
                                                        name="password" required autocomplete="current-password">
                                                    <label for="passwordInput">{{ __('Password') }}</label>
                                                    <span class="password-toggle">
                                                        <i class="far fa-eye"></i>
                                                    </span>
                                                    @error('password')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>


                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="rememberMe"
                                                        style="border: thin solid darkgreen;" name="remember">
                                                    <label class="form-check-label"
                                                        for="rememberMe">{{ __('Remember me') }}</label>
                                                </div>
                                                <div class="form-group mt-2 mb-4">
                                                    @if (Route::has('password.request'))
                                                    <a class="text-primary text-decoration-none"
                                                        href="{{ route('password.request') }}">
                                                        {{ __('Forgot your password?') }}
                                                    </a>
                                                @endif
                                                </div>

                                               

                                                <button type="submit"
                                                    class="btn btn-primary w-100">{{ __('Log in') }}</button>
                                            </form>



                                            <div class="auth-footer">
                                                Don't have an account? <a href="{{ route('register') }}"
                                                    class="text-primary text-decoration-none">Register</a>.
                                            </div>
                                            {{-- <div class="text-center">
                                                <a href="{{ route('landing_page') }}"
                                                    class="text-primary text-decoration-none"><i class="fas fa-home icon-large"></i></a>
                                            </div> --}}
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-2"
                                style="border: thin solid rgb(203, 214, 203)">
                                <div class="text-white px-3 py-0 p-md-5 mx-md-4">
                                    <img src="{{ asset('/dashboard/images/produce_home_350.jpg') }}"
                                        alt="agric_produce_home">
                                    <h4 class="mb-4">&nbsp;</h4>
                                    <p class="small mb-0" style="color: black">Now that you have your account registered
                                        with us, you can log in to gain access to our wide range of services.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="row col-md-12">
                <div class="row justify-content-center">
                    <!-- Session Status -->
                    <!-- <div class="alert alert-info mb-4" role="alert" >
                            {{ session('status') }}
                        </div> -->
                    <div class="col-lg-5 col-md-8">
                        <div class="auth-card">
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <i class="fas fa-seedling fa-3x text-primary mb-3"></i>
                                    <h4 class="auth-title">Welcome</h4>
                                    <p>Enter your details to login</p>
                                </div>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="emailInput"
                                            placeholder="name@example.com" name="email" value="{{ old('email') }}"
                                            required autofocus autocomplete="email">
                                        <label for="emailInput">{{ __('Email address') }}</label>
                                        @error('email')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-4 position-relative">
                                        <input type="password" class="form-control" id="passwordInput"
                                            placeholder="Password" name="password" required autocomplete="current-password">
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
                                            <a class="text-primary text-decoration-none"
                                                href="{{ route('password.request') }}">
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">{{ __('Log in') }}</button>
                                </form>



                                <div class="auth-footer">
                                    Don't have an account? <a href="{{ route('register') }}"
                                        class="text-primary text-decoration-none">Register</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection
