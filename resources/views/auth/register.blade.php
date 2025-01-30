@extends('layouts.loginregister')
@section('content')

 <div class="auth-wrapper d-flex align-items-center py-5">
            <div class="container">
                <div class="row justify-content-center">
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
                                        <input class="form-control"  type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                                        <label for="firstNameInput">{{ __('Full Name') }}</label>
                                         @error('name')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control"   name="email" value="{{ old('email') }}" required autocomplete="username">
                                        <label for="emailInput">{{ __('Email') }}</label>
                                         @error('email')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-3 position-relative">
                                        <input type="password" class="form-control" name="password" required autocomplete="new-password">
                                        <label for="passwordInput">{{ __('Password') }}</label>
                                        <span class="password-toggle">
                                            <i class="far fa-eye"></i>
                                        </span>
                                         @error('password')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-floating mb-4 position-relative">
                                        <input type="password" class="form-control"  name="password_confirmation" required autocomplete="new-password">
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

@endsection