<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2a7d2e 0%, #1f5e22 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header img {
            max-width: 80px;
            margin-bottom: 15px;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #2a7d2e;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .content p {
            margin-bottom: 15px;
            color: #555;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #2a7d2e;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .reset-button:hover {
            background-color: #1f5e22;
        }
        .alternative-link {
            margin-top: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            word-break: break-all;
        }
        .alternative-link p {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #666;
        }
        .alternative-link a {
            color: #2a7d2e;
            font-size: 12px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 13px;
            color: #666;
        }
        .footer a {
            color: #2a7d2e;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🌾 Benue State Agricultural System</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Password Reset Request</h2>
            
            <p>Hello,</p>
            
            <p>We received a request to reset the password for your account in the Benue State Smart Agricultural System and Data Management platform.</p>

            <p>Click the button below to reset your password. This link will expire in <strong>60 minutes</strong> for security reasons.</p>

            <!-- Reset Button -->
            <div class="button-container">
                <a href="{{ $url }}" class="reset-button">Reset Password</a>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <p><strong>⚠️ Security Notice:</strong> If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>
            </div>

            <div class="divider"></div>

            <!-- Alternative Link -->
            <div class="alternative-link">
                <p>If the button above doesn't work, copy and paste this link into your browser:</p>
                <a href="{{ $url }}">{{ $url }}</a>
            </div>

            <p style="margin-top: 25px; font-size: 14px; color: #666;">
                <strong>Note:</strong> This is an automated email from the Benue State Agricultural System. Please do not reply to this message.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Benue State Government</p>
            <p>Ministry of Agriculture and Food Security</p>
            <p>
                <a href="{{ url('/') }}">Visit Website</a> | 
                <a href="mailto:support@benue.gov.ng">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>