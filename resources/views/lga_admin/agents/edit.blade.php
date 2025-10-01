@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Enrollment Agent</h4>
            <div class="page-title-right">
                <a href="{{ route('lga_admin.agents.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Agents
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('lga_admin.agents.update', $agent) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Personal Information</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $agent->name) }}" 
                                       placeholder="Enter full name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $agent->email) }}" 
                                       placeholder="agent@example.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" 
                                       name="phone_number" 
                                       id="phone_number" 
                                       class="form-control @error('phone_number') is-invalid @enderror" 
                                       value="{{ old('phone_number', $agent->phone_number) }}" 
                                       placeholder="080XXXXXXXX">
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" 
                                        id="status" 
                                        class="form-control @error('status') is-invalid @enderror" 
                                        required>
                                    <option value="pending" {{ old('status', $agent->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="onboarded" {{ old('status', $agent->status) == 'onboarded' ? 'selected' : '' }}>Onboarded</option>
                                    <option value="rejected" {{ old('status', $agent->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Security Information</h5>
                            
                            <div class="alert alert-warning">
                                <i class="ri-alert-line me-2"></i>
                                Leave password fields empty to keep current password
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Minimum 8 characters">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="ri-eye-line" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control" 
                                       placeholder="Re-enter new password">
                            </div>

                            <div class="alert alert-info mt-4">
                                <i class="ri-information-line me-2"></i>
                                <strong>Administrative Assignment:</strong><br>
                                {{ $agent->administrativeUnit->name ?? 'N/A' }}
                            </div>

                            <div class="card bg-light mt-3">
                                <div class="card-body">
                                    <h6 class="mb-2"><i class="ri-time-line me-1"></i> Account Details</h6>
                                    <p class="mb-1 small"><strong>Created:</strong> {{ $agent->created_at->format('M d, Y h:i A') }}</p>
                                    <p class="mb-0 small"><strong>Last Updated:</strong> {{ $agent->updated_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Role & Permissions</h6>
                                    <p class="card-text mb-2">
                                        <i class="ri-shield-check-line text-success me-1"></i>
                                        <strong>Role:</strong> Enrollment Agent
                                    </p>
                                    <p class="card-text mb-0">
                                        <i class="ri-list-check text-primary me-1"></i>
                                        <strong>Permissions:</strong> Enroll Farmers, Verify Farmer Data, Update Farmer Profiles, View Farmer Data
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line align-middle me-1"></i> Update Enrollment Agent
                        </button>
                        <a href="{{ route('lga_admin.agents.index') }}" class="btn btn-secondary">
                            <i class="ri-close-line align-middle me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('ri-eye-line');
            toggleIcon.classList.add('ri-eye-off-line');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('ri-eye-off-line');
            toggleIcon.classList.add('ri-eye-line');
        }
    });

    // Form validation feedback
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        // Only validate if password field has a value
        if (password && password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Passwords do not match. Please try again.',
            });
        }
    });
</script>
@endpush