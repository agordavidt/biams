<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Approved</title>
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
        .success-box {
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
        .features {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .feature-item {
            margin: 10px 0;
            padding-left: 25px;
            position: relative;
        }
        .feature-item:before {
            content: "✓";
            color: #006837;
            position: absolute;
            left: 0;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light" >
        </div>
        
        <div class="content">
            <h2>Congratulations, {{ $name }}!</h2>
            
            <div class="success-box">
                <h3>Your Profile is Approved! 🎉</h3>
                <p>We're excited to welcome you to the BSIADAMS platform. Your profile has been reviewed and approved by our admin team.</p>
            </div>
            
            <p>You now have full access to all features of the BSIADAMS platform:</p>
            
            <!-- <div class="features">
                <div class="feature-item">Register your agricultural practices</div>
                <div class="feature-item">Access agricultural resources and guidelines</div>
                <div class="feature-item">Connect with other farmers and processors</div>
                <div class="feature-item">Receive important updates and notifications</div>
                <div class="feature-item">Track your applications and submissions</div>
            </div> -->
            
            <div style="text-align: center;">
                <a href="{{ $dashboardUrl }}" class="button">Go to Dashboard</a>
            </div>
            
            <p>Need help getting started? Our support team is here to assist you every step of the way.</p>
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