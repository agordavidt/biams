@extends('layouts.frontend')

@section('title', 'News & Updates | BSSADMS')

@section('content')
<!-- Page Title -->
<div class="page__title" style="background-image: url('{{ asset('frontend/assets/img/hero_pages.jpg') }}');">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="page__title-content text-center pt-120 pb-80">
                    <h3 class="breadcrumb-title text-white mb-3">News & Updates</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">News</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- News Section -->
<div class="latest-news-area-2 pt-120 pb-90">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-9 mx-auto">
                <div class="tp-section-wrap text-center mb-60">
                    <span class="d-inline-block mb-3"><i class="flaticon-newspaper text-success"></i></span>
                    <h3 class="tp-section-title">Platform Announcements & Insights</h3>
                    <p class="text-muted lead">
                        Stay updated with official system rollouts, farmer success stories, digital agriculture initiatives, and policy developments from the Benue State Ministry of Agriculture.
                    </p>
                </div>
            </div>
        </div>

        <!-- Coming Soon Message -->
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="text-center py-5 px-4 bg-white rounded shadow-sm border">
                    <div class="mb-4">
                        <i class="flaticon-newspaper display-1 text-success" style="opacity: 0.12;"></i>
                    </div>
                    <h4 class="mb-3">News Section Launching Soon</h4>
                    <p class="text-muted mb-4">
                        We are preparing a dedicated newsroom with verified updates, field reports, and digital transformation stories from across Benue’s 23 LGAs.
                    </p>
                    <p class="mb-4">
                        <strong>Expected Launch:</strong> Q1 2026
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('contact') }}" class="tp-btn-h1">
                            Contact for Updates
                        </a>
                        <a href="mailto:agridata@benue.gov.ng" class="btn btn-outline-success">
                            <i class="fal fa-envelope"></i> Subscribe via Email
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional Future Preview -->
        <div class="row mt-5">
            <div class="col-xl-10 mx-auto">
                <div class="alert alert-light border-start border-success border-4">
                    <p class="mb-0">
                        <strong>Future Content Includes:</strong><br>
                        • Farmer enrollment progress reports<br>
                        • New feature releases (e.g., USSD access)<br>
                        • Cooperative registration success stories<br>
                        • Investment and partnership announcements
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection