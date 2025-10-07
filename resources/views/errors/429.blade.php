@extends('errors::layout')

@section('title', 'Too Many Requests')
@section('code', '429')
@section('message')
    You've made too many requests in a short period. Please wait a moment before trying again.
@endsection

@section('additional_info')
    <p class="text-muted mt-3">
        <i class="ri-time-line me-1"></i>
        Please wait a few minutes before attempting this action again.
    </p>
@endsection