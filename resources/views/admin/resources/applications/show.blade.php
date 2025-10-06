@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Application Details</h4>
                <a href="{{ route('resources.applications.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Back to Applications
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Card for this Resource -->
    <!-- <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="ri-bar-chart-box-line me-1"></i> 
                        Statistics for "{{ $application->resource->name }}"
                    </h6>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-primary">{{ $resourceStats->total ?? 0 }}</h4>
                                <p class="text-muted mb-0 small">Total Applications</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-success">{{ $resourceStats->approved ?? 0 }}</h4>
                                <p class="text-muted mb-0 small">Granted</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-danger">{{ $resourceStats->rejected ?? 0 }}</h4>
                                <p class="text-muted mb-0 small">Declined</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="mb-1 text-warning">{{ $resourceStats->pending ?? 0 }}</h4>
                            <p class="text-muted mb-0 small">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <!-- User & Resource Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2 mb-3">User Information</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr><th width="150">Name</th><td>{{ $application->user->name }}</td></tr>
                                <tr><th>Email</th><td>{{ $application->user->email }}</td></tr>
                                <tr><th>Submitted</th><td>{{ $application->created_at->format('M d, Y H:i') }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h5 class="border-bottom pb-2 mb-3">Resource Information</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr><th width="150">Name</th><td>{{ $application->resource->name }}</td></tr>
                                <tr><th>Type</th><td>{{ ucfirst($application->resource->target_practice ?? 'General') }}</td></tr>
                                @if($application->resource->requires_payment)
                                <tr>
                                    <th>Payment Status</th>
                                    <td>
                                        <span class="badge bg-{{ $application->payment_status === 'verified' ? 'success' : ($application->payment_status === 'failed' ? 'danger' : ($application->payment_status === 'paid' ? 'primary' : 'warning')) }}">
                                            {{ $application->getPaymentStatusLabel() }}
                                        </span>
                                    </td>
                                </tr>                                            
                                @endif
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $application->status === 'approved' ? 'success' : ($application->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ $application->getStatusLabel() }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Application Details -->
            <div class="mb-4">
                <h5 class="border-bottom pb-2 mb-3">Application Details</h5>
                
                @foreach($application->form_data as $key => $value)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</h6>
                        
                        @if(is_string($value) && \Illuminate\Support\Str::startsWith($value, 'resource_applications/'))
                            @php 
                                $extension = pathinfo($value, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                $isPdf = strtolower($extension) === 'pdf';
                                $fileUrl = asset('storage/' . $value);
                                $fileName = basename($value);
                            @endphp
                            
                            <div class="border p-3 rounded bg-light">
                                <p class="mb-2">
                                    <i class="ri-file-{{ $isImage ? 'image' : ($isPdf ? 'pdf' : 'text') }}-line me-1"></i>
                                    <strong>{{ $fileName }}</strong>
                                </p>
                                
                                @if($isImage)
                                    <div class="mb-3 text-center">
                                        <img src="{{ $fileUrl }}" alt="{{ $fileName }}" 
                                            class="img-fluid border rounded" style="max-height: 400px;">
                                    </div>
                                @elseif($isPdf)
                                    <div class="mb-3">
                                        <iframe src="{{ $fileUrl }}" class="w-100 border rounded" 
                                            style="height: 500px;" title="{{ $fileName }}"></iframe>
                                    </div>
                                @else
                                    <div class="mb-3 text-center">
                                        <i class="ri-file-line display-4 text-muted"></i>
                                        <p class="text-muted">Preview not available</p>
                                    </div>
                                @endif
                                
                                <div class="text-end">
                                    <a href="{{ $fileUrl }}" class="btn btn-sm btn-primary" download="{{ $fileName }}">
                                        <i class="ri-download-line me-1"></i> Download
                                    </a>
                                    <a href="{{ $fileUrl }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="ri-external-link-line me-1"></i> Open in New Tab
                                    </a>
                                </div>
                            </div>
                        @elseif(is_array($value))
                            <p>{{ implode(', ', $value) }}</p>
                        @else
                            <p>{{ $value ?? 'N/A' }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Status Update Forms -->
            @if($application->canBeEdited())
                <div class="border rounded p-4 bg-light">
                    <h5 class="mb-4">
                        <i class="ri-checkbox-circle-line me-1"></i> 
                        Grant or Decline Resource Application
                    </h5>            

                    <div class="row">
                        <!-- Grant Form -->
                        <div class="col-md-6">
                            <form action="{{ route('resources.applications.grant', $application) }}" method="POST">
                                @csrf
                                <div class="card border-success">
                                    <div class="card-header bg-success bg-opacity-10">
                                        <h6 class="mb-0 text-success">
                                            <i class="ri-check-line me-1"></i> Grant Resource
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="grant_notes" class="form-label">Notes (Optional)</label>
                                            <textarea name="notes" id="grant_notes" rows="3" 
                                                class="form-control @error('notes') is-invalid @enderror"
                                                placeholder="Add any notes about granting this resource...">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="ri-check-line me-1"></i> Grant Resource
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Decline Form -->
                        <div class="col-md-6">
                            <form action="{{ route('resources.applications.decline', $application) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to DECLINE this application? This action cannot be undone.');">
                                @csrf
                                <div class="card border-danger">
                                    <div class="card-header bg-danger bg-opacity-10">
                                        <h6 class="mb-0 text-danger">
                                            <i class="ri-close-line me-1"></i> Decline Resource
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="decline_notes" class="form-label">
                                                Reason for Decline <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="notes" id="decline_notes" rows="3" 
                                                class="form-control @error('notes') is-invalid @enderror"
                                                placeholder="Please provide a clear reason for declining this application..." 
                                                required>{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Minimum 10 characters required</small>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="ri-close-line me-1"></i> Decline Resource
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="ri-information-line me-1"></i>
                    This application status is <span class="fw-bold">{{ $application->getStatusLabel() }}</span> and cannot be updated further.
                </div>
            @endif
        </div>
    </div>
@endsection