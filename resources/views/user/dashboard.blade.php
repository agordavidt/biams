<!DOCTYPE html>
<title>User Dashboard</title>
<style>body { font-family: sans-serif; background-color: #fff3e0; padding: 20px; }</style>
<h1>USER HOME / MARKETPLACE DASHBOARD</h1>
<p>You have successfully logged in as a **Standard User**. Your Spatie role is working correctly!</p>
<p><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>