@extends('layouts.admin')

@section('content')
    <!-- Page-Title -->
    
       <!-- start page title -->
   <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Resource Applications</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Resources Applications</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form action="{{ route('resources.applications.index') }}" method="GET" class="row mb-4">
                        <div class="col-md-4">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="form-control" 
                                placeholder="Search by user or resource...">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                @foreach(\App\Models\ResourceApplication::getStatusOptions() as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-filter-2-line align-middle me-1"></i> Filter
                            </button>
                        </div>
                    </form>

                    <!-- Applications Table -->
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Resource</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td>
                                            <div>{{ $application->user->name }}</div>
                                            <small class="text-muted">{{ $application->user->email }}</small>
                                        </td>
                                        <td>{{ $application->resource->name }}</td>
                                        <td>
                                            <span class="status-badge 
                                                @if($application->status === 'approved') status-approved
                                                @elseif($application->status === 'rejected') status-rejected
                                                @else status-pending @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('resources.applications.show', $application) }}" 
                                                class="btn btn-sm btn-primary">
                                                <i class="ri-eye-line align-middle"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
