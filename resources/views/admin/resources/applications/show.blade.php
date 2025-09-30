@extends('layouts.admin')

@section('content')
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Application Details</h4>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

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
                                <tr><th>Type</th><td>{{ ucfirst($application->resource->target_practice) }}</td></tr>
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
                                <tr><th>Status</th>
                                    <td>
                                        <span class="badge bg-{{ $application->status === 'approved' ? 'success' : ($application->status === 'rejected' ? 'danger' : ($application->status === 'delivered' ? 'info' : 'warning')) }}">
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
                                <!-- File name -->
                                <p class="mb-2">
                                    <i class="ri-file-{{ $isImage ? 'image' : ($isPdf ? 'pdf' : 'text') }}-line me-1"></i>
                                    <strong>{{ $fileName }}</strong>
                                </p>
                                
                                <!-- File preview -->
                                @if($isImage)
                                    <!-- Direct image display -->
                                    <div class="mb-3 text-center">
                                        <img src="{{ $fileUrl }}" alt="{{ $fileName }}" 
                                            class="img-fluid border rounded" style="max-height: 400px;">
                                    </div>
                                @elseif($isPdf)
                                    <!-- PDF embedded viewer -->
                                    <div class="mb-3">
                                        <iframe src="{{ $fileUrl }}" class="w-100 border rounded" 
                                            style="height: 500px;" title="{{ $fileName }}"></iframe>
                                    </div>
                                @else
                                    <!-- File icon for non-previewable files -->
                                    <div class="mb-3 text-center">
                                        <i class="ri-file-line display-4 text-muted"></i>
                                        <p class="text-muted">Preview not available</p>
                                    </div>
                                @endif
                                
                                <!-- File actions -->
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

            <!-- Status Update Form -->
    @if($application->canBeEdited())
            <div class="border rounded p-3 bg-light">
            <h5 class="mb-3">Grant or Decline Resource</h5>            

            <form action="{{ route('admin.applications.grant', $application) }}" method="POST" class="d-inline-block me-2">
            @csrf
            <div class="mb-3">
            <textarea name="notes" rows="2" class="form-control" style="min-width: 300px;"
            placeholder="Notes for Grant (Optional)"></textarea>
            </div>
            <button type="submit" class="btn btn-success">
            <i class="ri-check-line me-1"></i> Grant Resource
            </button>
            </form>

            <form action="{{ route('admin.applications.decline', $application) }}" method="POST" class="d-inline-block">
            @csrf
            <div class="mb-3">
            <textarea name="notes" rows="2" class="form-control" style="min-width: 300px;"
            placeholder="Reason for Decline (Required)"></textarea>
            </div>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to DECLINE this application?');">
            <i class="ri-close-line me-1"></i> Decline Resource
            </button>
            </form>
            </div>
            @else
            <div class="alert alert-info">
            The application status is <span class="fw-bold">{{ $application->getStatusLabel() }} </span>and cannot be updated further.
            </div>
            @endif
        </div>
    </div>
@endsection