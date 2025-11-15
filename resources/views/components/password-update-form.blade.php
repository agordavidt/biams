<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">Change Password</h5>
        <p class="text-muted mb-4">Update your password to keep your account secure.</p>

        @if(session('success') && request()->has('password'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-check-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    {{-- Current Password --}}
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            Current Password <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="current_password" 
                            id="current_password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            required
                        >
                        @error('current_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            New Password <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @else
                            <small class="text-muted">Password must be at least 8 characters long</small>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            Confirm New Password <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation"
                            class="form-control"
                            required
                        >
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>