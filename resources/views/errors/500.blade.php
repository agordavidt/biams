@extends('errors::layout')

@section('title', 'Server Error')
@section('code', '500')
@section('message')
    Something went wrong on our end. Our team has been notified and is working to fix the issue.
@endsection

@section('additional_info')
    <p class="text-muted mt-3">
        <i class="ri-tools-line me-1"></i>
        Error Reference: <strong>{{ uniqid('ERR-') }}</strong>
    </p>
    <p class="text-muted">
        Please try again in a few moments. If the problem persists, contact support with the error reference above.
    </p>
@endsection