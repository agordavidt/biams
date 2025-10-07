@extends('errors::layout')

@section('title', 'Unauthorized')
@section('code', '401')
@section('message')
    You are not authorized to access this resource. Please log in to continue.
@endsection

@section('action_button')
    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
        <i class="ri-login-box-line me-2"></i>Login
    </a>
@endsection