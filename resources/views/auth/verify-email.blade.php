<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
         body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #c0cfd1; /* Optional: Light gray background 536068 EAEAEA f8f9fa*/
        }
        .flex-container {
    display: flex;
    flex-direction: column; /* Stack items vertically */
    height: 100vh; /* Adjust height as needed */
  
}


    </style>
</head>
<body>



<div class="flex col-md-7" style="background-color: white;">
   
<div class="flex-container">
    <div class="" style=" height: 80px; text-align: justify; color: #2E7D32;"><h4 class="text-center py-3">Benue State Agricultural Data and Access Management
        System</h4></div>
   
        <div class="" style=" height: 100px; text-align: justify; font-size: 45px; "><p class="text-center py-3">Verify Your Email</p></div>
    <div class="text-center pb-2"> <img src="{{ asset('/dashboard/images/verify.png') }}" style="width: 185px;"
        alt="email verify"> </div>
   
   
        <div class="row">
         
            <div class="col-md-7 mx-auto" >
                <div class="alert alert-info" role="alert">
                    {{ __('Thank you for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success" role="alert">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <button type="submit" class="btn btn-primary">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="btn btn-danger">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

   
    
</div>
</div>







    
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>