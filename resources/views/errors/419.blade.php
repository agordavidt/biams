@extends('errors::layout')

@section('title', 'Page Expired')
@section('code', '419')
@section('message')
    Your session has expired. Please refresh the page and try again.
@endsection

@section('additional_info')
    <p class="text-muted mt-3">
        <i class="ri-information-line me-1"></i>
        This usually happens when you've been inactive for a while or when your form submission took too long.
    </p>
@endsection