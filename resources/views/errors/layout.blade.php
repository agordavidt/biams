<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - @yield('title') | Benue State Smart Agricultural System</title>
    <meta name="description" content="Benue State Smart Agricultural System and Data Management">
    <meta name="author" content="BDIC Team">
    
    <link rel="shortcut icon" href="{{ asset('dashboard/images/favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashboard/css/app.min.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Error @yield('code')</h5>
                                        <p>@yield('title')</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('dashboard/images/profile-img.png') }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="p-2">
                                <div class="text-center">
                                    <div class="avatar-md mx-auto">
                                        <div class="avatar-title rounded-circle bg-light">
                                            <i class="ri-error-warning-line h1 mb-0 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <h4>@yield('title')</h4>
                                        <p class="text-muted">@yield('message')</p>
                                        
                                        @hasSection('additional_info')
                                            <div class="mt-3">
                                                @yield('additional_info')
                                            </div>
                                        @endif
                                        
                                        <div class="mt-4">
                                            @hasSection('action_button')
                                                @yield('action_button')
                                            @else
                                                @auth
                                                    {{-- Redirect authenticated users to their role-appropriate dashboard --}}
                                                    @if(auth()->user()->hasRole('Super Admin'))
                                                        <a href="{{ route('super_admin.dashboard') }}" class="btn btn-primary w-md">
                                                            <i class="ri-dashboard-line me-1"></i> Return to Dashboard
                                                        </a>
                                                    @elseif(auth()->user()->hasRole('Governor'))
                                                        <a href="{{ route('governor.dashboard') }}" class="btn btn-primary w-md">
                                                            <i class="ri-dashboard-line me-1"></i> Return to Dashboard
                                                        </a>
                                                    @elseif(auth()->user()->hasRole('State Admin'))
                                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary w-md">
                                                            <i class="ri-dashboard-line me-1"></i> Return to Dashboard
                                                        </a>
                                                    @elseif(auth()->user()->hasRole('LGA Admin'))
                                                        <a href="{{ route('lga_admin.dashboard') }}" class="btn btn-primary w-md">
                                                            <i class="ri-dashboard-line me-1"></i> Return to Dashboard
                                                        </a>
                                                    @elseif(auth()->user()->hasRole('Enrollment Agent'))
                                                        <a href="{{ route('enrollment.dashboard') }}" class="btn btn-primary w-md">
                                                            <i class="ri-dashboard-line me-1"></i> Return to Dashboard
                                                        </a>
                                                    @else
                                                        <a href="{{ route('home') }}" class="btn btn-primary w-md">
                                                            <i class="ri-dashboard-line me-1"></i> Return to Dashboard
                                                        </a>
                                                    @endif
                                                @else
                                                    {{-- Redirect guests to homepage --}}
                                                    <a href="{{ url('/') }}" class="btn btn-primary w-md">
                                                        <i class="ri-home-4-line me-1"></i> Go to Homepage
                                                    </a>
                                                @endauth
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 text-center">
                        <p>© <script>document.write(new Date().getFullYear())</script> Benue State Smart Agricultural System and Data Management. <br> Powered by BDIC</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>