@extends('layouts.admin')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Application Details</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- User Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="card-title mb-3">User Information</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" width="200">Name</th>
                                            <td>{{ $application->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>{{ $application->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Submitted Date</th>
                                            <td>{{ $application->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Resource Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="card-title mb-3">Resource Information</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" width="200">Resource Name</th>
                                            <td>{{ $application->resource->name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Type</th>
                                            <td>{{ ucfirst($application->resource->target_practice) }}</td>
                                        </tr>
                                        @if($application->resource->requires_payment)
                                        <tr>
                                            <th scope="row">Payment Status</th>
                                            <td>
                                                <span class="badge bg-{{ $application->payment_status === 'verified' ? 'success' : ($application->payment_status === 'failed' ? 'danger' : ($application->payment_status === 'paid' ? 'primary' : 'warning')) }}">
                                                    {{ $application->getPaymentStatusLabel() }}
                                                </span>
                                            </td>
                                        </tr>                                            
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Application Details -->
                        <div class="col-12 mb-4">
                            <h5 class="card-title mb-3">Application Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        @foreach($application->form_data as $key => $value)
                                            <tr>
                                                <th scope="row" width="200">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                <td>
                                                    @if(is_string($value) && \Illuminate\Support\Str::startsWith($value, 'resource_applications/'))
                                                        <!-- File uploaded in form_data -->
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-3">{{ basename($value) }}</span>
                                                            <div class="btn-group">
                                                                <a href="{{ asset('storage/' . $value) }}" class="btn btn-sm btn-info" target="_blank" title="Open in new tab">
                                                                    <i class="ri-eye-line"></i> View
                                                                </a>
                                                                <a href="{{ asset('storage/' . $value) }}" class="btn btn-sm btn-primary" download title="Download">
                                                                    <i class="ri-download-line"></i> Download
                                                                </a>
                                                                <button type="button" class="btn btn-sm btn-secondary file-preview-btn" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#filePreviewModal"
                                                                        data-file-url="{{ asset('storage/' . $value) }}"
                                                                        data-file-name="{{ basename($value) }}"
                                                                        data-file-extension="{{ pathinfo($value, PATHINFO_EXTENSION) }}"
                                                                        title="Preview in popup">
                                                                    <i class="ri-fullscreen-line"></i> Preview
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @elseif(is_array($value))
                                                        {{ implode(', ', $value) }}
                                                    @else
                                                        {{ $value ?? 'N/A' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Evidence (Bank Transfer Only) -->
                        @if($application->requiresPayment() && $application->resource->payment_option === 'bank_transfer' && $application->payment_receipt_path)
                            <div class="col-12 mb-4">
                                <h5 class="card-title mb-3">Payment Evidence</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row" width="200">Payment Receipt</th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-3">{{ basename($application->payment_receipt_path) }}</span>
                                                        <div class="btn-group">
                                                            <a href="{{ asset('storage/' . $application->payment_receipt_path) }}" class="btn btn-sm btn-info" target="_blank" title="Open in new tab">
                                                                <i class="ri-eye-line"></i> View
                                                            </a>
                                                            <a href="{{ asset('storage/' . $application->payment_receipt_path) }}" class="btn btn-sm btn-primary" download title="Download">
                                                                <i class="ri-download-line"></i> Download
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-secondary file-preview-btn" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#filePreviewModal"
                                                                    data-file-url="{{ asset('storage/' . $application->payment_receipt_path) }}"
                                                                    data-file-name="{{ basename($application->payment_receipt_path) }}"
                                                                    data-file-extension="{{ pathinfo($application->payment_receipt_path, PATHINFO_EXTENSION) }}"
                                                                    title="Preview in popup">
                                                                <i class="ri-fullscreen-line"></i> Preview
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Status Update Form -->
                        @if($application->canBeEdited())
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Update Application Status</h5>
                                        <form action="{{ route('admin.applications.update-status', $application) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-control">
                                                        @foreach(\App\Models\ResourceApplication::getStatusOptions() as $value => $label)
                                                            @if($application->canTransitionTo($value))
                                                                <option value="{{ $value }}" {{ $application->status == $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Note (Optional)</label>
                                                    <textarea name="note" rows="3" 
                                                        class="form-control"
                                                        placeholder="Add a note to the applicant..."></textarea>
                                                </div>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ri-check-line align-middle me-1"></i> Update Status
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="file-loader">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <!-- For images -->
                    <div id="image-preview" class="text-center d-none">
                        <img src="" alt="Preview" class="img-fluid" style="max-height: 500px;">
                    </div>
                    
                    <!-- For PDFs -->
                    <div id="pdf-preview" class="d-none">
                        <iframe src="" width="100%" height="500" frameborder="0"></iframe>
                    </div>
                    
                    <!-- For unsupported files -->
                    <div id="unsupported-preview" class="d-none">
                        <div class="alert alert-warning">
                            <i class="ri-error-warning-line me-2"></i>
                            Preview not available for this file type. Please download to view.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary" id="download-btn">
                        <i class="ri-download-line me-1"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filePreviewBtns = document.querySelectorAll('.file-preview-btn');
            
            filePreviewBtns.forEach(button => {
                button.addEventListener('click', function() {
                    const fileUrl = this.getAttribute('data-file-url');
                    const fileName = this.getAttribute('data-file-name');
                    const fileExtension = this.getAttribute('data-file-extension').toLowerCase();
                    
                    // Set modal title and download button
                    document.getElementById('filePreviewModalLabel').textContent = fileName;
                    document.getElementById('download-btn').href = fileUrl;
                    
                    // Hide all preview containers
                    document.getElementById('image-preview').classList.add('d-none');
                    document.getElementById('pdf-preview').classList.add('d-none');
                    document.getElementById('unsupported-preview').classList.add('d-none');
                    
                    // Show loader
                    document.getElementById('file-loader').classList.remove('d-none');
                    
                    // Handle file type
                    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                        const imgElement = document.querySelector('#image-preview img');
                        imgElement.src = fileUrl;
                        imgElement.onload = () => {
                            document.getElementById('file-loader').classList.add('d-none');
                            document.getElementById('image-preview').classList.remove('d-none');
                        };
                        imgElement.onerror = () => {
                            document.getElementById('file-loader').classList.add('d-none');
                            document.getElementById('unsupported-preview').classList.remove('d-none');
                        };
                    } else if (fileExtension === 'pdf') {
                        const iframeElement = document.querySelector('#pdf-preview iframe');
                        iframeElement.src = fileUrl;
                        iframeElement.onload = () => {
                            document.getElementById('file-loader').classList.add('d-none');
                            document.getElementById('pdf-preview').classList.remove('d-none');
                        };
                        iframeElement.onerror = () => {
                            document.getElementById('file-loader').classList.add('d-none');
                            document.getElementById('unsupported-preview').classList.remove('d-none');
                        };
                    } else {
                        document.getElementById('file-loader').classList.add('d-none');
                        document.getElementById('unsupported-preview').classList.remove('d-none');
                    }
                });
            });
        });
    </script>
@endsection