@extends('errors::layout')

@section('title', 'Bad Request')
@section('code', '400')
@section('message')
    The request could not be understood by the server. Please check your input and try again.
@endsection