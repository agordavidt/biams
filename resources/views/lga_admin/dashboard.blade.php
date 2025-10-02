@extends('layouts.lga_admin')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row" >
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm bg-primary rounded-circle">
                                    <span class="avatar-title rounded-circle fs-2">
                                        <i class="ri-map-pin-line text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $lgaName }} Local Government</h5>
                                <p class="text-muted mb-0">LGA Administrator - {{ Auth::user()->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1 text-warning">{{ $pendingCount ?? 0 }}</h4>
                                <p class="text-muted mb-0">Submissions Pending Review</p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-warning rounded-circle">
                                    <i class="ri-time-line font-size-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-truncate">
                            <a href="{{ route('lga_admin.farmers.index') }}" class="text-warning fw-medium">Review Now <i class="ri-arrow-right-line align-middle"></i></a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1 text-danger">{{ $rejectedCount ?? 0 }}</h4>
                                <p class="text-muted mb-0">Rejected Submissions</p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-danger rounded-circle">
                                    <i class="ri-close-circle-line font-size-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-truncate">
                            Profiles sent back to Enrollment Agents.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1 text-success">{{ $activeCount ?? 0 }}</h4>
                                <p class="text-muted mb-0">Verified Active Farmers</p>
                            </div>
                            <div class="avatar-sm">
                                <span class="avatar-title bg-success rounded-circle">
                                    <i class="ri-user-check-line font-size-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-truncate">
                            Accounts successfully activated in your LGA.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card border">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('lga_admin.farmers.index') }}" class="btn btn-primary">
                                <i class="ri-list-check-2 me-1"></i>
                                Review Enrollment Submissions
                            </a>
                            <a href="{{ route('lga_admin.agents.index') }}" class="btn btn-outline-primary">
                                <i class="ri-user-settings-line me-1"></i>
                                Manage Enrollment Agents
                            </a>
                            <a href="#" class="btn btn-outline-primary">
                                <i class="ri-team-line me-1"></i>
                                View All Farmers
                            </a>
                            <a href="#" class="btn btn-outline-primary">
                                <i class="ri-file-list-line me-1"></i>
                                View Manifests
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Your Responsibilities</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Manage enrollment agents in your LGA
                            </li>
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                **Verify and Activate** farmer profiles
                            </li>
                            <li class="mb-2">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                View farmer data within your LGA
                            </li>
                            <li class="mb-0">
                                <i class="ri-checkbox-circle-line text-success me-2"></i>
                                Manage resource distribution manifests
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border border-primary">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="ri-information-line fs-1 text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title">Getting Started</h5>
                                <p class="card-text mb-0">
                                    As an LGA Administrator for **{{ $lgaName }}**, you have oversight of farmer registrations and resource distribution within your local government area. Use the quick actions above to begin managing your LGA's agricultural programs.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection