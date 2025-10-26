@extends('layouts.vendor')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Vendor Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Overview</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<!-- Vendor Info Card -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary rounded-circle font-size-20">
                                <i class="ri-building-line"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">{{ $vendor->legal_name }}</h5>
                        <p class="text-muted mb-0">{{ $vendor->organization_type }} | Registered: {{ $vendor->establishment_date?->format('M Y') ?? 'N/A' }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge badge-soft-{{ $vendor->is_active ? 'success' : 'danger' }} font-size-12">
                            {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Team Members</p>
                        <h4 class="mb-2">{{ $stats['total_team_members'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-team-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Distribution Agents</p>
                        <h4 class="mb-2">{{ $stats['active_distribution_agents'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-user-settings-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Proposed Resources</p>
                        <h4 class="mb-2">{{ $stats['proposed_resources'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-file-list-3-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Active Resources</p>
                        <h4 class="mb-2">{{ $stats['active_resources'] }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-info rounded-3">
                            <i class="ri-check-double-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Quick Actions</h4>
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ route('vendor.team.index') }}" class="btn btn-primary btn-block w-100 mb-2">
                            <i class="ri-team-line me-1"></i> Manage Team
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('vendor.resources.create') }}" class="btn btn-success btn-block w-100 mb-2">
                            <i class="ri-add-circle-line me-1"></i> Propose Resource
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('vendor.resources.index') }}" class="btn btn-info btn-block w-100 mb-2">
                            <i class="ri-list-check me-1"></i> View Resources
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Activity</h4>
                <div class="table-responsive">
                    <table class="table table-nowrap mb-0">
                        <tbody>
                            <tr>
                                <td style="width: 60px;">
                                    <div class="avatar-sm">
                                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-18">
                                            <i class="ri-information-line"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="font-size-14 mb-1">Welcome to Vendor Portal</h5>
                                    <p class="text-muted mb-0">Your vendor account is now active. You can manage your team and propose resources.</p>
                                </td>
                                <td>
                                    <div class="text-end">
                                        <span class="text-muted font-size-12">{{ now()->format('M d, Y') }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection