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
        .note {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 0.9em;
            color: #666;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666666;
            border-top: 1px solid #eeeeee;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            margin: 0 10px;
            color: #006837;
            text-decoration: none;
        }
        .help-text {
            font-size: 0.9em;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="BSIADAMS Logo" class="logo">
        </div>
        
        <div class="content">
            <h2>Welcome to BSIADAMS!</h2>
            
            <p>Dear {{ $notifiable->name }},</p>
            
            <div class="verification-box">
                <h3>One Last Step!</h3>
                <p>Thank you for joining BSIADAMS - your gateway to modern agricultural practices in Nigeria. To ensure the security of your account and get started with your agricultural journey, please verify your email address.</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Verify Email Address</a>
            </div>
            
            <div class="note">
                <strong>Note:</strong> This verification link will expire in 60 minutes for security reasons.
            </div>
            
            <p>By verifying your email, you'll gain access to:</p>
            <ul>
                <li>Register your agricultural practices</li>
                <li>Access to agricultural resources and guidelines</li>
                <li>Connect with other farmers and processors</li>
                <li>Receive important updates and notifications</li>
            </ul>
            
            <div class="help-text">
                <p>If you didn't create an account with BSIADAMS, please ignore this email or contact our support team if you have concerns.</p>
                <p>Having trouble with the verification button? Copy and paste this link into your browser:</p>
                <p style="word-break: break-all; font-size: 0.8em; color: #006837;">{{ $url }}</p>
            </div>
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