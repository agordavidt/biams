@extends('layouts.enrollment_agent')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Enrollment Agent Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('enrollment.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Total Farmers</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stats['total_farmers'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                Enrolled by you
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded-circle fs-3">
                                <i class="ri-team-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Verified Farmers</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stats['verified_farmers'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-success">
                                <i class="ri-checkbox-circle-line align-middle"></i> Active
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded-circle fs-3">
                                <i class="ri-user-check-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">Pending Verification</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stats['pending_verification'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="badge badge-soft-warning">
                                <i class="ri-time-line align-middle"></i> Under Review
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded-circle fs-3">
                                <i class="ri-time-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-1">This Month</p>
                        <h4 class="mb-0">
                            <span class="counter-value" data-target="{{ $stats['this_month_enrollments'] }}">0</span>
                        </h4>
                        <p class="text-muted mb-0 mt-2">
                            <span class="text-muted font-size-12">
                                New enrollments
                            </span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded-circle fs-3">
                                <i class="ri-calendar-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Agent Info & Quick Actions -->
<div class="row">
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-user-line me-1"></i> Agent Profile
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-xl mx-auto mb-3">
                        <span class="avatar-title rounded-circle bg-soft-primary text-primary fs-2">
                            {{ strtoupper(substr($agent->name, 0, 1)) }}
                        </span>
                    </div>
                    <h5 class="mb-1">{{ $agent->name }}</h5>
                    <p class="text-muted mb-2">{{ $agent->email }}</p>
                    <div class="mb-3">
                        <span class="badge badge-soft-primary px-3 py-2">
                            <i class="ri-shield-user-line align-middle"></i> Enrollment Agent
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-map-pin-line me-2"></i>LGA
                                </td>
                                <td class="text-end fw-medium">{{ $agent->administrativeUnit->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-user-add-line me-2"></i>Total Enrolled
                                </td>
                                <td class="text-end fw-medium">{{ $stats['total_farmers'] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-checkbox-circle-line me-2"></i>Verified
                                </td>
                                <td class="text-end fw-medium">{{ $stats['verified_farmers'] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">
                                    <i class="ri-user-settings-line me-2"></i>Status
                                </td>
                                <td class="text-end">
                                    <span class="badge badge-soft-success">Active</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-flashlight-line me-1"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('enrollment.farmers.create') }}" class="text-decoration-none">
                            <div class="card module-card border h-100 border-primary">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                            <i class="ri-user-add-line text-primary"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Enroll New Farmer</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Register new farmer profile
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="{{ route('enrollment.farmers.index') }}" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-success fs-3">
                                            <i class="ri-list-check text-success"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">View All Farmers</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        Manage enrolled farmers
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="#" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-warning fs-3">
                                            <i class="ri-time-line text-warning"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Pending Reviews</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        {{ $stats['pending_verification'] }} awaiting approval
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="#" class="text-decoration-none">
                            <div class="card module-card border h-100">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <span class="avatar-title rounded-circle bg-soft-info fs-3">
                                            <i class="ri-file-list-line text-info"></i>
                                        </span>
                                    </div>
                                    <h6 class="mb-2 text-dark">Reports</h6>
                                    <p class="text-muted mb-0 font-size-13">
                                        View enrollment reports
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Getting Started Guide -->
<div class="row">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-soft-primary fs-3">
                                <i class="ri-information-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-2">
                            <i class="ri-lightbulb-line me-1"></i> Getting Started as Enrollment Agent
                        </h5>
                        <p class="card-text mb-3">As an Enrollment Agent for {{ $agent->administrativeUnit->name ?? 'your' }} LGA, you have the following responsibilities:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="mb-0">
                                    <li class="mb-2">
                                        <i class="ri-check-line text-success me-1"></i>
                                        <strong>Enroll new farmers</strong> into the system
                                    </li>
                                    <li class="mb-2">
                                        <i class="ri-check-line text-success me-1"></i>
                                        <strong>Verify farmer information</strong> and documentation
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="mb-0">
                                    <li class="mb-2">
                                        <i class="ri-check-line text-success me-1"></i>
                                        <strong>Update existing profiles</strong> when needed
                                    </li>
                                    <li class="mb-2">
                                        <i class="ri-check-line text-success me-1"></i>
                                        <strong>Submit for LGA Admin approval</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Overview -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-bar-chart-box-line me-1"></i> Enrollment Performance
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-xl-3 col-md-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-primary">{{ $stats['total_farmers'] }}</h4>
                            <p class="text-muted mb-0">Total Enrollments</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-success">{{ $stats['verified_farmers'] }}</h4>
                            <p class="text-muted mb-0">Verified</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="py-3 border-end">
                            <h4 class="mb-1 text-warning">{{ $stats['pending_verification'] }}</h4>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="py-3">
                            <h4 class="mb-1 text-info">{{ $stats['this_month_enrollments'] }}</h4>
                            <p class="text-muted mb-0">This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Module Card Hover Effects
    const moduleCards = document.querySelectorAll('.module-card');
    moduleCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.1)';
            if (!this.classList.contains('border-primary')) {
                this.style.borderColor = '#556ee6';
            }
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
            if (!this.classList.contains('border-primary')) {
                this.style.borderColor = '#e5e7eb';
            }
        });
    });

    // Counter animation
    const counters = document.querySelectorAll('.counter-value');
    const speed = 200;

    counters.forEach(counter => {
        const animate = () => {
            const value = +counter.getAttribute('data-target');
            const data = +counter.innerText;
            const time = value / speed;
            
            if(data < value) {
                counter.innerText = Math.ceil(data + time);
                setTimeout(animate, 1);
            } else {
                counter.innerText = value.toLocaleString();
            }
        }
        
        animate();
    });
});
</script>

<style>
.module-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-animate {
    transition: all 0.3s ease;
}

.card-animate:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>
@endpush