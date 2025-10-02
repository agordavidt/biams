@extends('layouts.lga_admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Farmer Enrollment Review Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium font-size-14 mb-2">Pending Review</p>
                            <h4 class="mb-0 text-white">{{ $pendingFarmers->total() }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle text-warning font-size-24">
                                <i class="ri-question-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium font-size-14 mb-2">Rejected Profiles</p>
                            <h4 class="mb-0 text-white">{{ $rejectedCount }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle text-danger font-size-24">
                                <i class="ri-close-circle-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium font-size-14 mb-2">Active Farmers</p>
                            <h4 class="mb-0 text-white">{{ $activeCount }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle text-success font-size-24">
                                <i class="ri-user-check-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Submissions Awaiting Review ({{ $pendingFarmers->total() }})</h4>
                    <p class="card-title-desc">These profiles require immediate verification and action.</p>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Farmer Name</th>
                                    <th>Submitted By (EO)</th>
                                    <th>Submission Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingFarmers as $farmer)
                                <tr>
                                    <td><a href="{{ route('lga_admin.farmers.show', $farmer) }}" class="text-primary fw-bold">{{ $farmer->full_name }}</a></td>
                                    <td>{{ $farmer->enrolledBy->name ?? 'N/A' }}</td>
                                    <td>{{ $farmer->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="status-badge status-pending">
                                            {{ ucwords(str_replace('_', ' ', $farmer->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('lga_admin.farmers.show', $farmer) }}" class="btn btn-sm btn-primary waves-effect waves-light" title="Review Profile"><i class="ri-eye-line"></i> Review</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No farmer profiles currently pending review in your LGA.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $pendingFarmers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- NOTE: You will need to pass $rejectedCount and $activeCount from your FarmerReviewController@index method. --}}