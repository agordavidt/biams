
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Account Created</title>
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
            font-size: 20px;
            font-weight: 600;
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
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #2a7d2e;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-box h3 {
            margin: 0 0 15px 0;
            color: #2a7d2e;
            font-size: 16px;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 4px;
        }
        .credential-label {
            font-weight: 600;
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .credential-value {
            font-size: 16px;
            color: #2a7d2e;
            font-family: 'Courier New', monospace;
            margin-top: 5px;
            padding: 8px;
            background-color: #f0f0f0;
            border-radius: 3px;
            word-break: break-all;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .login-button {
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
        .login-button:hover {
            background-color: #1f5e22;
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
        .unit-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .unit-info p {
            margin: 5px 0;
            color: #0d47a1;
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
            <h1>🌾 Benue State Smart Agriculture and Data Management Portal</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Administrator Account Created</h2>
            
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>Your administrator account has been successfully created for the Benue State Smart Agricultural System and Data Management platform.</p>

            <!-- Unit/Role Information -->
            @if($administrativeUnit || $administrativeUnitName)
            <div class="unit-info">
                <p><strong>Role:</strong> {{ $roleName }}</p>
                @if($administrativeUnitName)
                <p><strong>Administrative Unit:</strong> {{ $administrativeUnitName }}</p>
                @endif
            </div>
            @else
            <div class="unit-info">
                <p><strong>Role:</strong> {{ $roleName }}</p>
            </div>
            @endif

            <!-- Login Credentials -->
            <div class="info-box">
                <h3>Your Login Credentials</h3>
                
                <div class="credential-item">
                    <div class="credential-label">Email Address</div>
                    <div class="credential-value">{{ $user->email }}</div>
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">Temporary Password</div>
                    <div class="credential-value">{{ $password }}</div>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <p><strong>Security Notice:</strong> Please keep these credentials secure and change your password immediately after your first login.</p>
            </div>

            <!-- Login Button -->
            <div class="button-container">
                <a href="{{ $loginUrl }}" class="login-button">Login to Your Account</a>
            </div>

            <div class="divider"></div>

            <!-- General Responsibilities -->
            <!-- <h3 style="color: #2a7d2e; margin-bottom: 10px;">As an Administrator, you will have access to:</h3>
            <ul style="color: #555; line-height: 1.8;">
                <li>User and role management for your administrative unit</li>
                <li>Data analytics and reporting dashboards</li>
                <li>System configuration and settings</li>
                <li>Support and communication tools</li>
            </ul> -->

            <p style="margin-top: 25px; font-size: 14px; color: #666;">
                If you have any questions or encounter any issues, please contact the system administrator or support team at <a href="mailto:support@benue.gov.ng" style="color: #2a7d2e;">support@benue.gov.ng</a>.
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