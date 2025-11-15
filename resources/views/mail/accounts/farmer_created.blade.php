<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Account Approved</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #38761D;
            padding: 30px 20px;
            text-align: center;
        }
        .header img {
            max-height: 60px;
            width: auto;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
        }
        .content h2 {
            color: #38761D;
            margin-top: 0;
            font-size: 24px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border-left: 4px solid #38761D;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .credentials-box p {
            margin: 10px 0;
            font-size: 14px;
        }
        .credentials-box strong {
            color: #38761D;
            display: inline-block;
            width: 100px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #38761D;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: 600;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('dashboard/images/bsiadams_logo_new.png') }}" alt="BSSADAM Logo">
        </div>
        
        <div class="content">
            <h2>🎉 Your Farmer Profile Has Been Approved!</h2>
            
            <p>Dear {{ $user->name }},</p>
            
            <p>Congratulations! Your farmer enrollment has been reviewed and approved by the LGA Administrator. You can now access the BSIADAMS platform.</p>
            
            <div class="credentials-box">
                <p style="margin-top: 0; font-weight: 600; color: #38761D;">Your Login Credentials:</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
                <p><strong>Role:</strong> Farmer</p>
            </div>
            
            <div class="warning">
                <strong>⚠️ Important Security Notice:</strong><br>
                You will be required to change your password on your first login. Please keep your new password secure and do not share it with anyone.
            </div>
            
            <p style="text-align: center;">
                <a href="{{ $loginUrl }}" class="btn">Login to Your Account</a>
            </p>
            
            <p>As a registered farmer, you can now:</p>
            <ul>
                <li>Access your farmer dashboard</li>
                <li>Manage your farm information</li>
                <li>Apply for agricultural resources</li>
                <li>Receive updates and notifications</li>
            </ul>
            
            <p>If you need any assistance, please contact your local enrollment officer or LGA administrator.</p>
            
            <p>Best regards,<br>
            <strong>BSSADAM Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Benue State Smart Agricultural System and Data Management<br>
            Powered by BDIC</p>
        </div>
    </div>
</body>
</html>