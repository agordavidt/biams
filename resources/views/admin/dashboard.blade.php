<!DOCTYPE html>
<title>State Admin Dashboard</title>
<style>body { font-family: sans-serif; background-color: #e8f5e9; padding: 20px; }</style>
<h1>STATE ADMIN DASHBOARD (State Admin / LGA Admin access)</h1>
<p>You have successfully logged in as an **Admin**. Your Spatie role is working correctly!</p>
<p><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></p>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>