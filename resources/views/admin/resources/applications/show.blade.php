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
                                            <td>{{ ucfirst($application->payment_status) }}</td>
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
                                                    @if(is_array($value) && isset($value['path']) && isset($value['filename']))
                                                        <!-- File handling -->
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-3">{{ $value['original_name'] ?? $value['filename'] }}</span>
                                                            <div class="btn-group">
                                                                <!-- View button (opens file in new tab) -->
                                                                <a href="{{ asset('storage/'.$value['path']) }}" class="btn btn-sm btn-info" target="_blank">
                                                                    <i class="ri-eye-line"></i> View
                                                                </a>
                                                                
                                                                <!-- Download button -->
                                                                <a href="{{ asset('storage/'.$value['path']) }}" class="btn btn-sm btn-primary" download>
                                                                    <i class="ri-download-line"></i> Download
                                                                </a>
                                                                
                                                                <!-- Preview button (for modal) -->
                                                                <button type="button" class="btn btn-sm btn-secondary file-preview-btn" 
                                                                        data-bs-toggle="modal" 
                                                                        data-bs-target="#filePreviewModal"
                                                                        data-file-url="{{ asset('storage/'.$value['path']) }}"
                                                                        data-file-name="{{ $value['original_name'] ?? $value['filename'] }}"
                                                                        data-file-extension="{{ pathinfo($value['filename'], PATHINFO_EXTENSION) }}">
                                                                    <i class="ri-fullscreen-line"></i> Preview
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @elseif(is_array($value))
                                                        {{ implode(', ', $value) }}
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
                                                    @foreach(\App\Models\ResourceApplication::getStatusOptions() as $status)
                                                        @if($application->canTransitionTo($status))
                                                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
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
                        <img src="" alt="Preview" class="img-fluid">
                    </div>
                    
                    <!-- For PDFs -->
                    <div id="pdf-preview" class="d-none">
                        <iframe src="" width="100%" height="500" frameborder="0"></iframe>
                    </div>
                    
                    <!-- For text files -->
                    <div id="text-preview" class="d-none">
                        <pre class="border p-3 bg-light"><code></code></pre>
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
        // Handle file preview modal
        const filePreviewBtns = document.querySelectorAll('.file-preview-btn');
        
        filePreviewBtns.forEach(button => {
            button.addEventListener('click', function() {
                const fileUrl = this.getAttribute('data-file-url');
                const fileName = this.getAttribute('data-file-name');
                const fileExtension = this.getAttribute('data-file-extension').toLowerCase();
                
                // Set the modal title
                document.getElementById('filePreviewModalLabel').textContent = fileName;
                
                // Set download button URL
                document.getElementById('download-btn').href = fileUrl;
                
                // Hide all preview containers
                document.getElementById('image-preview').classList.add('d-none');
                document.getElementById('pdf-preview').classList.add('d-none');
                document.getElementById('text-preview').classList.add('d-none');
                document.getElementById('unsupported-preview').classList.add('d-none');
                
                // Show loader
                document.getElementById('file-loader').classList.remove('d-none');
                
                // Determine file type and show appropriate preview
                if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(fileExtension)) {
                    // Image preview
                    const imgElement = document.querySelector('#image-preview img');
                    imgElement.src = fileUrl;
                    imgElement.onload = function() {
                        document.getElementById('file-loader').classList.add('d-none');
                        document.getElementById('image-preview').classList.remove('d-none');
                    };
                } else if (fileExtension === 'pdf') {
                    // PDF preview
                    const iframeElement = document.querySelector('#pdf-preview iframe');
                    iframeElement.src = fileUrl;
                    iframeElement.onload = function() {
                        document.getElementById('file-loader').classList.add('d-none');
                        document.getElementById('pdf-preview').classList.remove('d-none');
                    };
                } else if (['txt', 'csv', 'json', 'html', 'xml', 'md'].includes(fileExtension)) {
                    // Text file preview
                    fetch(fileUrl)
                        .then(response => response.text())
                        .then(text => {
                            document.querySelector('#text-preview code').textContent = text;
                            document.getElementById('file-loader').classList.add('d-none');
                            document.getElementById('text-preview').classList.remove('d-none');
                        })
                        .catch(error => {
                            console.error('Error fetching text file:', error);
                            document.getElementById('file-loader').classList.add('d-none');
                            document.getElementById('unsupported-preview').classList.remove('d-none');
                        });
                } else {
                    // Unsupported file type
                    document.getElementById('file-loader').classList.add('d-none');
                    document.getElementById('unsupported-preview').classList.remove('d-none');
                }
            });
        });
    });
</script>
@endsection