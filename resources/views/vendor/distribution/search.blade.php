@extends('layouts.vendor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Search Farmer</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.distribution.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Search</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <span class="avatar-title bg-primary rounded-circle font-size-24">
                            <i class="ri-search-line"></i>
                        </span>
                    </div>
                    <h4 class="card-title">Search Farmer by NIN or Farmer ID</h4>
                    <p class="text-muted">Enter the farmer's National Identification Number (NIN) or Farmer ID to view their payment status and mark fulfillment</p>
                </div>

                <form action="{{ route('vendor.distribution.search-results') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="search_term" class="form-label">NIN or Farmer ID <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="ri-user-search-line"></i></span>
                            <input type="text" 
                                   class="form-control @error('search_term') is-invalid @enderror" 
                                   id="search_term" 
                                   name="search_term" 
                                   value="{{ old('search_term') }}"
                                   placeholder="Enter NIN or Farmer ID" 
                                   required 
                                   autofocus>
                        </div>
                        @error('search_term')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Example: 12345678901 (NIN) or FRM-2024-0001 (Farmer ID)</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="ri-search-line me-1"></i> Search Farmer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Instructions</h5>
                
                <div class="alert alert-info">
                    <h6 class="alert-heading">How to Mark Fulfillment:</h6>
                    <ol class="mb-0 ps-3">
                        <li>Search for the farmer using their NIN or Farmer ID</li>
                        <li>Verify the farmer's identity and payment status</li>
                        <li>Only applications with status "PAID" can be fulfilled</li>
                        <li>Deliver the exact quantity shown in the paid application</li>
                        <li>Click the "MARK AS FULFILLED" button</li>
                        <li><strong class="text-danger">IMPORTANT:</strong> Fulfillment is one-time only and cannot be undone</li>
                    </ol>
                </div>

                <div class="alert alert-warning">
                    <i class="ri-error-warning-line me-2"></i>
                    <strong>Critical Rule:</strong> Once you mark an application as fulfilled, the farmer's entitlement is completely consumed, even if they didn't collect the maximum allocation. This action is final.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection