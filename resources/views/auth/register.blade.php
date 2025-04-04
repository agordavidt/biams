@extends('layouts.loginregister')
@section('content')
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
                                        <p class="text-center mt-3" style="color: rgb(3, 73, 3)">Please, create account.</p>
                                        <div class="card-body">

                                            <form method="POST" action="{{ route('register') }}">
                                                @csrf
                                                <div class="form-floating  mb-3">
                                                    <input style="border: thin solid darkgreen;" class="form-control" type="text" name="name"
                                                        value="{{ old('name') }}" required autofocus autocomplete="name">
                                                    <label for="firstNameInput">{{ __('Full Name') }}</label>
                                                    @error('name')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input style="border: thin solid darkgreen;" type="email" class="form-control" name="email"
                                                        value="{{ old('email') }}" required autocomplete="username">
                                                    <label for="emailInput">{{ __('Email') }}</label>
                                                    @error('email')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-floating mb-3 position-relative">
                                                    <input style="border: thin solid darkgreen;" type="password" class="form-control" name="password" required
                                                        autocomplete="new-password" id="passwordInput1">
                                                    <label for="passwordInput">{{ __('Password') }}</label>
                                                    <span class="password-toggle" id="togglePassword1">
                                                        <i class="bi bi-eye" id="eyeIcon1"></i>
                                                    </span>
                                                    @error('password')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-floating mb-4 position-relative">
                                                    <input style="border: thin solid darkgreen;" type="password" class="form-control" name="password_confirmation"
                                                        required autocomplete="new-password" id="passwordInput2">
                                                    <label for="confirmPasswordInput">{{ __('Confirm Password') }}</label>
                                                    <span class="password-toggle" id="togglePassword2">
                                                        <i class="bi bi-eye" id="eyeIcon2"></i>
                                                    </span>
                                                    @error('password_confirmation')
                                                        <div class="text-danger mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                

                                                <input type="hidden" name="role" value="user">

                                                <!-- <div class="form-check mb-4">
                                                                <input class="form-check-input" type="checkbox" id="termsCheck">
                                                                <label class="form-check-label" for="termsCheck">
                                                                    I agree to the <a href="#" class="text-primary">Terms & Conditions</a>
                                                                </label>
                                                            </div> -->

                                                <button type="submit" class="btn btn-primary w-100">
                                                    {{ __('Register') }}</button>
                                            </form>



                                            <div class="auth-footer">
                                                Already have an account? <a href="{{ route('login') }}"
                                                    class="text-primary text-decoration-none">Login</a>.
                                            </div>
                                            {{-- <div class="text-center">
                                                <a href="{{ route('landing_page') }}"
                                                    class="text-primary text-decoration-none"><i
                                                        class="fas fa-home icon-large"></i></a>
                                            </div> --}}
                                        </div>
                                    </div>



                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom-1"
                                style="border: thin solid rgb(203, 214, 203)">
                                <div class="text-white px-3 py-0 p-md-5 mx-md-4">
                                    <img src="{{ asset('/dashboard/images/agric_asorted_350.png') }}"
                                        alt="agric_produce_home">
                                    <h4 class="mb-4">&nbsp;</h4>
                                    <p class="small mb-0" style="color: black">We have a wide variety of agricultural
                                        products, services and benefits just for you. Please register to access them.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            {{-- <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="auth-card">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <!-- <i class="fas fa-seedling fa-3x text-primary mb-3"></i> -->
                                <h4 class="auth-title">Create Account</h4>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-floating  mb-3">
                                    <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                        required autofocus autocomplete="name">
                                    <label for="firstNameInput">{{ __('Full Name') }}</label>
                                    @error('name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                        required autocomplete="username">
                                    <label for="emailInput">{{ __('Email') }}</label>
                                    @error('email')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3 position-relative">
                                    <input type="password" class="form-control" name="password" required
                                        autocomplete="new-password">
                                    <label for="passwordInput">{{ __('Password') }}</label>
                                    <span class="password-toggle">
                                        <i class="far fa-eye"></i>
                                    </span>
                                    @error('password')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-4 position-relative">
                                    <input type="password" class="form-control" name="password_confirmation" required
                                        autocomplete="new-password">
                                    <label for="confirmPasswordInput">{{ __('Confirm Password') }}</label>
                                    <span class="password-toggle">
                                        <i class="far fa-eye"></i>
                                    </span>
                                    @error('password_confirmation')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" name="role" value="user">

                                <!-- <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="termsCheck">
                                            <label class="form-check-label" for="termsCheck">
                                                I agree to the <a href="#" class="text-primary">Terms & Conditions</a>
                                            </label>
                                        </div> -->

                                <button type="submit" class="btn btn-primary w-100"> {{ __('Register') }}</button>
                            </form>



                            <div class="auth-footer">
                                Already have an account? <a href="{{ route('login') }}"
                                    class="text-primary text-decoration-none">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}


        </div>
    </div>
@endsection
