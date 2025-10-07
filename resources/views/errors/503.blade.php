@extends('errors::layout')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('message')
    The system is temporarily unavailable for maintenance. We'll be back shortly.
@endsection

@section('additional_info')
    <p class="text-muted mt-3">
        <i class="ri-settings-3-line me-1"></i>
        We're performing scheduled maintenance to improve your experience.
    </p>
    <p class="text-muted">
        Please check back in a few minutes.
    </p>
@endsection