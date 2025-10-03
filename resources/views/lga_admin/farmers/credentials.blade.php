@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Farmer Login Credentials</h4>
            <div class="page-title-right">
                <a href="{{ route('lga_admin.farmers.show', $farmer) }}" class="btn btn-secondary waves-effect waves-light">
                    <i class="ri-arrow-left-line me-1"></i> Back to Profile
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-light text-primary rounded-circle font-size-24">
                            <i class="ri-key-line"></i>
                        </div>
                    </div>
                    <h4>Farmer Login Credentials</h4>
                    <p class="text-muted">Share these credentials with the farmer</p>
                </div>

                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    The farmer should login and change their password immediately for security.
                </div>

                <div class="credentials-box bg-light p-4 rounded mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Farmer Name</label>
                            <div class="form-control bg-white">{{ $farmer->full_name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">NIN</label>
                            <div class="form-control bg-white">{{ $farmer->nin }}</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <div class="form-control bg-white">{{ $farmer->email }}</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Temporary Password</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-white" id="passwordField" 
                                       value="{{ $farmer->initial_password }}" readonly>
                                <button class="btn btn-outline-secondary" type="button" id="copyPasswordBtn">
                                    <i class="ri-file-copy-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="instructions-box">
                    <h6 class="mb-3">Login Instructions:</h6>
                    <ol class="text-muted">
                        <li>Go to the login page: <code>{{ url('/login') }}</code></li>
                        <li>Enter the email and temporary password above</li>
                        <li>You will be forced to change your password on first login</li>
                        <li>After password change, you can access your farmer dashboard</li>
                    </ol>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="button" class="btn btn-primary" id="copyAllBtn">
                        <i class="ri-file-copy-line me-1"></i> Copy All Credentials
                    </button>
                    <a href="mailto:{{ $farmer->email }}?subject=Your Farmer Account Credentials&body=Email: {{ $farmer->email }}%0D%0ATemporary Password: {{ $farmer->initial_password }}%0D%0ALogin URL: {{ url('/login') }}" 
                       class="btn btn-outline-primary">
                        <i class="ri-mail-line me-1"></i> Send via Email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy password button
    document.getElementById('copyPasswordBtn').addEventListener('click', function() {
        const passwordField = document.getElementById('passwordField');
        passwordField.select();
        document.execCommand('copy');
        
        // Show feedback
        const originalHtml = this.innerHTML;
        this.innerHTML = '<i class="ri-check-line"></i> Copied!';
        setTimeout(() => {
            this.innerHTML = originalHtml;
        }, 2000);
    });

    // Copy all credentials
    document.getElementById('copyAllBtn').addEventListener('click', function() {
        const credentials = `Farmer Credentials:\nName: {{ $farmer->full_name }}\nEmail: {{ $farmer->email }}\nTemporary Password: {{ $farmer->initial_password }}\nLogin URL: {{ url('/login') }}`;
        
        navigator.clipboard.writeText(credentials).then(() => {
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="ri-check-line me-1"></i> All Credentials Copied!';
            setTimeout(() => {
                this.innerHTML = originalHtml;
            }, 2000);
        });
    });
});
</script>
@endpush