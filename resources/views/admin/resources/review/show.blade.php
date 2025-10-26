@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resource Details</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.resources.review.index') }}">Resources</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Resource Information -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="card-title">{{ $resource->name }}</h4>
                        <p class="text-muted mb-0">{{ $resource->vendor->legal_name }}</p>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'proposed' => 'warning',
                                'under_review' => 'info',
                                'approved' => 'primary',
                                'active' => 'success',
                                'rejected' => 'danger',
                                'inactive' => 'secondary'
                            ];
                        @endphp
                        <span class="badge badge-soft-{{ $statusColors[$resource->status] ?? 'secondary' }} font-size-14">
                            {{ ucwords(str_replace('_', ' ', $resource->status)) }}
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 30%;">Resource Type:</th>
                                <td>{{ ucfirst($resource->type) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Unit:</th>
                                <td>{{ $resource->unit }}</td>