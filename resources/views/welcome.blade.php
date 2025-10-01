<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benue State Smart Agricultural System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f0f4f8; /* Light background */
            text-align: center;
        }
        .container {
            padding: 40px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #1a73e8; /* Blue color for header */
            margin-bottom: 10px;
        }
        p {
            color: #5f6368;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #34a853; /* Green button */
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #1e8e3e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Benue State Smart Agricultural System</h1>
        <p>A digital platform for farmer registration, resource management, and state-level governance.</p>
        
        {{-- Check if the user is authenticated, otherwise show Login button --}}
        @auth
            <a href="{{ url('/home') }}" class="btn">Go to Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn">Login to Continue</a>
            
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn" style="margin-left: 15px; background-color: #6c757d;">Register</a>
            @endif
        @endauth
        
    </div>
</body>
</html>