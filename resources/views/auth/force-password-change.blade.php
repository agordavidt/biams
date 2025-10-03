<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Change Password - Benue State Smart Agricultural System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Benue State Integrated Agricultural Assets Data Management System" name="description" />
    <meta content="BDIC Team" name="author" />

    <link rel="shortcut icon" href="/dashboard/images/favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="/dashboard/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons CSS -->
    <link href="/dashboard/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App CSS -->
    <link href="/dashboard/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .auth-container {
            max-width: 500px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4 class="card-title mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Security Update Required
                </h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>Welcome, {{ $farmer->full_name }}!
                    </h6>
                    <p class="mb-0">For security reasons, you must change your initial password before accessing the system.</p>
                </div>

                <form method="POST" action="{{ route('password.update_initial') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Initial Password</label>
                        <input type="text" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required 
                               placeholder="Enter the initial password provided to you">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <small>This is the temporary password that was generated for you.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <small>Choose a strong password with at least 8 characters.</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-key me-2"></i>Update Password & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-3">
            <p class="text-muted mb-0">Powered by BDIC | &copy; <script>document.write(new Date().getFullYear())</script> Benue State Smart Agricultural System</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/dashboard/libs/jquery/jquery.min.js"></script>
    <script src="/dashboard/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/dashboard/libs/sweetalert2/sweetalert2.min.js"></script>
</body>

</html>