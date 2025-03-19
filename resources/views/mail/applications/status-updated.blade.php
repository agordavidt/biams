<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resource Application Status Update</title>

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
        .status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            margin: 10px 0;
        }
        .status-approved {
            background-color: #e7f5ed;
            color: #006837;
        }
        .status-rejected {
            background-color: #fee7e7;
            color: #dc3545;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-in-review {
            background-color: #cce5ff;
            color: #004085;
        }
        .note-box {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #006837;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .resource-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('dashboard/images/B-lgo-2.png') }}" alt="logo-light" >
        </div>
        
        <div class="content">
            <h2>Resource Application Status Update</h2>
            
            <p>Dear {{ $application->user->name }},</p>
            
            <p>Your application for <strong>{{ $application->resource->name }}</strong> has been updated to: 
                <span class="status status-{{ strtolower($application->status) }}">
                    {{ ucfirst($application->status) }}
                </span>
            </p>
            
            <div class="resource-details">
                <p><strong>Application ID:</strong> #{{ $application->id }}</p>
                <p><strong>Resource:</strong> {{ $application->resource->name }}</p>
                <p><strong>Submitted Date:</strong> {{ $application->created_at->format('F d, Y') }}</p>
                <p><strong>Last Updated:</strong> {{ $application->updated_at->format('F d, Y h:i A') }}</p>
            </div>
            
            @if ($note)
                <div class="note-box">
                    <h3>Additional Information:</h3>
                    <p>{{ $note }}</p>
                </div>
            @endif
            
            @if ($application->status === 'rejected')
                <p>You can revise and resubmit your application addressing any concerns mentioned above.</p>
                <a href="{{ url('/dashboard/applications/'.$application->id.'/edit') }}" class="button">Revise Application</a>
            @endif
            
            @if ($application->status === 'approved')
                <p>Congratulations! Your application has been approved. 
            @endif
            
            @if (in_array($application->status, ['pending', 'in-review']))
                <p>We are currently reviewing your application. You will be notified once the review process is complete.</p>
                <a href="{{ url('/dashboard/applications/'.$application->id) }}" class="button">View Application Status</a>
            @endif
            
            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
            
            <p>Best regards,<br>
            The BSIADAMS Team</p>
        </div>
        
        <div class="footer">
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">LinkedIn</a>
            </div>
            <p>© {{ date('Y') }} BSIADAMS. All rights reserved.</p>
            <p>Block 8 Suite 11-13 Makurdi/Otukpo Road,<br>
               Behind Total Filing Station, SDP,<br>
               Makurdi, Benue State</p>
        </div>
    </div>
</body>
</html>