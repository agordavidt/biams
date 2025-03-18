<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #006837;
            padding: 20px;
            text-align: center;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .content {
            padding: 30px;
            color: #333333;
        }
        .verification-box {
            background-color: #e7f5ed;
            border-left: 4px solid #006837;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #006837;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .button:hover {
            background-color: #005129;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666666;
            border-top: 1px solid #eeeeee;
        }
        .link-text {
            word-break: break-all;
            color: #006837;
            font-size: 0.9em;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            margin: 0 10px;
            color: #006837;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="BSIADAMS Logo" class="logo">
        </div>
        
        <div class="content">
            <h2>Email Verification</h2>
            
            <div class="verification-box">
                <h3>Verify Your Email Address 📧</h3>
                <p>Hello {{ $user->name }}, welcome to BSIADAMS! Please verify your email to complete your registration.</p>
            </div>
            
            <p>Click the button below to verify your email address:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
            </div>
            
            <p>If the button above doesn't work, you can copy and paste the following link into your browser:</p>
            <p class="link-text">{{ $verificationUrl }}</p>
            
            <p>This link will expire in 60 minutes. If you did not create an account, please ignore this email.</p>
        </div>
        
        <div class="footer">
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">LinkedIn</a>
            </div>
            <p>© {{ date('Y') }} BSIADAMS. All rights reserved.</p>
            <p>Block 8 Suite 11-13 Makurdi/Gboko Road,<br>
               Behind Total Filing Station, SDP,<br>
               Makurdi, Benue State</p>
            <p>Need help? Contact us at <a href="mailto:support@bsiadams.com" style="color: #006837;">support@bsiadams.com</a></p>
        </div>
    </div>
</body>
</html>