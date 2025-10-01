<!DOCTYPE html>

<head>
    <title>Governor Dashboard</title>
<style>body { font-family: sans-serif; background-color: #e3f2fd; padding: 20px; }</style>
</head>
<body>
    <h1>GOVERNOR DASHBOARD</h1>
<p>You have successfully logged in as the **Governor**. Your Spatie role is working correctly!</p>
<p><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>


</body>
</html>




