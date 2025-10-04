@extends('layouts.super_admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Predefined Analytics Reports</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('analytics.dashboard') }}">Analytics</a></li>
                        <li class="breadcrumb-item active">Predefined Reports</li>
                    </ol>
                </div>
            </div>
            <p class="text-muted mb-0">Quick access to commonly requested insights and analysis</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="hstack gap-3 justify-content-end">
                <a href="{{ route('analytics.advanced.index') }}" class="btn btn-primary">
                    <i class="ri-slider-line align-middle me-1"></i> Custom Filters
                </a>
            </div>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="row">
        @foreach($reports as $key => $report)
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card card-hover h-100">
                <div class="card-header bg-primary bg-opacity-10 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            @if($key === 'women_in_fisheries')
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-primary bg-opacity-10 rounded text-primary">
                                        <i class="ri-fish-line"></i>
                                    </div>
                                </div>
                            @elseif($key === 'youth_cassava_farmers')
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-success bg-opacity-10 rounded text-success">
                                        <i class="ri-seedling-line"></i>
                                    </div>
                                </div>
                            @elseif($key === 'cooperative_rice_farmers')
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-info bg-opacity-10 rounded text-info">
                                        <i class="ri-handshake-line"></i>
                                    </div>
                                </div>
                            @elseif($key === 'educated_livestock_farmers')
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-warning bg-opacity-10 rounded text-warning">
                                        <i class="ri-bear-smile-line"></i>
                                    </div>
                                </div>
                            @elseif($key === 'small_scale_farmers')
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-danger bg-opacity-10 rounded text-danger">
                                        <i class="ri-home-gear-line"></i>
                                    </div>
                                </div>
                            @elseif($key === 'organic_farmers')
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-purple bg-opacity-10 rounded text-purple">
                                        <i class="ri-leaf-line"></i>
                                    </div>
                                </div>
                            @else
                                <div class="avatar-xs">
                                    <div class="avatar-title bg-secondary bg-opacity-10 rounded text-secondary">
                                        <i class="ri-bar-chart-line"></i>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-0">{{ $report['name'] }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">{{ $report['description'] ?? 'Detailed analysis based on specific criteria' }}</p>
                    
                    <h6 class="text-muted mb-3">Filters Applied:</h6>
                    <div class="vstack gap-2">
                        @foreach($report['filters'] as $filterKey => $filterValue)
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold">{{ ucwords(str_replace('_', ' ', $filterKey)) }}:</span>
                                @if(is_array($filterValue))
                                    @if(isset($filterValue['min']) || isset($filterValue['max']))
                                        <span class="text-muted">
                                            {{ $filterValue['min'] ?? 'Any' }} - {{ $filterValue['max'] ?? 'Any' }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            {{ implode(', ', array_map('ucwords', $filterValue)) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted">{{ ucwords(str_replace('_', ' ', $filterValue)) }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0">
                    <a href="{{ route('analytics.advanced.predefined.run', $key) }}" class="btn btn-primary w-100">
                        <i class="ri-play-line align-middle me-1"></i> Run Report
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Custom Report Suggestion -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border border-info border-opacity-25">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-info bg-opacity-10 rounded text-info">
                                    <i class="ri-lightbulb-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title text-info mb-2">Need a Different Report?</h5>
                            <p class="text-muted mb-3">Use the <strong>Custom Filters</strong> to create your own specific analysis tailored to your exact requirements:</p>
                            
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <ul class="list-unstyled text-muted mb-3 mb-lg-0">
                                        <li class="mb-2">
                                            <i class="ri-check-line text-success me-2"></i>
                                            Combine multiple demographic filters
                                        </li>
                                        <li class="mb-2">
                                            <i class="ri-check-line text-success me-2"></i>
                                            Filter by specific crops, animals, or fish species
                                        </li>
                                        <li class="mb-2">
                                            <i class="ri-check-line text-success me-2"></i>
                                            Analyze by geographic location (LGA)
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-6">
                                    <ul class="list-unstyled text-muted">
                                        <li class="mb-2">
                                            <i class="ri-check-line text-success me-2"></i>
                                            Filter by cooperative membership
                                        </li>
                                        <li class="mb-2">
                                            <i class="ri-check-line text-success me-2"></i>
                                            Analyze by education level and occupation
                                        </li>
                                        <li class="mb-2">
                                            <i class="ri-check-line text-success me-2"></i>
                                            Export results for further analysis
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="hstack gap-3">
                                <a href="{{ route('analytics.advanced.index') }}" class="btn btn-primary">
                                    Create Custom Report <i class="ri-arrow-right-line align-middle ms-1"></i>
                                </a>
                                <a href="{{ route('analytics.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="ri-dashboard-line align-middle me-1"></i> View Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light bg-opacity-10 border-bottom">
                    <h5 class="card-title mb-0">Report Categories</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-primary bg-opacity-10 rounded text-primary">
                                        <i class="ri-user-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">Demographic</h6>
                                <small class="text-muted">Age, Gender, Education</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-success bg-opacity-10 rounded text-success">
                                        <i class="ri-seedling-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">Crop Farming</h6>
                                <small class="text-muted">Crops, Methods, Yields</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-info bg-opacity-10 rounded text-info">
                                        <i class="ri-bear-smile-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">Livestock</h6>
                                <small class="text-muted">Animals, Herd Size</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-warning bg-opacity-10 rounded text-warning">
                                        <i class="ri-fish-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">Fisheries</h6>
                                <small class="text-muted">Fish Species, Methods</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-purple bg-opacity-10 rounded text-purple">
                                        <i class="ri-handshake-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">Cooperatives</h6>
                                <small class="text-muted">Membership, Engagement</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="avatar-sm mx-auto mb-2">
                                    <div class="avatar-title bg-danger bg-opacity-10 rounded text-danger">
                                        <i class="ri-map-pin-line"></i>
                                    </div>
                                </div>
                                <h6 class="mb-1">Geographic</h6>
                                <small class="text-muted">LGA, Regional Analysis</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        border-color: #4e73df;
    }
    
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .vstack {
        display: flex;
        flex-direction: column;
    }
    
    .hstack {
        display: flex;
        flex-direction: row;
        align-items: center;
    }
</style>
@endpush

@push('scripts')
<script>
    // Add animation to cards on page load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card-hover');
        cards.forEach((card, index) => {
            card.style.animation = `fadeInUp 0.5s ease ${index * 0.1}s both`;
        });
    });
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush