@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Super Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Welcome, Super Admin!
                </div>
                <div class="card-body">
                    <p>You have full control over the system. Manage users, admins, and more.</p>
                    <a href="{{ route('super_admin.users') }}" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection